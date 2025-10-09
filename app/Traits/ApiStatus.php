<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiStatus
{

    public function StatusSuccess($data, $message = "Successful", $code = JsonResponse::HTTP_OK)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code
        ]);
    }

    public function StatusError($errors, $message = "Error", $code = JsonResponse::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'errors' => $errors,
            'message' => $message,
            'code' => $code
        ]);
    }

    public function StatusResource($message = "Successful", $code = JsonResponse::HTTP_OK)
    {
        return [
            'message' => $message,
            'code' => $code
        ];
    }
}
