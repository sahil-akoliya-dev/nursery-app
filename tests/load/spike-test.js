import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');

export const options = {
    stages: [
        { duration: '30s', target: 100 },   // Normal load: 100 users
        { duration: '10s', target: 500 },    // Spike: 500 users (5x increase)
        { duration: '2m', target: 500 },     // Sustain spike: 500 users
        { duration: '1m', target: 100 },     // Recovery: back to 100 users
        { duration: '30s', target: 0 },       // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<1000', 'p(99)<2000'], // More lenient during spike
        http_req_failed: ['rate<0.05'], // Allow up to 5% errors during spike
        errors: ['rate<0.05'],
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000/api';

export default function () {
    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        tags: { name: 'SpikeTest' },
    };

    // Mix of different operations to simulate real traffic
    const operation = Math.random();

    if (operation < 0.4) {
        // 40% - Browse products
        const listProductsRes = http.get(`${BASE_URL}/products?page=${Math.floor(Math.random() * 5) + 1}&per_page=20`, params);
        const listSuccess = check(listProductsRes, {
            'list products status is 200': (r) => r.status === 200 || r.status === 429, // Allow rate limit during spike
            'list products has response': (r) => r.body !== undefined,
        });
        errorRate.add(!listSuccess);
        sleep(1);
    } else if (operation < 0.7) {
        // 30% - View product details
        const productId = Math.floor(Math.random() * 50) + 1; // Assume products 1-50 exist
        const productRes = http.get(`${BASE_URL}/products/${productId}`, params);
        const productSuccess = check(productRes, {
            'product detail status is 200 or 404': (r) => r.status === 200 || r.status === 404 || r.status === 429,
            'product detail has response': (r) => r.body !== undefined,
        });
        errorRate.add(!productSuccess);
        sleep(1);
    } else if (operation < 0.85) {
        // 15% - Search products
        const searchTerms = ['plant', 'flower', 'pot', 'indoor'];
        const searchTerm = searchTerms[Math.floor(Math.random() * searchTerms.length)];
        const searchRes = http.get(`${BASE_URL}/products?search=${searchTerm}`, params);
        const searchSuccess = check(searchRes, {
            'search status is 200 or 429': (r) => r.status === 200 || r.status === 429,
            'search has response': (r) => r.body !== undefined,
        });
        errorRate.add(!searchSuccess);
        sleep(1);
    } else {
        // 15% - Get categories
        const categoriesRes = http.get(`${BASE_URL}/categories`, params);
        const categoriesSuccess = check(categoriesRes, {
            'categories status is 200 or 429': (r) => r.status === 200 || r.status === 429,
            'categories has response': (r) => r.body !== undefined,
        });
        errorRate.add(!categoriesSuccess);
        sleep(1);
    }
}

export function handleSummary(data) {
    return {
        'stdout': JSON.stringify(data, null, 2),
        'tests/load/results/spike-test-summary.json': JSON.stringify(data, null, 2),
    };
}

