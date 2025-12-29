<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of active testimonials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $testimonials = \App\Models\Testimonial::active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials,
        ]);
    }
}
