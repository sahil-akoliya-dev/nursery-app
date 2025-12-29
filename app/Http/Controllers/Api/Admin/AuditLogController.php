<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use ApiResponse;
    /**
     * List audit logs with filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = AuditLog::with('user:id,name,email')
            ->latest();

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $modelType = $request->model_type;
            // Allow short names like "Product" or full class names
            if (!str_contains($modelType, '\\')) {
                $modelType = 'App\\Models\\' . $modelType;
            }
            $query->where('model_type', $modelType);
        }

        // Filter by model ID
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('model_type', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = min($request->get('per_page', 50), 100);
        $auditLogs = $query->paginate($perPage);

        // Get filter options for UI
        $filterOptions = [
            'actions' => AuditLog::distinct()->pluck('action')->sort()->values(),
            'model_types' => AuditLog::distinct()
                ->whereNotNull('model_type')
                ->pluck('model_type')
                ->map(function ($type) {
                    $parts = explode('\\', $type);
                    return [
                        'full' => $type,
                        'short' => end($parts),
                    ];
                })
                ->unique('short')
                ->sortBy('short')
                ->values(),
        ];

        return response()->json([
            'success' => true,
            'data' => $auditLogs->items()->map(function ($log) {
                return $this->formatAuditLog($log);
            }),
            'filter_options' => $filterOptions,
            'meta' => [
                'current_page' => $auditLogs->currentPage(),
                'last_page' => $auditLogs->lastPage(),
                'per_page' => $auditLogs->perPage(),
                'total' => $auditLogs->total(),
            ],
        ]);
    }

    /**
     * Get single audit log
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $auditLog = AuditLog::with('user:id,name,email')->find($id);

        if (!$auditLog) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'AUDIT_LOG_NOT_FOUND',
                    'message' => 'Audit log not found.',
                ]
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatAuditLog($auditLog, true), // Include full details
        ]);
    }

    /**
     * Get audit logs for a specific model
     *
     * @param Request $request
     * @param string $modelType
     * @param int $modelId
     * @return JsonResponse
     */
    public function forModel(Request $request, string $modelType, int $modelId): JsonResponse
    {
        // Normalize model type
        if (!str_contains($modelType, '\\')) {
            $modelType = 'App\\Models\\' . $modelType;
        }

        $query = AuditLog::with('user:id,name,email')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->latest();

        $perPage = min($request->get('per_page', 20), 50);
        $auditLogs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $auditLogs->items()->map(function ($log) {
                return $this->formatAuditLog($log);
            }),
            'meta' => [
                'current_page' => $auditLogs->currentPage(),
                'last_page' => $auditLogs->lastPage(),
                'per_page' => $auditLogs->perPage(),
                'total' => $auditLogs->total(),
            ],
        ]);
    }

    /**
     * Get audit log statistics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->startOfDay());
        $dateTo = $request->get('date_to', now()->endOfDay());

        $query = AuditLog::whereBetween('created_at', [$dateFrom, $dateTo]);

        // Actions breakdown
        $actionsBreakdown = $query->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->action => $item->count];
            });

        // Model types breakdown
        $modelTypesBreakdown = AuditLog::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('model_type')
            ->selectRaw('model_type, COUNT(*) as count')
            ->groupBy('model_type')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) {
                $parts = explode('\\', $item->model_type);
                return [
                    'model_type' => $item->model_type,
                    'short_name' => end($parts),
                    'count' => $item->count,
                ];
            });

        // Top users by activity
        $topUsers = AuditLog::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('user_id')
            ->with('user:id,name,email')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'user' => $item->user ? [
                        'id' => $item->user->id,
                        'name' => $item->user->name,
                        'email' => $item->user->email,
                    ] : null,
                    'count' => $item->count,
                ];
            });

        // Activity over time (daily)
        $activityOverTime = AuditLog::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'from' => $dateFrom,
                    'to' => $dateTo,
                ],
                'total_logs' => AuditLog::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'actions_breakdown' => $actionsBreakdown,
                'model_types_breakdown' => $modelTypesBreakdown,
                'top_users' => $topUsers,
                'activity_over_time' => $activityOverTime,
            ],
        ]);
    }

    /**
     * Format audit log for API response
     *
     * @param AuditLog $log
     * @param bool $includeDetails
     * @return array
     */
    private function formatAuditLog(AuditLog $log, bool $includeDetails = false): array
    {
        $data = [
            'id' => $log->id,
            'action' => $log->action,
            'formatted_action' => $log->formatted_action,
            'model_type' => $log->model_type,
            'model_name' => $log->model_name,
            'model_id' => $log->model_id,
            'user' => $log->user ? [
                'id' => $log->user->id,
                'name' => $log->user->name,
                'email' => $log->user->email,
            ] : null,
            'ip_address' => $log->ip_address,
            'created_at' => $log->created_at->toIso8601String(),
            'time_ago' => $log->created_at->diffForHumans(),
        ];

        if ($includeDetails) {
            $data['old_values'] = $log->old_values;
            $data['new_values'] = $log->new_values;
            $data['user_agent'] = $log->user_agent;
            $data['url'] = $log->url;
            $data['metadata'] = $log->metadata;
        } else {
            // Show summary of changes
            if ($log->old_values || $log->new_values) {
                $data['has_changes'] = true;
                $data['changed_fields'] = $log->new_values ? array_keys($log->new_values) : [];
            }
        }

        return $data;
    }
}

