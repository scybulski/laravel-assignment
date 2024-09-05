<?php

use App\Adapters\Auth\Contracts\AuthAdapter;
use App\Dtos\AuthDto;
use App\Enums\SystemsEnum;
use App\Facades\AuthFacade;
use App\Factories\AuthAdapterFactory;
use App\Factories\Contracts\AuthAdapterFactoryContract;
use Illuminate\Auth\AuthenticationException;
use Mockery\MockInterface;

test('authenticate to a system through adapter', function (): void {
    $authAdapterMock = Mockery::mock(AuthAdapter::class, function (MockInterface $mock): void {
        $mock->shouldReceive('authenticate')
            ->once()
            ->with('XXX_login', 'password')
            ->andReturn(true);

        $mock->shouldReceive('getServiceName')
            ->atLeast()
            ->once()
            ->andReturn(SystemsEnum::Bar);
    });

    $authAdapterFactoryMock = Mockery::mock(AuthAdapterFactory::class, function (MockInterface $mock) use ($authAdapterMock): void {
        $mock->shouldReceive('create')
            ->once()
            ->with('XXX_login')
            ->andReturn($authAdapterMock);
    });

    $authFacade = new AuthFacade($authAdapterFactoryMock);

    /** @var \App\Dtos\AuthDto $result */
    $result = $authFacade->authenticate('XXX_login', 'password');

    expect($result)->toBeInstanceOf(AuthDto::class);

    expect($result->token)->toBeString();

    $jwtPayload = json_decode(base64_decode(explode('.', $result->token)[1]), true);

    expect($jwtPayload['login'])->toBe('XXX_login');
    expect($jwtPayload['system'])->toBe('Bar');
});

test('authenticate to a system through adapter with invalid credentials', function (): void {
    $authAdapterMock = Mockery::mock(AuthAdapter::class, function (MockInterface $mock): void {
        $mock->shouldReceive('authenticate')
            ->once()
            ->with('XXX_login', 'password')
            ->andReturn(false);

        $mock->shouldReceive('getServiceName')
            ->andReturn(SystemsEnum::Bar);
    });

    $authAdapterFactoryMock = Mockery::mock(AuthAdapterFactory::class, function (MockInterface $mock) use ($authAdapterMock): void {
        $mock->shouldReceive('create')
            ->once()
            ->with('XXX_login')
            ->andReturn($authAdapterMock);
    });

    $authFacade = new AuthFacade($authAdapterFactoryMock);

    expect(fn (): never => $authFacade->authenticate('XXX_login', 'password'))
        ->toThrow(AuthenticationException::class);
});

test('authenticat to a system through adapter with unknown system', function (): void {
    $authAdapterFactoryMock = Mockery::mock(AuthAdapterFactoryContract::class, function (MockInterface $mock): void {
        $mock->shouldReceive('create')
            ->once()
            ->with('UNKNOWN_login')
            ->andReturnNull();
    });

    $authFacade = new AuthFacade($authAdapterFactoryMock);

    expect(fn (): never => $authFacade->authenticate('UNKNOWN_login', 'password'))
        ->toThrow(AuthenticationException::class);
});
