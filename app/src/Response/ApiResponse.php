<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct(string $message, string $title, $data = null, array $errors = [], int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($this->format($message, $title, $data, $errors), $status, $headers, $json);
    }

    private function format(string $message, string $title, $data = null, array $errors = [])
    {
        if ($data === null) {
            $data = new \ArrayObject();
        }

        $response = [
            'message' => $message,
            'title' => $title,
            'data'    => $data,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}