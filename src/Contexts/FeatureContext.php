<?php

declare(strict_types=1);

namespace Grocelivery\Testing\Laravel\Contexts;

use Behat\Behat\Context\Context;
use Grocelivery\Testing\Laravel\Traits\AuthenticatesUsers;
use Grocelivery\Testing\Laravel\Traits\InitializesApplication;
use Grocelivery\Testing\Laravel\Traits\SendsRequests;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class FeatureContext
 * @package Grocelivery\Testing\Laravel\Contexts
 */
class FeatureContext implements Context
{
    use InitializesApplication, SendsRequests, AuthenticatesUsers;

    /**
     * @return mixed
     */
    protected function getApp(): Application
    {
        return require __DIR__ . '/../../../../../bootstrap/app.php';
    }

    /**
     * @return string
     */
    protected function getEnvFilename(): string
    {
        return '.env.testing';
    }

        /**
     * @return string
     */
    protected function getUserModelClass(): string
    {
        return (string)config('testing.user.model');
    }
}
