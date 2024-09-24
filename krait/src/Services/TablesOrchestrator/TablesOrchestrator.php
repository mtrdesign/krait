<?php

namespace MtrDesign\Krait\Services\TablesOrchestrator;

use FilesystemIterator;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Tables\BaseTable;
use MtrDesign\Krait\Utils\PathUtils;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * TableOrchestrator
 *
 * Handles the Tables registration functionalities.
 */
class TablesOrchestrator
{
    /**
     * All tables in the project
     *
     * @var TableCluster[]
     */
    protected array $tables = [];

    public function __construct(
        protected PreviewConfigService $previewConfigService
    ) {}

    /**
     * Registers a new table to the Container.
     *
     * @param  SplFileInfo  $table  - the table definition class file
     * @return $this
     */
    public function registerTable(SplFileInfo $table): TablesOrchestrator
    {
        $definitionClass = PathUtils::dirToNamespace($table->getPathname());

        $this->tables[$definitionClass] = new TableCluster($definitionClass);
        $this->tables[$definitionClass]->instantiate($this->previewConfigService);

        return $this;
    }

    /**
     * Return the registered instance of specific table.
     *
     * @param  string  $tableClass  - the target table definition class
     */
    public function getTable(string $tableClass): BaseTable
    {
        return $this->tables[$tableClass]->getInstance();
    }

    /**
     * Returns all available tables in the app.
     *
     * @return TableCluster[] - the registered tables
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * Return the registered instance of a specific table by its name.
     *
     * @param  string  $tableName  - the target table name
     */
    public function getTableByRoute(string $tableName): ?BaseTable
    {
        foreach ($this->tables as $table) {
            if ($table->getRoute() === $tableName) {
                return $table->getInstance();
            }
        }

        return null; // Return null if no table matches the name
    }

    /**
     * Generates a directory iterator for all table definition classes.
     *
     * @return RecursiveIteratorIterator|null - the directory iterator
     */
    public static function getTablesDirectoryIterator(): ?RecursiveIteratorIterator
    {
        $tablesDirectory = self::getTablesDefinitionDirectory();
        if (! file_exists($tablesDirectory)) {
            return null;
        }

        $directoryIterator = new RecursiveDirectoryIterator($tablesDirectory, FilesystemIterator::SKIP_DOTS);

        return new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * Returns the Tables Definition Classes directory.
     *
     * @return string - the table definition classes directory
     */
    public static function getTablesDefinitionDirectory(): string
    {
        return config('krait.table_definition_classes_directory');
    }

    /**
     * Returns the Table Definition Class based on its pathname.
     *
     * @param  string  $pathname  - the definition class file pathname
     */
    public static function getTableDefinitionClass(string $pathname): string
    {
        $class = str_replace(app_path(), 'App', $pathname);
        $class = str_replace('/', '\\', $class);

        return str_replace('.php', '', $class);
    }
}
