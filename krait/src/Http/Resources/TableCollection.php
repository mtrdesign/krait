<?php

namespace MtrDesign\Krait\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;
use MtrDesign\Krait\Tables\BaseTable;

class TableCollection extends ResourceCollection
{
    protected BaseTable $table;

    private ?KraitPreviewConfiguration $previewConfiguration = null;

    public function __construct(
        mixed $resource,
        BaseTable $table,
    ) {
        $this->table = $table;

        $defaultItemsPerPage = config('krait.default_items_per_page', 30);
        if ($resource instanceof Builder) {
            $previewConfiguration = $this->getPreviewConfiguration();

            if ($previewConfiguration && $previewConfiguration->items_per_page) {
                $resource = $resource->paginate($previewConfiguration->items_per_page);
            } else {
                $resource = $resource->paginate($defaultItemsPerPage);
            }
        } elseif (! $resource instanceof LengthAwarePaginator) {
            $resource = $this->getPaginator($resource, $defaultItemsPerPage);
        }
        parent::__construct($resource);
    }

    public function toArray(Request $request)
    {
        return $this->collection->map(function ($record) {
            return array_merge(
                [
                    'uuid' => $record->{$this->table->getKeyName()} ?? null,
                ],
                $this->table->processRecord($record),
                $this->table->additionalData($record),
            );
        });
    }

    public function with(Request $request): array
    {
        $user = $request->user();
        $previewConfiguration = null;
        if ($user) {
            $previewConfiguration = $this->getPreviewConfiguration();
        }

        return [
            'preview_configuration' => $previewConfiguration ? new KraitPreviewConfigurationResource($previewConfiguration) : null,
            'columns' => $this->getColumns($previewConfiguration),
        ];
    }

    private function getPreviewConfiguration(): ?KraitPreviewConfiguration
    {
        if ($this->previewConfiguration !== null) {
            return $this->previewConfiguration;
        }

        $user = request()->user();
        if (empty($user)) {
            return null;
        }

        $this->previewConfiguration = KraitPreviewConfiguration::where([
            ['user_id', $user->id],
            ['table_name', $this->table->name()],
        ])->first();

        return $this->previewConfiguration;
    }

    private function getColumns(?KraitPreviewConfiguration $previewConfiguration = null): array
    {
        $columns = $this->table->getColumns();
        $columnsCount = count($columns);
        $rawColumns = [];

        $order = $previewConfiguration?->columns_order;
        $orderFlipped = $order ? array_flip($order) : null;

        foreach ($columns as $column) {
            $data = $column->toArray();
            if (! empty($order) && in_array($column->name, $order)) {
                $rawColumns[$orderFlipped[$column->name]] = $data;

                continue;
            }

            if (empty($rawColumns[$columnsCount])) {
                $rawColumns[$columnsCount] = $data;
            } else {
                $rawColumns[] = $data;
            }
        }

        ksort($rawColumns);

        return array_values($rawColumns);
    }

    private function getPaginator(Collection|array $resource, int $itemsPerPage): LengthAwarePaginator
    {
        if ($resource instanceof Collection) {
            $resource = $resource->toArray();
        }

        $total = count($resource);
        $currentPage = request()->input('page') ?? 1;
        $starting_point = ($currentPage * $itemsPerPage) - $itemsPerPage;
        $resource = array_slice($resource, $starting_point, $itemsPerPage, true);

        return new LengthAwarePaginator(
            $resource,
            $total,
            $itemsPerPage,
            $currentPage, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
    }
}
