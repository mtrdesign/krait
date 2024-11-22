<?php

namespace MtrDesign\Krait\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;
use MtrDesign\Krait\Tables\BaseTable;

class TableStructureCollection extends JsonResource
{
    /**
     * The target table instance.
     */
    protected BaseTable $table;

    /**
     * The user preview configuration.
     */
    private ?KraitPreviewConfiguration $previewConfiguration = null;

    public function __construct(BaseTable $table)
    {
        $this->table = $table;

        parent::__construct($table);
    }

    public function toArray(Request $request)
    {
        if (! $this->table->authorize($request)) {
            abort(403);
        }

        $user = $request->user();
        $previewConfiguration = null;
        if ($user) {
            $previewConfiguration = $this->getPreviewConfiguration();
        }

        return [
            'preview_configuration' => $previewConfiguration ? new KraitPreviewConfigurationResource($previewConfiguration) : null,
            'columns' => $this->getColumns($previewConfiguration),
            'selectable_rows' => $this->table->isSelectableRows(),
            'bulk_action_links' => $this->table->bulkActionLinks(),
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
            ['table_name', $this->table->name],
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
}
