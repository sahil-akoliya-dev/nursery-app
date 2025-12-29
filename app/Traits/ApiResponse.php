<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a successful JSON response
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = ['success' => true];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response
     *
     * @param string $code Error code (e.g., 'VALIDATION_ERROR', 'NOT_FOUND')
     * @param string $message Human-readable error message
     * @param int $statusCode HTTP status code
     * @param mixed $details Additional error details (validation errors, etc.)
     * @return JsonResponse
     */
    protected function errorResponse(
        string $code,
        string $message,
        int $statusCode = 400,
        $details = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
            ]
        ];

        if ($details !== null) {
            if (is_array($details) && isset($details[0])) {
                // Validation errors format
                $response['error']['errors'] = $details;
            } else {
                $response['error']['details'] = $details;
            }
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return validation error response
     *
     * @param mixed $errors Validation errors
     * @param string|null $message
     * @return JsonResponse
     */
    protected function validationErrorResponse($errors, ?string $message = null): JsonResponse
    {
        return $this->errorResponse(
            'VALIDATION_ERROR',
            $message ?? 'The given data was invalid. Please check your input and try again.',
            422,
            $errors
        );
    }

    /**
     * Return unauthorized error response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function unauthorizedResponse(?string $message = null): JsonResponse
    {
        return $this->errorResponse(
            'UNAUTHORIZED',
            $message ?? 'Authentication required. Please log in to access this resource.',
            401
        );
    }

    /**
     * Return forbidden error response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function forbiddenResponse(?string $message = null): JsonResponse
    {
        return $this->errorResponse(
            'FORBIDDEN',
            $message ?? 'You do not have permission to perform this action.',
            403
        );
    }

    /**
     * Return not found error response
     *
     * @param string $resource Resource name (e.g., 'Product', 'Order')
     * @param string|null $message
     * @return JsonResponse
     */
    protected function notFoundResponse(string $resource, ?string $message = null): JsonResponse
    {
        return $this->errorResponse(
            'NOT_FOUND',
            $message ?? "{$resource} not found. Please check the ID and try again.",
            404
        );
    }

    /**
     * Return bad request error response
     *
     * @param string $message
     * @param string|null $code
     * @param mixed $details
     * @return JsonResponse
     */
    protected function badRequestResponse(string $message, ?string $code = 'BAD_REQUEST', $details = null): JsonResponse
    {
        return $this->errorResponse($code, $message, 400, $details);
    }

    /**
     * Return conflict error response (e.g., duplicate entry)
     *
     * @param string $message
     * @param string|null $code
     * @return JsonResponse
     */
    protected function conflictResponse(string $message, ?string $code = 'CONFLICT'): JsonResponse
    {
        return $this->errorResponse($code, $message, 409);
    }

    /**
     * Return too many requests error response
     *
     * @param string|null $message
     * @param int|null $retryAfter Seconds to wait before retrying
     * @return JsonResponse
     */
    protected function tooManyRequestsResponse(?string $message = null, ?int $retryAfter = null): JsonResponse
    {
        $response = $this->errorResponse(
            'TOO_MANY_REQUESTS',
            $message ?? 'Too many requests. Please wait a moment and try again.',
            429
        );

        if ($retryAfter) {
            $response->header('Retry-After', (string) $retryAfter);
        }

        return $response;
    }

    /**
     * Return server error response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function serverErrorResponse(?string $message = null): JsonResponse
    {
        return $this->errorResponse(
            'SERVER_ERROR',
            $message ?? 'An unexpected error occurred. Please try again later.',
            500
        );
    }
}

