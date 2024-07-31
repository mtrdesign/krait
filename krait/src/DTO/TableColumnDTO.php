<?php

namespace MtrDesign\Krait\DTO;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * DTO Object for handling consistent column generation.
 */
readonly class TableColumnDTO
{
    /**
     * The column callbacks.
     *
     * @var callable[]|null[]
     */
    private array $callbacks;

    /**
     * The column date formatting (if it's datetime column).
     */
    public string $dateFormat;

    /**
     * @param  string  $name  - The column name.
     * @param  string  $label  - The column label.
     * @param  bool  $hideLabel  - Flags if the label should be visible.
     * @param  bool  $datetime  - Flags if the column contains datetime information.
     * @param  bool  $sortable  - Flags if the column is sortable.
     * @param  bool  $fixed  - Flags if the column is resizable.
     * @param  string|null  $classes  - Sets FE style classes.
     * @param  callable|null  $process  - Sets the result processing callback.
     * @param  callable|null  $sort  - Sets the records sorting callback.
     */
    public function __construct(
        public string $name,
        public string $label,
        public bool $hideLabel = false,
        public bool $datetime = false,
        public bool $sortable = true,
        public bool $fixed = false,
        public ?string $classes = null,
        ?callable $process = null,
        ?callable $sort = null,
        ?string $dateFormat = null
    ) {
        $this->callbacks = [
            'process' => $process,
            'sort' => $sort,
        ];

        if ($this->datetime) {
            if ($dateFormat !== null) {
                $this->dateFormat = $dateFormat;
            } else {
                $this->dateFormat = config('krait.date_format', 'm/d/Y, g:i a');
            }
        }
    }

    /**
     * Processes one record.
     */
    public function process(mixed $model): mixed
    {
        if (empty($this->callbacks['process'])) {
            return null;
        }

        return $this->callbacks['process']($model);
    }

    /**
     * Sorts records.
     *
     * @param  mixed  $records  - The records.
     * @param  string  $direction  - The sorting direction.
     */
    public function sort(mixed $records, string $direction): mixed
    {
        if (empty($this->callbacks['sort'])) {
            if ($records instanceof Collection) {
                $records->sortBy($this->name, SORT_REGULAR, $direction === 'desc');
            } elseif (method_exists($records, 'getModel') && Schema::hasColumn($records->getModel()->getTable(), $this->name)) {
                $records->orderBy($this->name, $direction);
            }

            return $records;
        }

        return $this->callbacks['sort']($records, $direction);
    }

    /**
     * Flags if the column has processing callback assigned.
     */
    public function hasProcessingCallback(): bool
    {
        return ! empty($this->callbacks['process']);
    }

    /**
     * Returns the column represented as array.
     */
    public function toArray(): array
    {
        $data = (array) $this;
        array_shift($data);

        return $data;
    }
}
