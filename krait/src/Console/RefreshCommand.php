<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use MtrDesign\Krait\Services\TablesOrchestrator\TablesOrchestrator;
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

        $tablesOrchestrator = app(TablesOrchestrator::class);
        $tables = $tablesOrchestrator->getTables();
        $components = [];
        foreach ($tables as $table) {
            $pathname = $table->getVue()->pathname;

            $import = str_replace(
                config('krait.table_components_directory').'/',
                '',
                $pathname
            );
            $importParts = explode('/', $import);
            foreach ($importParts as $index => $part) {
                $importParts[$index] = ucfirst($part);
            }
            $componentName = implode('', $importParts);
            $componentName = str_replace('.vue', '', $componentName);
            $components[$componentName] = './'.$import;
        }

        $stub = $this->getStub();
        $imports = [];
        foreach ($components as $componentName => $import) {
            $imports[] = sprintf('import %s from "%s"', $componentName, $import);
        }

        $importJs = implode(';'.PHP_EOL, $imports);
        $exportJs = implode(','.PHP_EOL.'    ', array_keys($components));

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
