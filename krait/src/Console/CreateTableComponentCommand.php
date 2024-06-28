<?php

namespace MtrDesign\Krait\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'krait:table-vue-component')]
class CreateTableComponentCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krait:table-vue-component {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the table front-end component.';

    protected $type = 'Table Vue component';

    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     */
    protected function alreadyExists($rawName): string
    {
        $name = class_basename(str_replace('\\', '/', $rawName));
        $path = resource_path("js/components/{$name}.vue");

        return file_exists($path);
    }

    protected function getStub(): string
    {
        return dirname(__DIR__, 2).'/stubs/table-vue.stub';
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     */
    protected function replaceNamespace(&$stub, $name): CreateTableComponentCommand
    {
        $name = class_basename(str_replace('\\', '/', $name));
        $tableName = Str::kebab($name);
        $tableName = Str::lower($tableName);

        $stub = str_replace('{table_name}', $tableName, $stub);

        return $this;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     */
    protected function getPath($name): string
    {
        $name = class_basename(str_replace('\\', '/', $name));

        return resource_path("js/components/tables/{$name}.vue");
    }
}
