<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\ResponseInterface;

readonly class BaseController
{
    protected function successResponse(array $data, int $status = 200): ResponseInterface
    {
        $data['status'] = 'success';

        $response = new JsonResponse();
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus($status);
    }

    protected function errorResponse(string $message, int $status): ResponseInterface
    {
        $response = new JsonResponse();
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES  | JSON_UNESCAPED_UNICODE));

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus($status);
    }
}