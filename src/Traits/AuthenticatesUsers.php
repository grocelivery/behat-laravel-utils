<?php

declare(strict_types=1);

namespace Grocelivery\Testing\Laravel\Traits;

use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

/**
 * Trait AuthenticatesUsers
 * @package Grocelivery\Testing\Laravel\Traits
 */
trait AuthenticatesUsers
{
    /** @var string */
    protected $accessToken;

    /**
     * @Given user with :email email and :password password exists
     * @param string $email
     * @param string $password
     */
    public function userWithAndPasswordIsExists(string $email, string $password): void
    {
        $this->getUserModelClass()::create([
            'email' => $email,
            'name' => $email,
            'password' => Hash::make($password)
        ]);
    }

    /**
     * @Given :email email is verified
     * @param string $email
     */
    public function emailIsVerified(string $email): void
    {
        $this->getUserModelClass()::whereEmail($email)->first()->markEmailAsVerified();
    }

    /**
     * @Given user with :email email is authenticated
     * @param $email
     */
    public function userWithEmailIsAuthenticated(string $email): void
    {
        Passport::actingAs($this->getUserModelClass()::whereEmail($email)->first());
    }
}
