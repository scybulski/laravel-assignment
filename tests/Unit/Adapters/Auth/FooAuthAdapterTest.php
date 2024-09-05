<?php

use App\Adapters\Auth\FooAuthAdapter;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;
use Mockery\MockInterface;

it('should authenticate user', function () {
    $user = 'Alice';
    $password = 'alis_password';

    $fooAuthWsMock = Mockery::mock(AuthWS::class, function (MockInterface $mock) use ($user, $password): void {
        $mock->shouldReceive('authenticate')
            ->once()
            ->with($user, $password)
            ->andReturn();
    });

    $fooAuthAdapter = new FooAuthAdapter(
        externalAuthWS: $fooAuthWsMock,
    );

    $result = $fooAuthAdapter->authenticate($user, $password);

    expect($result)->toBeTrue();
});

it('should return false when user is not authenticated', function () {
    $user = 'Alice';
    $password = 'bobs_password';

    $fooAuthWsMock = Mockery::mock(AuthWS::class, function (MockInterface $mock) use ($user, $password): void {
        $mock->shouldReceive('authenticate')
            ->once()
            ->with($user, $password)
            ->andThrow(new AuthenticationFailedException());
    });

    $fooAuthAdapter = new FooAuthAdapter(
        externalAuthWS: $fooAuthWsMock,
    );

    $result = $fooAuthAdapter->authenticate($user, $password);

    expect($result)->toBeFalse();
});
