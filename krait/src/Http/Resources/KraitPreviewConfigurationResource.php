<?php

namespace MtrDesign\Krait\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KraitPreviewConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'table_name' => $this->table_name,
            'sort_column' => $this->sort_column,
            'sort_direction' => $this->sort_direction,
            'columns_order' => $this->columns_order,
            'columns_width' => $this->columns_width,
            'visible_columns' => $this->visible_columns,
        ];
    }
}
