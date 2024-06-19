<?php

namespace MtrDesign\Krait\DTO;

use Illuminate\Database\Eloquent\Model;

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
    ) {
        $this->callbacks = [
            'process' => $process
        ];
    }

    public function process(Model $model): mixed {
        if (empty($this->callbacks['process'])) {
            return null;
        }

        return $this->callbacks['process']($model);
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
