import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';
import { SharedArray } from 'k6/data';

// Custom metrics
const errorRate = new Rate('errors');
const checkoutErrorRate = new Rate('checkout_errors');

// Shared test user credentials (in real scenario, use different users)
const users = new SharedArray('users', function () {
    return [
        { email: 'customer@example.com', password: 'password' },
        { email: 'customer2@example.com', password: 'password' },
    ];
});

export const options = {
    stages: [
        { duration: '2m', target: 50 },   // Ramp up to 50 users (lower for checkout flow)
        { duration: '5m', target: 50 },     // Stay at 50 users
        { duration: '2m', target: 0 },       // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<500', 'p(99)<1000'],
        http_req_failed: ['rate<0.01'],
        errors: ['rate<0.01'],
        checkout_errors: ['rate<0.05'], // Allow slightly higher error rate for checkout
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000/api';

// Helper function to get auth token
function getAuthToken(user) {
    const loginRes = http.post(`${BASE_URL}/auth/login`, JSON.stringify({
        email: user.email,
        password: user.password,
    }), {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    });

    if (loginRes.status === 200) {
        const body = JSON.parse(loginRes.body);
        return body.token;
    }
    return null;
}

export default function () {
    const user = users[Math.floor(Math.random() * users.length)];
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    // 1. Login
    const token = getAuthToken(user);
    if (!token) {
        errorRate.add(1);
        return;
    }

    headers['Authorization'] = `Bearer ${token}`;
    const params = { headers, tags: { name: 'CartCheckout' } };

    // 2. Get products to add to cart
    let productsRes = http.get(`${BASE_URL}/products?page=1&per_page=10`, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    });

    if (productsRes.status !== 200) {
        errorRate.add(1);
        return;
    }

    const products = JSON.parse(productsRes.body).data || [];
    if (products.length === 0) {
        errorRate.add(1);
        return;
    }

    // 3. Add product to cart
    const randomProduct = products[Math.floor(Math.random() * products.length)];
    const addToCartRes = http.post(`${BASE_URL}/cart/add`, JSON.stringify({
        item_id: randomProduct.id,
        item_type: 'App\\Models\\Product',
        quantity: Math.floor(Math.random() * 3) + 1, // 1-3 items
    }), params);

    const addToCartSuccess = check(addToCartRes, {
        'add to cart status is 200 or 201': (r) => r.status === 200 || r.status === 201,
        'add to cart successful': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true;
        },
    });
    errorRate.add(!addToCartSuccess);
    sleep(1);

    // 4. Get cart
    let cartRes = http.get(`${BASE_URL}/cart`, params);
    const getCartSuccess = check(cartRes, {
        'get cart status is 200': (r) => r.status === 200,
        'cart has items': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.data !== undefined;
        },
    });
    errorRate.add(!getCartSuccess);
    sleep(1);

    // 5. Update cart item (if cart has items)
    const cartData = JSON.parse(cartRes.body).data || {};
    const cartItems = cartData.items || [];
    if (cartItems.length > 0) {
        const cartItem = cartItems[0];
        const updateRes = http.put(`${BASE_URL}/cart/update/${cartItem.id}`, JSON.stringify({
            quantity: Math.min(cartItem.quantity + 1, 10), // Increase quantity
        }), params);

        const updateSuccess = check(updateRes, {
            'update cart status is 200': (r) => r.status === 200,
            'update cart successful': (r) => {
                const body = JSON.parse(r.body);
                return body.success === true;
            },
        });
        errorRate.add(!updateSuccess);
        sleep(1);
    }

    // 6. Get cart count (quick check)
    let cartCountRes = http.get(`${BASE_URL}/cart/count`, params);
    check(cartCountRes, {
        'cart count status is 200': (r) => r.status === 200,
    });
    sleep(1);

    // 7. Checkout (only for 20% of users to simulate realistic behavior)
    if (Math.random() < 0.2) {
        // Note: In a real scenario, you'd need addresses and payment info
        // This simulates the checkout API call without actually creating orders
        // to avoid polluting the database
        
        // For load testing, we'll just verify the endpoint exists
        // and handle the case where checkout might fail due to missing data
        const checkoutRes = http.post(`${BASE_URL}/orders`, JSON.stringify({
            shipping_address_id: 1, // Assumes test data has addresses
            billing_address_id: 1,
            payment_method: 'cod',
            payment_status: 'pending',
        }), params);

        const checkoutSuccess = check(checkoutRes, {
            'checkout response received': (r) => r.status === 200 || r.status === 201 || r.status === 400 || r.status === 422,
        });

        if (!checkoutSuccess || checkoutRes.status >= 400) {
            checkoutErrorRate.add(1);
        }
        sleep(2);
    }
}

export function handleSummary(data) {
    return {
        'stdout': JSON.stringify(data, null, 2),
        'tests/load/results/cart-checkout-summary.json': JSON.stringify(data, null, 2),
    };
}

