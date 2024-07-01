<?php

namespace MtrDesign\Krait\Console;

use Composer\InstalledVersions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
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
    protected $signature = 'krait:install {--dev}';

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
    public function handle(): int
    {
        $this->info('Publishing assets...');
        $this->callSilent('vendor:publish', [
            '--provider' => KraitServiceProvider::class,
        ]);
        $this->registerKraitProvider();
        $this->info('Assets have been published successfully🚀');

        if (empty($this->option('dev'))) {
            $this->installJsPackage();
        }

        $this->components->info('Krait has been installed successfully🎉');

        return 0;
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

        ServiceProvider::addProviderToBootstrapFile(KraitServiceProvider::class);
    }

    private function getCurrentPackageVersion(): string
    {
        return InstalledVersions::getVersion('mtrdesign/krait');
    }

    private function installJsPackage(): void
    {
        $version = $this->getCurrentPackageVersion();
        $jsPackage = sprintf('npm install --save @mtrdesign/krait-ui@%s', $version);

        $this->info(sprintf('Installing the front-end library (%s)...', $jsPackage));
        $installation = Process::path(base_path())->run(sprintf('npm install --save %s', $jsPackage));
        if ($installation->successful()) {
            $this->info('Krait UI installed successfully✅');
        } else {
            $this->warn($installation->output());
            $this->fail('Krait UI hasn\'t been installed successfully.');
        }
    }
}
