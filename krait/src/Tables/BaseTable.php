<?php

namespace MtrDesign\Krait\Tables;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Prompts\Table;
use MtrDesign\Krait\DTO\TableColumnDTO;
use MtrDesign\Krait\Http\Resources\TableCollection;
use MtrDesign\Krait\Services\PreviewConfigService;

/**
 * BaseTable
 *
 * Represents the fundamental structure of all tables.
 */
abstract class BaseTable
{
    /**
     * The internal table columns.
     *
     * @var TableColumnDTO[]
     */
    protected array $columns;

    /**
     * The table namespace based on
     */
    protected string $namespace;

    /**
     * The preview configuration service.
     */
    public PreviewConfigService $previewConfigService;

    public function __construct(PreviewConfigService $previewConfigService, ?string $namespace = null)
    {
        $this->previewConfigService = $previewConfigService;
    }

    /**
     * Initializes the table columns.
     */
    abstract public function initColumns(): void;

    /**
     * Flags if the request is authorized to see the table's data.
     */
    public function authorize(Request $request): bool
    {
        return true;
    }

    /**
     * Returns the specific table middlewares that should be
     * applied to the request.
     */
    public function middlewares(): array
    {
        return [];
    }

    /**
     * Flags if the columns should be refreshed on every request.
     * Usable for dynamic columns serving (from a third-party services).
     */
    protected function shouldRefresh(): bool
    {
        return config('krait.hard_refresh', false);
    }

    /**
     * Adds a column to the table
     *
     * @param  string  $name  - The columns name
     * @param  string  $label  - The columns label
     * @param  bool  $hideLabel  - Flags if the label should be visible in the header.
     * @param  bool  $datetime  - Flags if the column contains datetime object.
     * @param  bool  $sortable  - Flags if the column is sortable.
     * @param  bool  $fixed  - Flags if the column is resizable.
     * @param  string|null  $classes  - Additional classes that will be added on FE.
     * @param  callable|null  $process  - The column result generation callback.
     * @param  callable|null  $sort  - The column sorting callback.
     */
    protected function column(
        string $name,
        string $label,
        bool $hideLabel = false,
        bool $datetime = false,
        bool $sortable = true,
        bool $fixed = false,
        ?string $classes = null,
        ?callable $process = null,
        ?callable $sort = null,
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
        if ($this->shouldRefresh() || empty($this->columns)) {
            $this->columns = [];
            $this->initColumns();
        }

        return $this->columns;
    }

    /**
     * Returns specific column
     *
     * @throws Exception
     */
    public function getColumn(string $columnName): ?TableColumnDTO
    {
        $columns = $this->getColumns();

        $column = $columns[$columnName] ?? null;
        if (empty($column)) {
            throw new Exception("Invalid column name $columnName.");
        }

        return $column;
    }

    /**
     * Returns specific column
     *
     * @throws Exception
     */
    public function hasColumn(string $name): bool
    {
        $columns = $this->getColumns();

        return isset($columns[$name]);
    }

    /**
     * Returns a Laravel Facade of the Table class.
     */
    protected static function getFacade(): BaseTable
    {
        return app(get_called_class());
    }

    /**
     * Processes one record.
     *
     * @param  Model|array  $resource  - The record.
     * @param  mixed|null  $placeholder  - The placeholder for empty values.
     */
    public function processRecord(Model|array $resource, mixed $placeholder = null): array
    {
        if (is_array($resource)) {
            $resource = (object) $resource;
        }

        $row = [];
        foreach ($this->getColumns() as $column) {
            $columnMethod = sprintf('get%s', Str::ucfirst($column->name));

            if ($column->hasProcessingCallback()) {
                $row[$column->name] = $column->process($resource) ?? $placeholder;
            } elseif (method_exists($this, $columnMethod)) {
                $row[$column->name] = $this->{$columnMethod}($resource) ?? $placeholder;
            } else {
                $row[$column->name] = $resource->{$column->name} ?? $placeholder;
            }

            if ($column->datetime && $row[$column->name] !== $placeholder) {
                $date = $row[$column->name];
                if (is_string($date)) {
                    $date = Carbon::parse($date);
                }

                $row[$column->name] = $date ? $date->format($column->dateFormat) : $placeholder;
            }
        }

        return $row;
    }

    /**
     * Processes a record.
     *
     * @param  mixed  $resource  - The target record.
     * @param  mixed|null  $placeholder  - The placeholder for empty values.
     */
    public static function process(mixed $resource, mixed $placeholder = null): mixed
    {
        return static::getFacade()->processRecord($resource, $placeholder);
    }

    /**
     * Generates an API Resource Collection for the table.
     *
     * @param  mixed  $records  - The target records.
     *
     * @throws Exception
     */
    public static function from(mixed $records): TableCollection
    {
        $table = static::getFacade();
        $user = request()->user();
        $previewConfiguration = $table->previewConfigService->getConfiguration($user, $table->name());
        $table->previewConfigService->sort($records, $previewConfiguration, $table);

        return new TableCollection($records, $table);
    }

    /**
     * Returns the table additional data passed to the FE.
     */
    public function additionalData(mixed $resource): array
    {
        return [];
    }

    /**
     * Returns the record ID key.
     */
    public function getKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Returns the action links for specific resource
     */
    public function actionLinks(mixed $resource): array
    {
        return [];
    }
}
