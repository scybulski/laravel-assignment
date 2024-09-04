<?php

use App\Adapters\Auth\BazAuthAdapter;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Failure;
use External\Baz\Auth\Responses\Success;
use Mockery\MockInterface;

it('should authenticate user', function () {
    $user = 'Alice';
    $password = 'alis_password';

    $fooAuthWsMock = Mockery::mock(Authenticator::class, function (MockInterface $mock) use ($user, $password): void {
        $mock->shouldReceive('auth')
            ->once()
            ->with($user, $password)
            ->andReturn(new Success());
    });

    $bazAuthAdapter = new BazAuthAdapter(
        externalAuthenticator: $fooAuthWsMock,
    );

    $result = $bazAuthAdapter->authenticate($user, $password);

    expect($result)->toBeTrue();
});

it('should return false when user is not authenticated', function () {
    $user = 'Alice';
    $password = 'bobs_password';

    $fooAuthWsMock = Mockery::mock(Authenticator::class, function (MockInterface $mock) use ($user, $password): void {
        $mock->shouldReceive('auth')
            ->once()
            ->with($user, $password)
            ->andReturn(new Failure());
    });

    $bazAuthAdapter = new BazAuthAdapter(
        externalAuthenticator: $fooAuthWsMock,
    );

    $result = $bazAuthAdapter->authenticate($user, $password);

    expect($result)->toBeFalse();
});
