<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use MtrDesign\Krait\KraitServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'krait:install')]
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Krait resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', [
            '--provider' => KraitServiceProvider::class,
        ]);
        $this->registerKraitProvider();

        $this->components->info('Krait has been installed successfully.');
    }

    /**
     * Register the Krait service provider in
     * the application configuration file.
     */
    protected function registerKraitProvider(): void
    {
        if (! method_exists(
            ServiceProvider::class,
            'addProviderToBootstrapFile'
        )) {
            return;
        }
        echo 'manual install service!';

        ServiceProvider::addProviderToBootstrapFile(KraitServiceProvider::class);
    }
}
