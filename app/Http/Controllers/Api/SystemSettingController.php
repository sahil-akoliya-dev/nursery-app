<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    use ApiResponse;

    /**
     * Get all settings
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $settings = SystemSetting::all()->groupBy('group');
        return $this->successResponse($settings);
    }

    /**
     * Update settings
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:system_settings,key',
            'settings.*.value' => 'nullable',
        ]);

        foreach ($data['settings'] as $item) {
            $setting = SystemSetting::where('key', $item['key'])->first();
            if ($setting) {
                // Handle type casting for storage if needed
                $value = $item['value'];
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }
                $setting->update(['value' => $value]);
            }
        }

        return $this->successResponse(null, 'Settings updated successfully.');
    }

    /**
     * Helper to perform database backup (mock)
     */
    public function backup(): JsonResponse
    {
        // In a real app, this would trigger `Artisan::call('backup:run')`
        // For now, we simulate it.
        sleep(2); // Simulate delay

        return $this->successResponse(null, 'Database backup created successfully (Mock).');
    }
}
