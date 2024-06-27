<?php

namespace MtrDesign\Krait\DTO;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

readonly class TableColumnDTO
{
    private array $callbacks;

    public function __construct(
        public string $name,
        public string $label,
        public bool $hideLabel = false,
        public bool $datetime = false,
        public bool $sortable = true,
        public bool $fixed = false,
        public ?string $classes = null,
        callable $process = null,
        callable $sort = null,
    ) {
        $this->callbacks = [
            'process' => $process,
            'sort' => $sort,
        ];
    }

    public function process(mixed $model): mixed {
        if (empty($this->callbacks['process'])) {
            return null;
        }

        return $this->callbacks['process']($model);
    }

    public function sort(mixed $records, string $direction): mixed {
        if (empty($this->callbacks['sort'])) {
            return $records;
        }

        return $this->callbacks['sort']($records, $direction);
    }

    public function hasCallback(): bool{
        return ! empty($this->callbacks);
    }

    public function toArray(): array {
        $data = (array)$this;
        array_shift($data);

        return $data;
    }
}
