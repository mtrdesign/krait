<?php

namespace MtrDesign\Krait\Services\TablesOrchestrator;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use MtrDesign\Krait\DTO\TableResourceDTO;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Tables\BaseTable;

class TableCluster
{
    protected string $definitionClass;

    public readonly string $tableClass;

    protected string $tableName;

    protected ?string $tablePrefix = null;

    protected ?string $snakeTablePrefix = null;

    protected ?TableResourceDTO $controller = null;

    protected ?TableResourceDTO $vue = null;

    protected ?BaseTable $instance = null;

    #[NoReturn]
    public function __construct(string $definitionClass)
    {
        $this->definitionClass = $definitionClass;
        $classParts = explode('\\', $definitionClass);

        $this->tableClass = end($classParts);
        $this->tableName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $this->tableClass));

        $prefix = str_replace("\\$this->tableClass", '', $this->definitionClass);
        $prefix = str_replace('App\\Tables', '', $prefix);
        if ($prefix) {
            if (str_starts_with($prefix, '\\')) {
                $prefix = substr($prefix, 1);
            }
            $this->tablePrefix = $prefix;
            $this->snakeTablePrefix = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->tablePrefix));
        }
    }

    /**
     * @throws Exception
     */
    public function getDefinitionClass(): TableResourceDTO
    {
        return new TableResourceDTO(
            namespace: $this->definitionClass
        );
    }

    /**
     * @throws Exception
     */
    public function getController(): TableResourceDTO
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

        $this->controller = new TableResourceDTO(namespace: sprintf('%s\\%sController', $namespace, $this->tableClass));

        return $this->controller;
    }

    public function getVue(): TableResourceDTO
    {
        if ($this->vue) {
            return $this->vue;
        }

        $path = config('krait.table_components_directory');
        if ($this->snakeTablePrefix) {
            $path = "$path/$this->snakeTablePrefix";
        }

        $this->vue = new TableResourceDTO(pathname: "$path/$this->tableClass.vue");

        return $this->vue;
    }

    public function instantiate(PreviewConfigService $previewConfigService): TableCluster
    {
        if (empty($this->instance)) {
            $definitionClass = $this->getDefinitionClass();
            $this->instance = new $definitionClass->namespace($previewConfigService, $this->getRoute());
        }

        return $this;
    }

    public function getInstance(): ?BaseTable
    {
        return $this->instance;
    }

    public function getRoute(): string
    {
        if ($this->snakeTablePrefix) {
            return "$this->snakeTablePrefix/$this->tableName";
        }

        return "$this->tableName";
    }
}
