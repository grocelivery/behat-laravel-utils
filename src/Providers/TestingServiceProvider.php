<?php

declare(strict_types=1);

namespace Grocelivery\Testing\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class TestingServiceProvider
 * @package Grocelivery\Testing\Laravel\Providers
 */
class TestingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '../../../config/testing.php' => config_path('testing.php'),
        ]);
    }
}