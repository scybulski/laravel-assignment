<?php

namespace App\Http\Controllers;

use App\Facades\Contracts\AuthContract;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\StatusResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthContract $authService)
    {
    }

    public function login(LoginRequest $request): StatusResponse
    {
        try {
            $authDto = $this->authService->authenticate(
                $request->validated('login'),
                $request->validated('password'),
            );

            return StatusResponse::make(additionalPayload: [
                'token' => $authDto->token,
            ]);
        } catch (\Illuminate\Auth\AuthenticationException) {
            return StatusResponse::make(
                httpStatus: StatusResponse::HTTP_UNAUTHORIZED,
                status: StatusResponse::STATUS_UNAUTHORIZED,
            );
        }
    }
}
