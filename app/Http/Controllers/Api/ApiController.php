<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login', 'register');
    }

    public function sendResponse(
        string $message,
        bool $status,
        array|int $data = null,
        int $http_code = 200,
        int $error_code = 1103
    ): JsonResponse
    {
        if (is_numeric($data)) {
            $error_code = $http_code;
            $http_code = $data;
            $data = null;
        }
        $response = [
            'success' => $status,
            'message' => $message,
        ];
        if (!$status) {
            $response['error_code'] = $error_code;
            $response['errors'] = $data;
        } else $response['data'] = $data;

        return response()->json($response, $http_code);
    }
}
