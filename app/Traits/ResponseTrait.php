<?php

namespace App\Traits;

trait ResponseTrait
{
    public function successResponse($data, $message = 'success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    public function errorResponse($message = 'faild', $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $status);
    }
}
