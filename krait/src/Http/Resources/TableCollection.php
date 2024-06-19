<?php

namespace MtrDesign\Krait\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use MtrDesign\Krait\CustomPreview;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;
use MtrDesign\Krait\Tables\BaseTable;

class TableCollection extends ResourceCollection
{
    protected BaseTable $table;
    public function __construct($resource, BaseTable $table)
    {
        parent::__construct($resource);
        $this->table = $table;
    }

    public function toArray(Request $request)
    {
        return $this->collection->map(function ($record) {
            return array_merge(
                [
                    'uuid' => $record->{$this->table->getKeyName()}
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
            $previewConfiguration = $this->getPreviewConfiguration($user);
            if ($previewConfiguration) {
                $previewConfiguration = new KraitPreviewConfigurationResource($previewConfiguration);
            }
        }

        return [
            'preview_configuration' => $previewConfiguration,
            'columns' => $this->getColumns($previewConfiguration),
        ];
    }

    private function getPreviewConfiguration(CustomPreview $user): ?KraitPreviewConfiguration
    {
        return KraitPreviewConfiguration::where('user_id', $user->id)->where([
            'table_name', $this->table->name()
        ])->first();
    }

    private function getColumns(?KraitPreviewConfiguration $previewConfiguration = null): array {
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
}
