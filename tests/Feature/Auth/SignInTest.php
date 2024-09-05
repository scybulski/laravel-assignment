<?php

use App\Dtos\AuthDto;
use App\Facades\Contracts\AuthContract;
use Illuminate\Auth\AuthenticationException;
use Mockery\MockInterface;

it('should return a token when the user is authenticated', function (): void {
    $login = 'przemek';
    $password = 'password1@';
    $jwtKey = 'sample-jwt-key';

    $this->instance(
        AuthContract::class,
        Mockery::mock(AuthContract::class, function (MockInterface $mock) use ($login, $password, $jwtKey): void {
            $mock->shouldReceive('authenticate')
                ->once()
                ->with($login, $password)
                ->andReturn(new AuthDto(token: $jwtKey));
        }),
    );

    $this->postJson('/api/login', [
        'login' => $login,
        'password' => $password,
    ])
        ->assertOk()
        ->assertJson([
            'status' => 'success',
            'token' => $jwtKey,
        ]);
});

it('should return an error when the user is not authenticated', function (): void {
    $login = 'przemek';
    $password = 'password1@';

    $this->instance(
        AuthContract::class,
        Mockery::mock(AuthContract::class, function (MockInterface $mock) use ($login, $password): void {
            $mock->shouldReceive('authenticate')
                ->once()
                ->with($login, $password)
                ->andThrow(new AuthenticationException());
        }),
    );

    $this->postJson('/api/login', [
        'login' => $login,
        'password' => $password,
    ])
        ->assertUnauthorized()
        ->assertJson([
            'status' => 'unauthorized',
        ]);
});
