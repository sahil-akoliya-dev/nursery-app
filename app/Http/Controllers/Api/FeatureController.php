<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of active features.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $features = \App\Models\Feature::active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $features,
        ]);
    }
}
