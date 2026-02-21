<?php
namespace App\Traits;

trait ApiResponse {
    protected function success($data, $message = null, $code = 200) {
        return response()->json([
            'status'  => 'Success',
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    protected function data($data, $message = null, $code = 200) {
        return response()->json([
            'status'  => 'Success',
            'data'    => $data
        ], $code);
    }

    protected function error($message = "Internal Server Error", $code = 500, $errors = null) {
        return response()->json([
            'status'  => 'Error',
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}
