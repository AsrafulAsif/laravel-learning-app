<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function successResponse($data = null, string $message = 'Operation successful', int $statusCode = 200): JsonResponse
    {

        $response = [
            'status_code' => $statusCode,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ];
        if ($data != null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
    protected function errorResponse(string $message = 'Operation failed', int $statusCode = 400, ?array $errors = null): JsonResponse
    {
        $response = [
            'status_code' => $statusCode,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
