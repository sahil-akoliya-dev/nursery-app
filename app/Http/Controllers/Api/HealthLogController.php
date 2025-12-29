<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthLog;
use App\Models\PlantCareReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthLogController extends Controller
{
    public function index($reminderId)
    {
        $reminder = PlantCareReminder::where('user_id', Auth::id())
            ->findOrFail($reminderId);

        $logs = $reminder->healthLogs()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    public function store(Request $request, $reminderId)
    {
        $reminder = PlantCareReminder::where('user_id', Auth::id())
            ->findOrFail($reminderId);

        $request->validate([
            'status' => 'required|in:healthy,needs_attention,sick',
            'notes' => 'nullable|string',
            'photo_url' => 'nullable|url'
        ]);

        $log = $reminder->healthLogs()->create($request->all());

        return response()->json([
            'success' => true,
            'data' => $log,
            'message' => 'Health log added successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $log = HealthLog::whereHas('reminder', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Health log deleted successfully'
        ]);
    }
}
