# Load Testing with k6

This directory contains k6 load testing scripts for the Nursery App API.

## Prerequisites

Install k6:

```bash
# macOS
brew install k6

# Linux (Ubuntu/Debian)
sudo apt-get install k6

# Or download from https://k6.io/docs/getting-started/installation/
```

## Test Scripts

### 1. Product Browsing (`product-browsing.js`)

Tests the product browsing flow:
- List products with filters
- View product details
- Search products
- Filter by category

**Run:**
```bash
k6 run tests/load/product-browsing.js
```

**Configuration:**
- Ramp up to 100 concurrent users over 2 minutes
- Sustain 100 users for 5 minutes
- Ramp down over 2 minutes

### 2. Cart & Checkout (`cart-checkout.js`)

Tests the shopping cart and checkout flow:
- Login
- Add to cart
- Update cart
- Get cart
- Checkout (simulated)

**Run:**
```bash
k6 run tests/load/cart-checkout.js
```

**Configuration:**
- Ramp up to 50 concurrent users over 2 minutes
- Sustain 50 users for 5 minutes
- Ramp down over 2 minutes

**Note:** Requires test users with valid credentials. Update the `users` SharedArray in the script.

### 3. Authentication (`authentication.js`)

Tests authentication flows:
- Login
- Get current user
- Refresh token
- Use authenticated endpoints
- Logout

**Run:**
```bash
k6 run tests/load/authentication.js
```

**Configuration:**
- Ramp up to 100 concurrent users over 2 minutes
- Sustain 100 users for 5 minutes
- Ramp down over 2 minutes

### 4. Spike Test (`spike-test.js`)

Tests system behavior under sudden traffic spikes:
- Normal load: 100 users
- Spike: 500 users (5x increase)
- Recovery: Back to 100 users

**Run:**
```bash
k6 run tests/load/spike-test.js
```

## Running All Tests

```bash
# Create results directory
mkdir -p tests/load/results

# Run all tests sequentially
k6 run tests/load/product-browsing.js
k6 run tests/load/cart-checkout.js
k6 run tests/load/authentication.js
k6 run tests/load/spike-test.js
```

## Custom Base URL

Set a custom base URL using environment variable:

```bash
BASE_URL=http://your-api-url.com/api k6 run tests/load/product-browsing.js
```

## Performance Thresholds

All tests have performance thresholds:

- **Response Time:** 95th percentile < 500ms (1000ms for spike test)
- **Error Rate:** < 1% (5% for spike test)
- **99th Percentile:** < 1000ms (2000ms for spike test)

## Test Results

Results are saved to:
- `tests/load/results/product-browsing-summary.json`
- `tests/load/results/cart-checkout-summary.json`
- `tests/load/results/authentication-summary.json`
- `tests/load/results/spike-test-summary.json`

## Interpreting Results

Key metrics to watch:

1. **http_req_duration**: Request duration (p50, p95, p99)
2. **http_req_failed**: Failed request rate
3. **http_reqs**: Total requests per second
4. **vus**: Virtual users (concurrent users)
5. **errors**: Custom error rate

## Troubleshooting

### High Error Rates
- Check if API server is running
- Verify database connection
- Check rate limiting configuration
- Ensure test data exists in database

### Slow Response Times
- Check database query performance
- Verify caching is enabled
- Check server resources (CPU, memory)
- Review slow query logs

### Rate Limiting Errors (429)
- Adjust rate limiting thresholds
- Increase rate limit configuration
- Reduce concurrent users in test

## Continuous Integration

You can integrate k6 tests into CI/CD:

```yaml
# Example GitHub Actions
- name: Run Load Tests
  run: |
    k6 run tests/load/product-browsing.js
    k6 run tests/load/authentication.js
```

## Best Practices

1. **Start Small**: Begin with low user counts and gradually increase
2. **Monitor Resources**: Watch CPU, memory, and database during tests
3. **Test Realistic Scenarios**: Simulate real user behavior
4. **Use Shared Arrays**: For test data to reduce memory usage
5. **Set Realistic Thresholds**: Based on actual requirements
6. **Run During Off-Peak**: Avoid impacting production users

## Additional Resources

- [k6 Documentation](https://k6.io/docs/)
- [k6 Examples](https://github.com/grafana/k6/tree/master/samples)
- [Load Testing Best Practices](https://k6.io/docs/test-types/load-testing/)

