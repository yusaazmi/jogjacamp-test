<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait CustomApiResponseTrait
{
    protected function apiResponse($data = null, $message = 'success', $httpStatus = 200, $total = null, $limit = null, $page = null)
    {
        $response = [
            'data' => $data,
            'message' => $message,
            'meta' => [
                'http_status' => $httpStatus,
            ],
        ];
        
        // Tambahkan total, limit, dan page hanya jika nilainya tidak null
        if ($total !== null) {
            $response['meta']['total'] = $total;
        }
        
        if ($limit !== null) {
            $response['meta']['limit'] = $limit;
        }
        
        if ($page !== null) {
            $response['meta']['page'] = $page;
        }
        
        return response()->json($response, $httpStatus);
    }
}