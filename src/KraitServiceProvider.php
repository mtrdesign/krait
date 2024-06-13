<?php

namespace MtrDesign\Krait;

use Carbon\Laravel\ServiceProvider;

class KraitServiceProvider extends ServiceProvider
{

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/krait.php', 'krait');
    }

    public function boot() {
        $this->configPublishing();
        $this->configCommands();
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configPublishing()
    {
        if ($this->app->runningInConsole()) {
            ### Configurations
            $this->publishes([
                __DIR__.'/../stubs/krait.php' => config_path('krait.php'),
            ], 'krait-config');

            ### JS Assets
            $this->publishes([
                __DIR__ . '/../resources/js' =>
                    resource_path('js/vendor/package-name'
                    )], 'krait-js');
        }
    }


    /**
     * Register the package's commands.
     */
    protected function configCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }
}
