<?php

namespace MtrDesign\Krait\Services\TablesOrchestrator;

use JetBrains\PhpStorm\NoReturn;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Tables\BaseTable;

class TableCluster
{
    protected string $definitionClass;
    protected string $tableClass;
    protected string $tableName;
    protected ?string $tablePrefix = null;
    protected ?string $snakeTablePrefix = null;

    protected ?string $controller = null;
    protected ?string $vue = null;

    protected ?BaseTable $instance = null;

    #[NoReturn]
    public function __construct(string $definitionClass)
    {
        $this->definitionClass = $definitionClass;
        $classParts = explode('\\', $definitionClass);

        $this->tableClass = end($classParts);
        $this->tableName = strtolower(preg_replace("/([a-z])([A-Z])/", "$1-$2", $this->tableClass));

        $prefix = str_replace("\\$this->tableClass", '', $this->definitionClass);
        $prefix = str_replace("App\\Tables", '', $prefix);
        if ($prefix) {
            if (str_starts_with($prefix, '\\')) {
                $prefix = substr($prefix, 1);
            }
            $this->tablePrefix = $prefix;
            $this->snakeTablePrefix = strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $this->tablePrefix));
        }
    }

    public function getDefinitionClass(): string
    {
        return $this->definitionClass;
    }

    public function getController(): string
    {
        if ($this->controller) {
            return $this->controller;
        }

        $namespace = config('krait.table_controllers_directory');
        $namespace = str_replace(app_path(), '', $namespace);
        $namespace = str_replace('/', '\\', $namespace);
        $namespace = "App$namespace";

        if ($this->tablePrefix) {
            $namespace = "$namespace\\$this->tablePrefix";
        }

        $this->controller = sprintf('%s\\%sController', $namespace, $this->tableClass);
        return $this->controller;
    }

    public function getVue(): string
    {
        if ($this->vue) {
            return $this->vue;
        }

        $path = config('krait.table_components_directory');
        if ($this->snakeTablePrefix) {
            $path = "$path/$this->snakeTablePrefix";
        }

        return "$path/$this->tableClass.vue";
    }

    public function instantiate(PreviewConfigService $previewConfigService): TableCluster
    {
        if (empty($this->instance)) {
            $definitionClass = $this->getDefinitionClass();
            $this->instance = new $definitionClass($previewConfigService);
        }

        return $this;
    }

    public function getInstance(): ?BaseTable
    {
        return $this->instance;
    }

    public function getRoute(): string
    {
        return "$this->snakeTablePrefix/$this->tableName";
    }
}
