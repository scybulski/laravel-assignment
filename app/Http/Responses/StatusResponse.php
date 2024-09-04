<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

final class StatusResponse extends JsonResponse
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_UNAUTHORIZED = 'unauthorized';

    public static $wrap = null;

    public static function make(
        int $httpStatus = self::HTTP_OK,
        string $status = self::STATUS_SUCCESS,
        array $additionalPayload = [],
    ): static {
        return new static(
            data: [
                'status' => $status,
                ...$additionalPayload
            ],
            status: $httpStatus,
        );
    }
}
