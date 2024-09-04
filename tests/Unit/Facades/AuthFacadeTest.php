<?php

use App\Adapters\Auth\BarAuthAdapter;
use App\Dtos\AuthDto;
use App\Enums\SystemsEnum;
use App\Facades\AuthFacade;
use Illuminate\Auth\AuthenticationException;
use Mockery\MockInterface;

test('authenticate to a system through adapter', function (): void {
    $authFacade = new AuthFacade();

    $this->instance(
        BarAuthAdapter::class,
        Mockery::mock(BarAuthAdapter::class, function (MockInterface $mock): void {
            $mock->shouldReceive('authenticate')
                ->once()
                ->with('BAR_login', 'password')
                ->andReturn(true);

            $mock->shouldReceive('getServiceName')
                ->atLeast()
                ->once()
                ->andReturn(SystemsEnum::Bar);
        }),
    );

    /** @var \App\Dtos\AuthDto $result */
    $result = $authFacade->authenticate('BAR_login', 'password');

    expect($result)->toBeInstanceOf(AuthDto::class);

    expect($result->token)->toBeString();

    $jwtPayload = json_decode(base64_decode(explode('.', $result->token)[1]), true);

    expect($jwtPayload['login'])->toBe('BAR_login');
    expect($jwtPayload['system'])->toBe('Bar');
});

test('authenticate to a system through adapter with invalid credentials', function (): void {
    $authFacade = new AuthFacade();

    $this->instance(
        BarAuthAdapter::class,
        Mockery::mock(BarAuthAdapter::class, function (MockInterface $mock): void {
            $mock->shouldReceive('authenticate')
                ->once()
                ->with('BAR_login', 'password')
                ->andReturn(false);
        }),
    );

    expect(fn (): never => $authFacade->authenticate('BAR_login', 'password'))
        ->toThrow(AuthenticationException::class);
});

test('authenticat to a system through adapter with unknown system', function (): void {
    $authFacade = new AuthFacade();

    expect(fn (): never => $authFacade->authenticate('UNKNOWN_login', 'password'))
        ->toThrow(AuthenticationException::class);
});
