<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use ApiResponse;

    /**
     * List audit logs
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Fetch activity logs
        $logs = Activity::with('causer')
            ->latest()
            ->paginate(50);

        return $this->successResponse($logs);
    }
}
