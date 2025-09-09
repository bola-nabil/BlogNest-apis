<?php

namespace App\Traits;

trait ApiResponse {
    public function success($message = "Success", $key, $data = [], $code = 200)
    {
        return response()->json([
            "success" => true,
            "message" => $message,
            $key => $data
        ], $code);
    }

    public function error($message = "Error", $code = 400, $errors = [])
    {
        return response()->json([
            "success" => false,
            "message" => $message,
            "errors" => $errors
        ], $code);
    }

    public function notFound($message)
    {
        return response()->json(["message" => $message], 404);
    }

    public function removeData($message)
    {
        return response()->json(["message" => $message]);
    }
}