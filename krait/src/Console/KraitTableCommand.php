<?php

namespace MtrDesign\Krait\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use MtrDesign\Krait\Services\TablesOrchestrator\TableCluster;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'krait:table')]
class KraitTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:table {table-class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new table with all required resources.';

    /**
     * The target table classname
     */
    protected string $tableClass;

    /**
     * The table cluster that contains the information for all resources.
     */
    protected TableCluster $tableCluster;

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): int
    {
        $this->tableClass = $this->argument('table-class');
        $this->validateClass();

        $this->tableCluster = new TableCluster(sprintf(
            'App\\Tables\\%s',
            $this->tableClass,
        ));

        $this->createTableDefinitionClass();
        $this->createController();
        $this->createVueComponent();
        $this->callSilent('krait:refresh');

        return 0;
    }

    protected function validateClass(): void
    {
        if (preg_match('/[^a-zA-Z0-9\\\]/', $this->tableClass)) {
            $this->fail(
                'The table name contains invalid characters. '.
                "Only letters and '\\' are allowed"
            );
        }
    }

    /**
     * @throws Exception
     */
    protected function createTableDefinitionClass(): void
    {
        $definitionClass = $this->tableCluster->getDefinitionClass();

        if (file_exists($definitionClass->pathname)) {
            $continue = $this->confirm("The $definitionClass->pathname definition class already exists. Shall we continue regenerate it?");
            if (! $continue) {
                return;
            }

            unlink($definitionClass->pathname);
        }

        $stubPath = dirname(__DIR__, 2).'/stubs/table-definition-class.stub';
        $class = $this->getClassParts($definitionClass->namespace);

        $this->processStub($stubPath, $definitionClass->pathname, [
            'table_classname' => $class['classname'],
            'table_namespace' => $class['namespace'],
        ]);
    }

    /**
     * @throws Exception
     */
    protected function createController(): void
    {
        $controller = $this->tableCluster->getController();
        $definitionClass = $this->tableCluster->getDefinitionClass();

        if (file_exists($controller->pathname)) {
            $continue = $this->confirm("The $controller->pathname controller already exists. Shall we continue regenerate it?");
            if (! $continue) {
                return;
            }

            unlink($controller->pathname);
        }

        $stubPath = dirname(__DIR__, 2).'/stubs/table-controller.stub';
        $controllerClass = $this->getClassParts($controller->namespace);
        $tableClass = $this->getClassParts($definitionClass->namespace);

        $this->processStub($stubPath, $controller->pathname, [
            'controller_namespace' => $controllerClass['namespace'],
            'controller_classname' => $controllerClass['classname'],
            'table_class' => $definitionClass->namespace,
            'table_classname' => $tableClass['classname'],
        ]);
    }

    /**
     * @throws Exception
     */
    protected function createVueComponent(): void
    {
        $vue = $this->tableCluster->getVue();

        if (file_exists($vue->pathname)) {
            $continue = $this->confirm("The $vue->pathname component already exists. Shall we continue regenerate it?");
            if (! $continue) {
                return;
            }

            unlink($vue->pathname);
        }

        $stubPath = dirname(__DIR__, 2).'/stubs/table-vue.stub';

        $this->processStub($stubPath, $vue->pathname, [
            'table_name' => $this->tableCluster->getRoute(),
        ]);
    }

    protected function processStub(string $stubPath, string $targetPath, ?array $arguments = null): void
    {
        $template = file_get_contents($stubPath);
        foreach ($arguments as $key => $value) {
            $template = str_replace(sprintf('{{%s}}', $key), $value, $template);
        }

        $directory = dirname($targetPath);
        if (! File::exists($directory)) {
            File::makeDirectory($directory, mode: 0775, recursive: true);
        }

        file_put_contents($targetPath, $template);
    }

    protected function getClassParts(string $fullClass): array
    {
        $namespaceParts = explode('\\', $fullClass);
        $classname = end($namespaceParts);
        array_pop($namespaceParts);
        $namespace = implode('\\', $namespaceParts);

        return [
            'classname' => $classname,
            'namespace' => $namespace,
        ];
    }
}
