<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    use ApiResponse;

    /**
     * Subscribe to newsletter
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // In a real app, you'd save to DB or call Mailchimp API
        // For now, we'll just log it and return success
        Log::info('Newsletter subscription: ' . $request->email);

        return $this->successResponse(null, 'Thank you for subscribing!');
    }
}
