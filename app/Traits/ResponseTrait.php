<?php

namespace App\Traits;

trait ResponseTrait {

    public function resSuccess($message, $data) {
        return $this->serverResponse($message, $data);
    }

    public function resValidation($message, $data) {
        return $this->serverResponse($message, $data, 422);
    }

    public function resInvalid($message, $data) {
        return $this->serverResponse($message, $data, 400);
    }

    public function resUnauthenticated($message, $data) {
        return $this->serverResponse($message, $data, 401);
    }

    public function resUnauthrized($message, $data) {
        return $this->serverResponse($message, $data, 403);
    }

    protected function serverResponse($message, $data, $status = 200) {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }
}