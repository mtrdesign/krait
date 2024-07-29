<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'krait:table-controller')]
class CreateTableControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:table-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the table API controller..';

    protected $type = 'Table Controller';

    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $files;

    protected function getStub(): string
    {
        return dirname(__DIR__, 2).'/stubs/controller.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Controllers\Tables';
    }

    protected function replaceClass($stub, $name): string
    {
        $name = $this->getNameInput();

        $controllerNamespace = $this->getNamespace($name);
        $controllerClass = str_replace($controllerNamespace.'\\', '', $name);

        $tableNamespace = 'App\\Tables\\'.str_replace($controllerClass, '', $name);
        $tableClass = str_replace('Controller', '', $controllerClass);

        $code = $stub;
        $code = str_replace('{{ namespace }}', $controllerNamespace, $code);
        $code = str_replace('{{ class }}', $controllerClass, $code);
        $code = str_replace('{{ tableNamespace }}', $tableNamespace, $code);

        return str_replace('{{ tableClass }}', $tableClass, $code);
    }

    protected function getNameInput(): string
    {
        $name = $this->argument('name');
        if (! str_ends_with($name, 'Controller')) {
            $name = $name.'Controller';
        }

        return $name;
    }
}
