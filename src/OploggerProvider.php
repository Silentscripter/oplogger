<?php

namespace Protechstudio\Oplogger;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\ServiceProvider;
use Protechstudio\Oplogger\Repositories\LogRepositoryContract;

class OploggerProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @param Guard $auth
     * @param LogRepositoryContract $logRepositoryContract
     */
    public function boot(Guard $auth, LogRepositoryContract $logRepositoryContract)
    {
        $this->publish();
        $this->app->singleton(Oplogger::class, function () use ($auth, $logRepositoryContract) {
            return new Oplogger(config('oplogger.types'), $auth, $logRepositoryContract);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->app->bind(LogRepositoryContract::class, config('oplogger.repository'));
    }

    public function provides()
    {
        return ['Protechstudio\Oplogger\Oplogger'];
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('oplogger.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/Migrations/2016_02_10_214919_create_logs_table.php' => database_path('migrations/2016_02_10_214919_create_logs_table.php'),
        ], 'migrations');
    }

    private function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'oplogger');
    }

}
