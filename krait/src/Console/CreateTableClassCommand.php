<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;


//#[AsCommand(name: 'krait:table-class')]
class CreateTableClassCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:table-class {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the table class..';

    protected $type = 'Table';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    protected function getStub()
    {
        return dirname(__DIR__, 2).'/stubs/table.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Tables';
    }

    protected function replaceClass($stub, $name)
    {
        $namespace = $this->getNamespace($name);
        $class = str_replace($namespace . '\\', '', $name);
        $tableName = Str::kebab($class);
        $tableName = Str::lower($tableName);

        $code = $stub;
        $code = str_replace('{{table_namespace}}', $namespace, $code);
        $code = str_replace('{{table_classname}}', $class, $code);
        $code = str_replace('{{table_name}}', $tableName, $code);

        return $code;
    }
}
