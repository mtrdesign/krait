<?php

namespace MtrDesign\Krait\Services\TablesOrchestrator;

use FilesystemIterator;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Tables\BaseTable;
use MtrDesign\Krait\Utils\PathUtils;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class TablesOrchestrator
{
    /**
     * @var TableCluster[]
     */
    protected array $tables = [];

    public function __construct(
        protected PreviewConfigService $previewConfigService
    ) {}

    public function registerTable(\SplFileInfo $table): TablesOrchestrator
    {
        $definitionClass = PathUtils::dirToNamespace($table->getPathname());

        $this->tables[$definitionClass] = new TableCluster($definitionClass);
        $this->tables[$definitionClass]->instantiate($this->previewConfigService);

        return $this;
    }

    public function getTable(string $tableClass): BaseTable
    {
        return $this->tables[$tableClass]->getInstance();
    }

    public function getTables(): array
    {
        return $this->tables;
    }

    public static function getTablesDirectoryIterator(): ?RecursiveIteratorIterator
    {
        $tablesDirectory = self::getTablesDefinitionDirectory();
        if (! file_exists($tablesDirectory)) {
            return null;
        }

        $directoryIterator = new RecursiveDirectoryIterator($tablesDirectory, FilesystemIterator::SKIP_DOTS);

        return new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);
    }

    public static function getTablesDefinitionDirectory(): string
    {
        return config('krait.table_definition_classes_directory');
    }

    public static function getTableDefinitionClass(string $pathname): string
    {
        $class = str_replace(app_path(), 'App', $pathname);
        $class = str_replace('/', '\\', $class);

        return str_replace('.php', '', $class);
    }
}
