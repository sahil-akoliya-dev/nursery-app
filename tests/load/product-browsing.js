import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');

export const options = {
    stages: [
        { duration: '2m', target: 100 },  // Ramp up to 100 users
        { duration: '5m', target: 100 },    // Stay at 100 users
        { duration: '2m', target: 0 },      // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<500', 'p(99)<1000'], // 95% of requests under 500ms, 99% under 1s
        http_req_failed: ['rate<0.01'],   // Less than 1% errors
        errors: ['rate<0.01'],
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000/api';

export default function () {
    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        tags: { name: 'ProductBrowsing' },
    };

    // 1. List products with filters
    let listProductsRes = http.get(`${BASE_URL}/products?page=1&per_page=20&sort=price&order=asc`, params);
    let listProductsSuccess = check(listProductsRes, {
        'list products status is 200': (r) => r.status === 200,
        'list products has data': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.data !== undefined;
        },
    });
    errorRate.add(!listProductsSuccess);
    sleep(1);

    // 2. Get random product details
    const products = JSON.parse(listProductsRes.body).data || [];
    if (products.length > 0) {
        const randomProduct = products[Math.floor(Math.random() * products.length)];
        const productId = randomProduct.id;
        
        let productDetailRes = http.get(`${BASE_URL}/products/${productId}`, params);
        let productDetailSuccess = check(productDetailRes, {
            'product detail status is 200': (r) => r.status === 200,
            'product detail has data': (r) => {
                const body = JSON.parse(r.body);
                return body.success === true && body.data !== undefined;
            },
        });
        errorRate.add(!productDetailSuccess);
        sleep(1);
    }

    // 3. Search products
    const searchTerms = ['plant', 'flower', 'pot', 'indoor', 'outdoor'];
    const randomSearch = searchTerms[Math.floor(Math.random() * searchTerms.length)];
    
    let searchRes = http.get(`${BASE_URL}/products?search=${randomSearch}&page=1`, params);
    let searchSuccess = check(searchRes, {
        'search products status is 200': (r) => r.status === 200,
        'search products returns results': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true;
        },
    });
    errorRate.add(!searchSuccess);
    sleep(1);

    // 4. Get categories
    let categoriesRes = http.get(`${BASE_URL}/categories`, params);
    let categoriesSuccess = check(categoriesRes, {
        'categories status is 200': (r) => r.status === 200,
        'categories has data': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.data !== undefined;
        },
    });
    errorRate.add(!categoriesSuccess);
    sleep(1);

    // 5. Filter by category
    const categories = JSON.parse(categoriesRes.body).data || [];
    if (categories.length > 0) {
        const randomCategory = categories[Math.floor(Math.random() * categories.length)];
        const categoryId = randomCategory.id;
        
        let categoryProductsRes = http.get(`${BASE_URL}/products?category_id=${categoryId}&page=1`, params);
        let categoryProductsSuccess = check(categoryProductsRes, {
            'category products status is 200': (r) => r.status === 200,
            'category products has data': (r) => {
                const body = JSON.parse(r.body);
                return body.success === true;
            },
        });
        errorRate.add(!categoryProductsSuccess);
        sleep(1);
    }
}

export function handleSummary(data) {
    return {
        'stdout': JSON.stringify(data, null, 2),
        'tests/load/results/product-browsing-summary.json': JSON.stringify(data, null, 2),
    };
}

