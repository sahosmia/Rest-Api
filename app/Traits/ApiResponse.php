<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{

    public function successResponse($data = [], $message = "Success", $code = 200)
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data
            ],
            $code
        );
    }


    public function errorResponse($message = "Something went wrong", $code = 500)
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
            ],
            $code
        );
    }
}
