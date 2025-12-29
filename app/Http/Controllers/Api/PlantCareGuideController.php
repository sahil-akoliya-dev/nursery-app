<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\PlantCareGuide;
use App\Models\Plant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlantCareGuideController extends Controller
{
    use ApiResponse;
    /**
     * Get list of plant care guides
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = PlantCareGuide::where('is_active', true)
            ->with('plant.category');

        // Filter by plant_id
        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        // Filter by plant_type
        if ($request->filled('plant_type')) {
            $query->where(function ($q) use ($request) {
                $q->where('plant_type', $request->plant_type)
                  ->orWhereHas('plant', function ($pq) use ($request) {
                      $pq->where('plant_type', $request->plant_type);
                  });
            });
        }

        // Filter by care_level
        if ($request->filled('care_level')) {
            $query->where('care_level', $request->care_level);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 20), 50);
        $guides = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => collect($guides->items())->map(function ($guide) {
                return $this->formatGuide($guide);
            }),
            'meta' => [
                'current_page' => $guides->currentPage(),
                'last_page' => $guides->lastPage(),
                'per_page' => $guides->perPage(),
                'total' => $guides->total(),
            ],
        ]);
    }

    /**
     * Get single plant care guide
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $guide = PlantCareGuide::where('is_active', true)
            ->with(['plant.category'])
            ->find($id);

        if (!$guide) {
            return $this->notFoundResponse('Plant Care Guide', 'The plant care guide you are looking for was not found or is no longer available.');
        }

        // Get related guides
        $relatedGuides = PlantCareGuide::where('is_active', true)
            ->where('care_level', $guide->care_level)
            ->where('id', '!=', $guide->id)
            ->limit(3)
            ->get();

        // Get current season care instructions
        $currentSeason = $this->getCurrentSeason();
        $seasonalCare = $guide->seasonal_care[$currentSeason] ?? null;

        return response()->json([
            'success' => true,
            'data' => array_merge($this->formatGuide($guide, true), [
                'seasonal_care' => $guide->seasonal_care ?? [],
                'current_season' => $currentSeason,
                'current_season_care' => $seasonalCare,
                'common_problems' => $guide->common_problems ?? [],
                'temperature_range' => $guide->temperature_range ?? null,
                'related_guides' => $relatedGuides->map(function ($related) {
                    return $this->formatGuide($related);
                }),
            ]),
        ]);
    }

    /**
     * Get care guides by plant
     *
     * @param Request $request
     * @param int $plantId
     * @return JsonResponse
     */
    public function byPlant(Request $request, int $plantId): JsonResponse
    {
        $plant = Plant::where('is_active', true)->find($plantId);

        if (!$plant) {
            return $this->notFoundResponse('Plant', 'The plant you are looking for was not found or is no longer available.');
        }

        $guides = PlantCareGuide::where('is_active', true)
            ->where(function ($query) use ($plant) {
                $query->where('plant_id', $plant->id)
                      ->orWhere('plant_type', $plant->plant_type);
            })
            ->with('plant.category')
            ->orderBy('care_level')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'plant' => [
                    'id' => $plant->id,
                    'name' => $plant->name,
                    'scientific_name' => $plant->scientific_name,
                    'plant_type' => $plant->plant_type,
                ],
                'guides' => $guides->map(function ($guide) {
                    return $this->formatGuide($guide);
                }),
            ],
        ]);
    }

    /**
     * Get seasonal care tips
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function seasonalCare(Request $request): JsonResponse
    {
        $currentSeason = $request->get('season', $this->getCurrentSeason());

        $guides = PlantCareGuide::where('is_active', true)
            ->with('plant.category')
            ->get();

        $seasonalTips = [];

        foreach ($guides as $guide) {
            if (isset($guide->seasonal_care[$currentSeason])) {
                $seasonalData = $guide->seasonal_care[$currentSeason];
                
                $seasonalTips[] = [
                    'guide_id' => $guide->id,
                    'guide_title' => $guide->title,
                    'plant' => $guide->plant ? [
                        'id' => $guide->plant->id,
                        'name' => $guide->plant->name,
                    ] : null,
                    'care_instructions' => $seasonalData['instructions'] ?? null,
                    'tips' => $seasonalData['tips'] ?? [],
                    'watering' => $seasonalData['watering'] ?? null,
                    'fertilizing' => $seasonalData['fertilizing'] ?? null,
                    'light' => $seasonalData['light'] ?? null,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'season' => $currentSeason,
                'tips' => $seasonalTips,
                'guide_count' => count($seasonalTips),
            ],
        ]);
    }

    /**
     * Format guide for API response
     *
     * @param PlantCareGuide $guide
     * @param bool $detailed
     * @return array
     */
    private function formatGuide(PlantCareGuide $guide, bool $detailed = false): array
    {
        $data = [
            'id' => $guide->id,
            'title' => $guide->title,
            'description' => $guide->description,
            'care_level' => $guide->care_level,
            'light_requirements' => $guide->light_requirements,
            'water_needs' => $guide->water_needs,
            'humidity_requirements' => $guide->humidity_requirements,
            'soil_type' => $guide->soil_type,
            'fertilizer_schedule' => $guide->fertilizer_schedule,
            'repotting_frequency' => $guide->repotting_frequency,
            'pruning_instructions' => $guide->pruning_instructions,
            'created_at' => $guide->created_at->toIso8601String(),
        ];

        if ($guide->plant) {
            $data['plant'] = [
                'id' => $guide->plant->id,
                'name' => $guide->plant->name,
                'scientific_name' => $guide->plant->scientific_name,
                'slug' => $guide->plant->slug,
                'plant_type' => $guide->plant->plant_type,
                'category' => $guide->plant->category ? [
                    'id' => $guide->plant->category->id,
                    'name' => $guide->plant->category->name,
                    'slug' => $guide->plant->category->slug,
                ] : null,
            ];
        } else {
            $data['plant_type'] = $guide->plant_type;
        }

        if ($detailed) {
            $data['temperature_range'] = $guide->temperature_range;
            $data['common_problems'] = $guide->common_problems;
        }

        return $data;
    }

    /**
     * Get current season
     *
     * @return string
     */
    private function getCurrentSeason(): string
    {
        $month = Carbon::now()->month;
        
        return match(true) {
            in_array($month, [12, 1, 2]) => 'winter',
            in_array($month, [3, 4, 5]) => 'spring',
            in_array($month, [6, 7, 8]) => 'summer',
            in_array($month, [9, 10, 11]) => 'fall',
            default => 'spring'
        };
    }
}

