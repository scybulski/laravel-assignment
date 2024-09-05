<?php

use App\Adapters\Auth\BarAuthAdapter;
use External\Bar\Auth\LoginService;
use Mockery\MockInterface;

it('should authenticate user', function () {
    $user = 'Alice';
    $password = 'alis_password';

    $fooAuthWsMock = Mockery::mock(LoginService::class, function (MockInterface $mock) use ($user, $password): void {
        $mock->shouldReceive('login')
            ->once()
            ->with($user, $password)
            ->andReturn(true);
    });

    $fooAuthAdapter = new BarAuthAdapter(
        externalLoginService: $fooAuthWsMock,
    );

    $result = $fooAuthAdapter->authenticate($user, $password);

    expect($result)->toBeTrue();
});

it('should return false when user is not authenticated', function () {
    $user = 'Alice';
    $password = 'bobs_password';

    $fooAuthWsMock = Mockery::mock(LoginService::class, function (MockInterface $mock) use ($user, $password): void {
        $mock->shouldReceive('login')
            ->once()
            ->with($user, $password)
            ->andReturn(false);
    });

    $fooAuthAdapter = new BarAuthAdapter(
        externalLoginService: $fooAuthWsMock,
    );

    $result = $fooAuthAdapter->authenticate($user, $password);

    expect($result)->toBeFalse();
});
