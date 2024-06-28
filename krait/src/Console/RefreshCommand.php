<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'krait:refresh')]
class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exposes all table vue components components.';

    public function handle()
    {
        $tableComponentsPath = resource_path('js/components/tables');
        $components = glob("$tableComponentsPath/*.{vue}", GLOB_BRACE);

        $tables = [];

        foreach ($components as $component) {
            $tableName = Str::replace('.vue', '', basename($component));
            $relativeModulePath = '.'.explode('/components/tables', $component)[1];
            $tables[$tableName] = $relativeModulePath;
        }

        $stub = $this->getStub();
        $imports = [];
        foreach ($tables as $tableName => $modulePath) {
            $imports[] = sprintf('import %s from "%s"', $tableName, $modulePath);
        }

        $importJs = implode(';'.PHP_EOL, $imports);
        $exportJs = implode(','.PHP_EOL, array_keys($tables));

        $stub = Str::replace('{{imports}}', $importJs, $stub);
        $stub = Str::replace('{{exports}}', $exportJs, $stub);

        $indexFile = fopen(resource_path('js/components/tables/index.js'), 'w') or exit('Unable to open file!');
        fwrite($indexFile, $stub);
        fclose($indexFile);

        return 0;
    }

    private function getStubPath(): string
    {
        return dirname(__DIR__, 2).'/stubs/tables-index.stub';
    }

    private function getStub(): string
    {
        return file_get_contents($this->getStubPath());
    }
}
