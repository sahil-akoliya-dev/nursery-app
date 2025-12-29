<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\PlantCareReminder;
use App\Models\Plant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlantCareReminderController extends Controller
{
    use ApiResponse;
    /**
     * Get user's plant care reminders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = PlantCareReminder::where('user_id', $user->id)
            ->with(['plant.category', 'plantCareGuide']);

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'upcoming':
                    $query->where('scheduled_date', '>', now())
                        ->where('is_completed', false);
                    break;
                case 'due_soon':
                    $query->whereBetween('scheduled_date', [now(), now()->addDays(3)])
                        ->where('is_completed', false);
                    break;
                case 'overdue':
                    $query->where('scheduled_date', '<', now())
                        ->where('is_completed', false);
                    break;
                case 'completed':
                    $query->where('is_completed', true);
                    break;
            }
        }

        // Filter by reminder type
        if ($request->filled('reminder_type')) {
            $query->where('reminder_type', $request->reminder_type);
        }

        // Filter by plant_id
        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        // Filter active/inactive
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        } else {
            $query->where('is_active', true);
        }

        // Sort
        $sortBy = $request->get('sort', 'scheduled_date');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 20), 50);
        $reminders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => collect($reminders->items())->map(function ($reminder) {
                return $this->formatReminder($reminder);
            }),
            'meta' => [
                'current_page' => $reminders->currentPage(),
                'last_page' => $reminders->lastPage(),
                'per_page' => $reminders->perPage(),
                'total' => $reminders->total(),
            ],
        ]);
    }

    /**
     * Get upcoming reminders (next 14 days)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upcoming(Request $request): JsonResponse
    {
        $user = $request->user();
        $days = min($request->get('days', 14), 30);

        $reminders = PlantCareReminder::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_completed', false)
            ->whereBetween('scheduled_date', [now(), now()->addDays($days)])
            ->with(['plant.category', 'plantCareGuide'])
            ->orderBy('scheduled_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'reminders' => $reminders->map(function ($reminder) {
                    return $this->formatReminder($reminder);
                }),
                'count' => $reminders->count(),
                'days_ahead' => $days,
            ],
        ]);
    }

    /**
     * Get overdue reminders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function overdue(Request $request): JsonResponse
    {
        $user = $request->user();

        $reminders = PlantCareReminder::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_completed', false)
            ->where('scheduled_date', '<', now())
            ->with(['plant.category', 'plantCareGuide'])
            ->orderBy('scheduled_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'reminders' => $reminders->map(function ($reminder) {
                    return $this->formatReminder($reminder);
                }),
                'count' => $reminders->count(),
            ],
        ]);
    }

    /**
     * Get reminders calendar view
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calendar(Request $request): JsonResponse
    {
        $user = $request->user();
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $reminders = PlantCareReminder::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_completed', false)
            ->whereBetween('scheduled_date', [$startDate, $endDate])
            ->with(['plant.category', 'plantCareGuide'])
            ->orderBy('scheduled_date', 'asc')
            ->get()
            ->groupBy(function ($reminder) {
                return Carbon::parse($reminder->scheduled_date)->format('Y-m-d');
            });

        $calendar = [];
        foreach ($reminders as $date => $dateReminders) {
            $calendar[$date] = $dateReminders->map(function ($reminder) {
                return $this->formatReminder($reminder);
            })->toArray();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'calendar' => $calendar,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_reminders' => $reminders->flatten()->count(),
            ],
        ]);
    }

    /**
     * Get single reminder
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $reminder = PlantCareReminder::where('user_id', $user->id)
            ->with(['plant.category', 'plantCareGuide'])
            ->find($id);

        if (!$reminder) {
            return $this->notFoundResponse('Reminder', 'The plant care reminder you are looking for was not found.');
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatReminder($reminder, true),
        ]);
    }

    /**
     * Create plant care reminder
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'plant_id' => 'required|integer|exists:plants,id',
            'reminder_type' => 'required|string|in:watering,fertilizing,repotting,pruning,general',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'frequency' => 'required|string|in:daily,weekly,monthly,seasonal,custom,one_time',
            'frequency_value' => 'nullable|integer|min:1|required_if:frequency,custom',
            'plant_care_guide_id' => 'nullable|integer|exists:plant_care_guides,id',
        ]);

        $user = $request->user();

        // Verify plant exists and is active
        $plant = Plant::where('is_active', true)->find($request->plant_id);
        if (!$plant) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PLANT_NOT_FOUND',
                    'message' => 'Plant not found or inactive.',
                ]
            ], 404);
        }

        $reminder = PlantCareReminder::create([
            'user_id' => $user->id,
            'plant_id' => $request->plant_id,
            'plant_care_guide_id' => $request->plant_care_guide_id,
            'reminder_type' => $request->reminder_type,
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_date' => $request->scheduled_date,
            'frequency' => $request->frequency,
            'frequency_value' => $request->frequency_value,
            'is_active' => true,
            'is_completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plant care reminder created successfully.',
            'data' => $this->formatReminder($reminder->fresh(['plant', 'plantCareGuide']), true),
        ], 201);
    }

    /**
     * Update reminder
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'scheduled_date' => 'sometimes|required|date|after_or_equal:today',
            'frequency' => 'sometimes|required|string|in:daily,weekly,monthly,seasonal,custom,one_time',
            'frequency_value' => 'nullable|integer|min:1|required_if:frequency,custom',
            'is_active' => 'sometimes|boolean',
        ]);

        $reminder = PlantCareReminder::where('user_id', $user->id)->find($id);

        if (!$reminder) {
            return $this->notFoundResponse('Reminder', 'The plant care reminder you are looking for was not found.');
        }

        $reminder->update($request->only([
            'title',
            'description',
            'scheduled_date',
            'frequency',
            'frequency_value',
            'is_active',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Reminder updated successfully.',
            'data' => $this->formatReminder($reminder->fresh(['plant', 'plantCareGuide']), true),
        ]);
    }

    /**
     * Mark reminder as completed
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $reminder = PlantCareReminder::where('user_id', $user->id)
            ->where('is_completed', false)
            ->find($id);

        if (!$reminder) {
            return $this->notFoundResponse('Reminder', 'The plant care reminder you are trying to complete was not found or has already been completed.');
        }

        $reminder->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Reminder marked as completed.',
            'data' => $this->formatReminder($reminder->fresh(['plant', 'plantCareGuide']), true),
        ]);
    }

    /**
     * Delete reminder
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $reminder = PlantCareReminder::where('user_id', $user->id)->find($id);

        if (!$reminder) {
            return $this->notFoundResponse('Reminder', 'The plant care reminder you are looking for was not found.');
        }

        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully.',
        ]);
    }

    /**
     * Format reminder for API response
     *
     * @param PlantCareReminder $reminder
     * @param bool $detailed
     * @return array
     */
    private function formatReminder(PlantCareReminder $reminder, bool $detailed = false): array
    {
        $data = [
            'id' => $reminder->id,
            'reminder_type' => $reminder->reminder_type,
            'title' => $reminder->title,
            'description' => $reminder->description,
            'scheduled_date' => $reminder->scheduled_date->toIso8601String(),
            'scheduled_date_formatted' => $reminder->scheduled_date->format('Y-m-d'),
            'frequency' => $reminder->frequency,
            'frequency_value' => $reminder->frequency_value,
            'is_completed' => $reminder->is_completed,
            'completed_at' => $reminder->completed_at?->toIso8601String(),
            'is_active' => $reminder->is_active,
            'status' => $reminder->status,
            'days_until' => $reminder->scheduled_date->diffInDays(now(), false),
            'created_at' => $reminder->created_at->toIso8601String(),
        ];

        if ($reminder->plant) {
            $data['plant'] = [
                'id' => $reminder->plant->id,
                'name' => $reminder->plant->name,
                'scientific_name' => $reminder->plant->scientific_name,
                'slug' => $reminder->plant->slug,
                'plant_type' => $reminder->plant->plant_type,
                'image' => $reminder->plant->images[0] ?? null,
                'category' => $reminder->plant->category ? [
                    'id' => $reminder->plant->category->id,
                    'name' => $reminder->plant->category->name,
                    'slug' => $reminder->plant->category->slug,
                ] : null,
            ];
        }

        if ($detailed && $reminder->plantCareGuide) {
            $data['care_guide'] = [
                'id' => $reminder->plantCareGuide->id,
                'title' => $reminder->plantCareGuide->title,
                'care_level' => $reminder->plantCareGuide->care_level,
            ];
        }

        return $data;
    }
}

