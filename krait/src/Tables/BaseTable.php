<?php

namespace MtrDesign\Krait\Tables;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Prompts\Table;
use MtrDesign\Krait\DTO\TableColumnDTO;
use MtrDesign\Krait\Http\Resources\TableCollection;
use MtrDesign\Krait\Services\PreviewConfigService;

abstract class BaseTable
{
    /**
     * The internal table columns structure.
     *
     * @var TableColumnDTO[] $columns
     */
    protected array $columns;

    public PreviewConfigService $previewConfigService;

    public function __construct(PreviewConfigService $previewConfigService)
    {
        $this->previewConfigService = $previewConfigService;
    }

    /**
     * Returns the table name
     *
     * @return string
     */
    abstract function name(): string;

    /**
     * Initializes the table columns
     *
     * @return void
     */
    abstract function initColumns(): void;

    /**
     * Flags if the columns should be cached.
     * Usable for dynamic columns serving (from a third-party services).
     *
     * @return bool
     */
    protected function shouldCache(): bool {
        return config('krait.cache_columns', false);
    }

    /**
     * Flags if the columns should be refreshed on every request.
     * Usable for dynamic columns serving (from a third-party services).
     *
     * @return bool
     */
    protected function shouldRefresh(): bool {
        return config('krait.hard_refresh', false);
    }

    /**
     * Adds a column to the table
     *
     * @param string $name
     * @param string $label
     * @param bool $hideLabel
     * @param bool $datetime
     * @param bool $sortable
     * @param bool $fixed
     * @param string|null $classes
     * @param callable|null $process
     * @return void
     */
    protected function column(
        string $name,
        string $label,
        bool $hideLabel = false,
        bool $datetime = false,
        bool $sortable = true,
        bool $fixed = false,
        ?string $classes = null,
        callable $process = null,
        callable $sort = null,
    ): void {
         $this->columns[$name] = new TableColumnDTO(
            name: $name,
            label: $label,
            hideLabel: $hideLabel,
            datetime: $datetime,
            sortable: $sortable,
            fixed: $fixed,
            classes: $classes,
            process: $process,
            sort: $sort
        );
    }

    /**
     * Returns all columns
     *
     * @return TableColumnDTO[]
     */
    public function getColumns(): array
    {
        if ($this->shouldCache() && $columns = $this->getCachedColumns()) {
            return $columns;
        }

        if ($this->shouldRefresh() || empty($this->columns)) {
            $this->columns = [];
            $this->initColumns();
        }

        if ($this->shouldCache()) {
            Cache::put($this->name(), $this->columns, 5 * 60);
        }

        return $this->columns;
    }

    /**
     * Returns specific column
     *
     * @param string $columnName
     * @return TableColumnDTO
     * @throws Exception
     */
    public function getColumn(string $columnName): TableColumnDTO
    {
        $columns = $this->getColumns();

        $column = $columns[$columnName] ?? null;
        if (empty($column)) {
            throw new Exception("Invalid column name $columnName.");
        }

        return $column;
    }

    /**
     * Returns the columns from the cache (if there are any)
     *
     * @return array|null
     */
    protected function getCachedColumns(): array|null
    {
        return Cache::get($this->name());
    }

    protected static function getFacade(): BaseTable {
        return app(get_called_class());
    }

    public function processRecord(Model|array $resource, mixed $placeholder = null) {
        if (is_array($resource)) {
            $resource = (object)$resource;
        }

        $row = [];
        foreach ($this->getColumns() as $column) {
            $columnMethod = sprintf('get%s', Str::ucfirst($column->name));

            if ($column->hasCallback()) {
                $row[$column->name] = $column->process($resource);
            } elseif (method_exists($this, $columnMethod)) {
                $row[$column->name] = $this->{$columnMethod}($resource);
            } else {
                $row = $resource->{$column->name} ?? $placeholder;
            }
        }

        return $row;
    }

    public static function process(mixed $resource, mixed $placeholder = null): mixed
    {
        return static::getFacade()->processRecord($resource, $placeholder);
    }

    public static function from(mixed $records): TableCollection
    {
        $table = static::getFacade();
        $user = request()->user();
        $previewConfiguration = $table->previewConfigService->getConfiguration($user, $table->name());
        $table->previewConfigService->sort($records, $previewConfiguration, $table);

        return new TableCollection($records, $table);
    }

    public static function getName(): string
    {
        return static::getFacade()->name();
    }

    public function additionalData(mixed $resource): array
    {
        return [];
    }

    public function getKeyName(): string
    {
        return 'uuid';
    }
}
