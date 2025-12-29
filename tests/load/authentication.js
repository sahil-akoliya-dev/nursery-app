import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';
import { SharedArray } from 'k6/data';

// Custom metrics
const errorRate = new Rate('errors');
const authErrorRate = new Rate('auth_errors');

// Shared test user credentials
const testUsers = new SharedArray('testUsers', function () {
    return [
        { email: 'customer@example.com', password: 'password' },
        { email: 'admin@nursery-app.com', password: 'password123' },
    ];
});

export const options = {
    stages: [
        { duration: '2m', target: 100 },  // Ramp up to 100 users
        { duration: '5m', target: 100 },  // Stay at 100 users
        { duration: '2m', target: 0 },     // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<500', 'p(99)<1000'],
        http_req_failed: ['rate<0.01'],
        errors: ['rate<0.01'],
        auth_errors: ['rate<0.05'], // Slightly higher tolerance for auth errors
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000/api';

export default function () {
    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        tags: { name: 'Authentication' },
    };

    // 1. Login
    const user = testUsers[Math.floor(Math.random() * testUsers.length)];
    const loginRes = http.post(`${BASE_URL}/auth/login`, JSON.stringify({
        email: user.email,
        password: user.password,
    }), params);

    const loginSuccess = check(loginRes, {
        'login status is 200': (r) => r.status === 200,
        'login returns token': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.token !== undefined;
        },
    });

    if (!loginSuccess) {
        authErrorRate.add(1);
        errorRate.add(1);
        sleep(1);
        return;
    }

    const token = JSON.parse(loginRes.body).token;
    const authHeaders = {
        ...params.headers,
        'Authorization': `Bearer ${token}`,
    };
    const authParams = {
        headers: authHeaders,
        tags: { name: 'AuthenticatedRequests' },
    };

    sleep(1);

    // 2. Get current user
    let userRes = http.get(`${BASE_URL}/auth/user`, authParams);
    const getUserSuccess = check(userRes, {
        'get user status is 200': (r) => r.status === 200,
        'get user returns data': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.user !== undefined;
        },
    });
    errorRate.add(!getUserSuccess);
    sleep(1);

    // 3. Refresh token
    let refreshRes = http.post(`${BASE_URL}/auth/refresh`, null, authParams);
    const refreshSuccess = check(refreshRes, {
        'refresh token status is 200': (r) => r.status === 200,
        'refresh token returns new token': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.token !== undefined;
        },
    });
    errorRate.add(!refreshSuccess);
    sleep(1);

    // 4. Use token for authenticated request (get profile)
    let profileRes = http.get(`${BASE_URL}/profile`, authParams);
    const profileSuccess = check(profileRes, {
        'profile status is 200': (r) => r.status === 200,
        'profile returns data': (r) => {
            const body = JSON.parse(r.body);
            return body.success === true && body.data !== undefined;
        },
    });
    errorRate.add(!profileSuccess);
    sleep(1);

    // 5. Test rate limiting by making rapid requests (only for 10% of users)
    if (Math.random() < 0.1) {
        for (let i = 0; i < 5; i++) {
            const rapidRes = http.get(`${BASE_URL}/profile`, authParams);
            // Rate limit should kick in around 60 requests/minute
            // This test verifies the system handles rapid requests
            sleep(0.1); // Very short sleep to test rate limiting
        }
    }

    // 6. Logout (only for 30% of users, simulating session end)
    if (Math.random() < 0.3) {
        const logoutRes = http.post(`${BASE_URL}/auth/logout`, null, authParams);
        check(logoutRes, {
            'logout status is 200': (r) => r.status === 200,
        });
        sleep(1);
    }
}

export function handleSummary(data) {
    return {
        'stdout': JSON.stringify(data, null, 2),
        'tests/load/results/authentication-summary.json': JSON.stringify(data, null, 2),
    };
}

