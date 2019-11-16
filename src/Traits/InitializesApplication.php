<?php

declare(strict_types=1);

namespace Grocelivery\Testing\Laravel\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

/**
 * Trait InitializesApplication
 * @package Grocelivery\Testing\Laravel\Traits
 */
trait InitializesApplication
{
    /** @var Application */
    protected $app;

    /**
     * @Given initialized application
     */
    public function initializedApplication(): void
    {
        $this->app = $this->getApp();
        $this->app->loadEnvironmentFrom($this->getEnvFilename());

        (new LoadEnvironmentVariables())->bootstrap($this->app);
        (new LoadConfiguration())->bootstrap($this->app);

        Facade::setFacadeApplication($this->app);

        $this->refreshDatabase();
        $this->createOAuthClient();
        $this->registerProviders();
    }

    protected function refreshDatabase(): void
    {
        Artisan::call('migrate:fresh');
    }

    protected function createOAuthClient(): void
    {
        DB::table('oauth_clients')->insert([
            'id' => config('auth.oauth.client_id'),
            'name' => 'Test OAuth Client',
            'secret' => config('auth.oauth.client_secret'),
            'redirect' => 'http://localhost',
            'revoked' => false,
            'personal_access_client' => false,
            'password_client' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    protected function registerProviders(): void
    {
        foreach (config('testing.providers') as $provider) {
            $this->app->register($provider);
        }
    }
}
