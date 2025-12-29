<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    use ApiResponse;
    /**
     * Get dashboard analytics overview
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        // Sales analytics
        $sales = $this->getSalesAnalytics($startDate, $endDate);
        
        // Customer analytics
        $customers = $this->getCustomerAnalytics($startDate, $endDate);
        
        // Product analytics
        $products = $this->getProductAnalytics();
        
        // Order status breakdown
        $orderStatus = $this->getOrderStatusBreakdown($startDate, $endDate);
        
        // Revenue trends (last 12 months)
        $revenueTrends = $this->getRevenueTrends();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'sales' => $sales,
                'customers' => $customers,
                'products' => $products,
                'order_status' => $orderStatus,
                'revenue_trends' => $revenueTrends,
            ],
        ]);
    }

    /**
     * Get sales analytics
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getSalesAnalytics(string $startDate, string $endDate): array
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

        // Compare with previous period
        $previousStart = Carbon::parse($startDate)->subMonth()->toDateString();
        $previousEnd = Carbon::parse($endDate)->subMonth()->toDateString();
        
        $previousOrders = Order::whereBetween('created_at', [$previousStart, $previousEnd])
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $previousRevenue = $previousOrders->sum('total_amount');
        $revenueGrowth = $previousRevenue > 0 
            ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 2)
            : 0;

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'revenue_growth' => $revenueGrowth,
            'previous_period_revenue' => round($previousRevenue, 2),
        ];
    }

    /**
     * Get customer analytics
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getCustomerAnalytics(string $startDate, string $endDate): array
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        $activeCustomers = User::where('role', 'customer')
            ->whereHas('orders', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->count();

        // Repeat customers (customers with 2+ orders)
        $repeatCustomers = User::where('role', 'customer')
            ->has('orders', '>=', 2)
            ->count();

        return [
            'total' => $totalCustomers,
            'new_this_period' => $newCustomers,
            'active_this_period' => $activeCustomers,
            'repeat_customers' => $repeatCustomers,
        ];
    }

    /**
     * Get product analytics
     *
     * @return array
     */
    private function getProductAnalytics(): array
    {
        $totalProducts = Product::where('is_active', true)->count();
        $lowStock = Product::where('is_active', true)
            ->where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->count();
        $outOfStock = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 0)
            ->orWhere('in_stock', false)
            ->count();

        // Top selling products (last 30 days)
        $topProducts = Order::where('created_at', '>=', now()->subDays(30))
            ->whereHas('orderItems')
            ->with('orderItems.item')
            ->get()
            ->flatMap(function ($order) {
                return $order->orderItems;
            })
            ->groupBy('item_id')
            ->map(function ($items) {
                return [
                    'item_id' => $items->first()->item_id,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();

        return [
            'total' => $totalProducts,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'top_selling' => $topProducts,
        ];
    }

    /**
     * Get order status breakdown
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getOrderStatusBreakdown(string $startDate, string $endDate): array
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'pending' => $orders->get('pending')?->count ?? 0,
            'processing' => $orders->get('processing')?->count ?? 0,
            'shipped' => $orders->get('shipped')?->count ?? 0,
            'delivered' => $orders->get('delivered')?->count ?? 0,
            'cancelled' => $orders->get('cancelled')?->count ?? 0,
        ];
    }

    /**
     * Get revenue trends (last 12 months)
     *
     * @return array
     */
    private function getRevenueTrends(): array
    {
        $trends = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $monthRevenue = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $monthOrders = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', '!=', 'cancelled')
                ->count();
            
            $trends[] = [
                'month' => $monthStart->format('Y-m'),
                'month_name' => $monthStart->format('M Y'),
                'revenue' => round($monthRevenue, 2),
                'orders' => $monthOrders,
            ];
        }

        return $trends;
    }

    /**
     * Get sales report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function salesReport(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['orderItems.item', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $report = [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_revenue' => round($orders->where('status', '!=', 'cancelled')->sum('total_amount'), 2),
                'total_orders' => $orders->count(),
                'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
                'average_order_value' => round($orders->where('status', '!=', 'cancelled')->avg('total_amount'), 2),
            ],
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'user' => $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ] : null,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at->toIso8601String(),
                    'item_count' => $order->orderItems->count(),
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    /**
     * Get customer report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function customerReport(Request $request): JsonResponse
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount');

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('orders', function ($q) use ($request) {
                $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
            });
        }

        $perPage = min($request->get('per_page', 20), 50);
        $customers = $query->orderBy('orders_sum_total_amount', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers->items()->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'total_orders' => $customer->orders_count,
                    'total_spent' => round($customer->orders_sum_total_amount ?? 0, 2),
                    'average_order_value' => $customer->orders_count > 0
                        ? round(($customer->orders_sum_total_amount ?? 0) / $customer->orders_count, 2)
                        : 0,
                    'loyalty_points' => $customer->current_loyalty_points,
                    'created_at' => $customer->created_at->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
            ],
        ]);
    }

    /**
     * Get inventory report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function inventoryReport(Request $request): JsonResponse
    {
        $query = Product::with('category');

        // Filter by stock level
        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->where('stock_quantity', '<', 10)
                        ->where('stock_quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('stock_quantity', '<=', 0)
                        ->orWhere('in_stock', false);
                    break;
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0)
                        ->where('in_stock', true);
                    break;
            }
        }

        $products = $query->orderBy('stock_quantity', 'asc')
            ->get();

        $report = [
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock' => Product::where('is_active', true)
                ->where('stock_quantity', '<', 10)
                ->where('stock_quantity', '>', 0)
                ->count(),
            'out_of_stock' => Product::where('is_active', true)
                ->where(function ($q) {
                    $q->where('stock_quantity', '<=', 0)
                      ->orWhere('in_stock', false);
                })
                ->count(),
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category ? $product->category->name : null,
                    'stock_quantity' => $product->stock_quantity,
                    'in_stock' => $product->in_stock,
                    'price' => $product->price,
                    'status' => $product->stock_quantity <= 0 ? 'out_of_stock' 
                        : ($product->stock_quantity < 10 ? 'low_stock' : 'in_stock'),
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }
}

