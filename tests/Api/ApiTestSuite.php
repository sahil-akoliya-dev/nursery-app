<?php

namespace Tests\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Plant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ApiTestSuite extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl = '/api';
    protected $adminToken;
    protected $customerToken;
    protected $adminUser;
    protected $customerUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test admin user
        $this->adminUser = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        $this->adminUser->assignRole('admin');
        $this->adminToken = $this->adminUser->createToken('test-token')->plainTextToken;

        // Create test customer user
        $this->customerUser = User::factory()->create([
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
        $this->customerUser->assignRole('customer');
        $this->customerToken = $this->customerUser->createToken('test-token')->plainTextToken;

        // Create test data
        Category::factory()->count(5)->create();
        Product::factory()->count(10)->create();
        Plant::factory()->count(10)->create();
    }

    /**
     * Test Authentication Endpoints
     */
    public function test_authentication_endpoints()
    {
        $tests = [
            // Registration
            [
                'name' => 'POST /auth/register - Valid Registration',
                'method' => 'POST',
                'endpoint' => '/auth/register',
                'data' => [
                    'name' => 'New User',
                    'email' => 'newuser@test.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                ],
                'expected_status' => 201,
                'assertions' => [
                    'response.success' => true,
                    'response.token' => 'exists',
                    'response.user.id' => 'exists',
                ],
            ],

            // Login
            [
                'name' => 'POST /auth/login - Valid Login',
                'method' => 'POST',
                'endpoint' => '/auth/login',
                'data' => [
                    'email' => 'customer@test.com',
                    'password' => 'password123',
                ],
                'expected_status' => 200,
                'requires_auth' => false,
                'assertions' => [
                    'response.success' => true,
                    'response.token' => 'exists',
                    'response.user.id' => 'exists',
                ],
            ],

            [
                'name' => 'POST /auth/login - Invalid Credentials',
                'method' => 'POST',
                'endpoint' => '/auth/login',
                'data' => [
                    'email' => 'wrong@test.com',
                    'password' => 'wrongpassword',
                ],
                'expected_status' => 401,
                'requires_auth' => false,
            ],

            // Get User
            [
                'name' => 'GET /auth/user - Authenticated',
                'method' => 'GET',
                'endpoint' => '/auth/user',
                'expected_status' => 200,
                'requires_auth' => true,
                'assertions' => [
                    'response.success' => true,
                    'response.user.id' => 'exists',
                ],
            ],

            [
                'name' => 'GET /auth/user - Unauthenticated',
                'method' => 'GET',
                'endpoint' => '/auth/user',
                'expected_status' => 401,
                'requires_auth' => false,
            ],

            // Logout
            [
                'name' => 'POST /auth/logout - Authenticated',
                'method' => 'POST',
                'endpoint' => '/auth/logout',
                'expected_status' => 200,
                'requires_auth' => true,
            ],

            // Refresh Token
            [
                'name' => 'POST /auth/refresh - Authenticated',
                'method' => 'POST',
                'endpoint' => '/auth/refresh',
                'expected_status' => 200,
                'requires_auth' => true,
                'assertions' => [
                    'response.success' => true,
                    'response.token' => 'exists',
                ],
            ],
        ];

        $this->runTestBatch($tests, 'Authentication');
    }

    /**
     * Test Product Endpoints
     */
    public function test_product_endpoints()
    {
        $tests = [
            [
                'name' => 'GET /products - List Products',
                'method' => 'GET',
                'endpoint' => '/products',
                'expected_status' => 200,
                'requires_auth' => false,
                'assertions' => [
                    'response.success' => true,
                ],
            ],

            [
                'name' => 'GET /products/{id} - Get Product',
                'method' => 'GET',
                'endpoint' => '/products/1',
                'expected_status' => 200,
                'requires_auth' => false,
                'assertions' => [
                    'response.success' => true,
                ],
            ],

            [
                'name' => 'GET /products/{id} - Product Not Found',
                'method' => 'GET',
                'endpoint' => '/products/99999',
                'expected_status' => 404,
                'requires_auth' => false,
            ],
        ];

        $this->runTestBatch($tests, 'Products');
    }

    /**
     * Test Cart Endpoints
     */
    public function test_cart_endpoints()
    {
        $product = Product::first();

        $tests = [
            [
                'name' => 'GET /cart - Get Cart (Empty)',
                'method' => 'GET',
                'endpoint' => '/cart',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],

            [
                'name' => 'POST /cart/add - Add Item to Cart',
                'method' => 'POST',
                'endpoint' => '/cart/add',
                'data' => [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'quantity' => 2,
                ],
                'expected_status' => 201,
                'requires_auth' => true,
                'use_customer_token' => true,
                'assertions' => [
                    'response.success' => true,
                ],
            ],

            [
                'name' => 'GET /cart - Get Cart (With Items)',
                'method' => 'GET',
                'endpoint' => '/cart',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],

            [
                'name' => 'GET /cart/count - Get Cart Count',
                'method' => 'GET',
                'endpoint' => '/cart/count',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],
        ];

        $this->runTestBatch($tests, 'Cart');
    }

    /**
     * Test Order Endpoints
     */
    public function test_order_endpoints()
    {
        $customer = $this->customerUser;
        
        // Create an address for the customer
        $address = $customer->addresses()->create([
            'type' => 'shipping',
            'first_name' => 'Test',
            'last_name' => 'User',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'state' => 'TS',
            'postal_code' => '12345',
            'country' => 'US',
            'is_default' => true,
        ]);

        // Add item to cart first
        $product = Product::first();
        $customer->cartItems()->create([
            'item_type' => Product::class,
            'item_id' => $product->id,
            'quantity' => 1,
        ]);

        $tests = [
            [
                'name' => 'POST /orders - Create Order',
                'method' => 'POST',
                'endpoint' => '/orders',
                'data' => [
                    'shipping_address_id' => $address->id,
                    'billing_address_id' => $address->id,
                ],
                'expected_status' => 201,
                'requires_auth' => true,
                'use_customer_token' => true,
                'assertions' => [
                    'response.success' => true,
                ],
            ],

            [
                'name' => 'GET /orders - List Orders',
                'method' => 'GET',
                'endpoint' => '/orders',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],
        ];

        $this->runTestBatch($tests, 'Orders');
    }

    /**
     * Test Profile Endpoints
     */
    public function test_profile_endpoints()
    {
        $tests = [
            [
                'name' => 'GET /profile - Get Profile',
                'method' => 'GET',
                'endpoint' => '/profile',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
                'assertions' => [
                    'response.success' => true,
                ],
            ],

            [
                'name' => 'PUT /profile - Update Profile',
                'method' => 'PUT',
                'endpoint' => '/profile',
                'data' => [
                    'name' => 'Updated Name',
                    'phone' => '+1234567890',
                ],
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],
        ];

        $this->runTestBatch($tests, 'Profile');
    }

    /**
     * Test Wishlist Endpoints
     */
    public function test_wishlist_endpoints()
    {
        $product = Product::first();

        $tests = [
            [
                'name' => 'POST /wishlist/add - Add to Wishlist',
                'method' => 'POST',
                'endpoint' => '/wishlist/add',
                'data' => [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                ],
                'expected_status' => 201,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],

            [
                'name' => 'GET /wishlist - Get Wishlist',
                'method' => 'GET',
                'endpoint' => '/wishlist',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],
        ];

        $this->runTestBatch($tests, 'Wishlist');
    }

    /**
     * Test Loyalty Program Endpoints
     */
    public function test_loyalty_endpoints()
    {
        $tests = [
            [
                'name' => 'GET /loyalty - Get Loyalty Points',
                'method' => 'GET',
                'endpoint' => '/loyalty',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],

            [
                'name' => 'GET /loyalty/history - Get Points History',
                'method' => 'GET',
                'endpoint' => '/loyalty/history',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_customer_token' => true,
            ],
        ];

        $this->runTestBatch($tests, 'Loyalty');
    }

    /**
     * Test Admin Endpoints
     */
    public function test_admin_endpoints()
    {
        $tests = [
            [
                'name' => 'GET /admin/analytics/dashboard - Get Dashboard',
                'method' => 'GET',
                'endpoint' => '/admin/analytics/dashboard',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_admin_token' => true,
            ],

            [
                'name' => 'GET /admin/products - List Products',
                'method' => 'GET',
                'endpoint' => '/admin/products',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_admin_token' => true,
            ],

            [
                'name' => 'GET /admin/users - List Users',
                'method' => 'GET',
                'endpoint' => '/admin/users',
                'expected_status' => 200,
                'requires_auth' => true,
                'use_admin_token' => true,
            ],
        ];

        $this->runTestBatch($tests, 'Admin');
    }

    /**
     * Run a batch of tests
     */
    protected function runTestBatch(array $tests, string $category)
    {
        $results = [];
        
        foreach ($tests as $test) {
            $token = null;
            if ($test['requires_auth'] ?? true) {
                if ($test['use_admin_token'] ?? false) {
                    $token = $this->adminToken;
                } elseif ($test['use_customer_token'] ?? false) {
                    $token = $this->customerToken;
                } else {
                    $token = $this->customerToken;
                }
            }

            $response = $this->makeRequest(
                $test['method'],
                $test['endpoint'],
                $test['data'] ?? [],
                $token
            );

            $statusCode = $response->getStatusCode();
            $expectedStatus = $test['expected_status'];
            
            $results[] = [
                'name' => $test['name'],
                'passed' => $statusCode === $expectedStatus,
                'expected_status' => $expectedStatus,
                'actual_status' => $statusCode,
                'response' => json_decode($response->getContent(), true),
            ];
        }

        return $results;
    }

    /**
     * Make an HTTP request
     */
    protected function makeRequest(string $method, string $endpoint, array $data = [], ?string $token = null)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $url = $this->baseUrl . $endpoint;

        return match ($method) {
            'GET' => $this->getJson($url, $headers),
            'POST' => $this->postJson($url, $data, $headers),
            'PUT' => $this->putJson($url, $data, $headers),
            'PATCH' => $this->patchJson($url, $data, $headers),
            'DELETE' => $this->deleteJson($url, $headers),
            default => throw new \InvalidArgumentException("Unsupported method: {$method}"),
        };
    }
}

