<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /**
     * A member signs in.
     *
     * @param User|null $user
     * @return TestCase $this
     */
    protected function signIn($user = null): TestCase
    {
        $user = $user ?: create(User::class);

        $this->actingAs($user);

        return $this;
    }
}
