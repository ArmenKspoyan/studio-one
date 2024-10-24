<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ErrorResource extends JsonResource
{
    /**
     * Customize the outgoing response for the resource.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return void
     */
    public function withResponse(Request $request, $response): void
    {
        $response->setStatusCode(500);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        self::withoutWrapping();

        return [
            'success' => false,
            'message' => $this['message'] ?? '',
        ];
    }
}
