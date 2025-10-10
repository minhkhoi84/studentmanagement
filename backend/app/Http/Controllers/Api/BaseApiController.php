<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseApiController extends Controller
{
    /**
     * Return success response
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => true
        ], $statusCode);
    }

    /**
     * Return error response
     */
    protected function errorResponse(string $message = 'Error', int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'message' => $message,
            'success' => false
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return paginated response
     */
    protected function paginatedResponse($data, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
            'message' => $message,
            'success' => true
        ]);
    }

    /**
     * Handle search query
     */
    protected function handleSearch($query, Request $request, array $searchFields = []): void
    {
        if ($request->filled('search') && !empty($searchFields)) {
            $searchTerm = $request->string('search');
            $query->where(function ($q) use ($searchTerm, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', '%' . $searchTerm . '%');
                }
            });
        }
    }

    /**
     * Handle pagination
     */
    protected function handlePagination($query, Request $request, int $defaultPerPage = 15)
    {
        return $query->paginate($request->integer('per_page', $defaultPerPage));
    }
}



