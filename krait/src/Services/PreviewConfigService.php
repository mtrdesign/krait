<?php

namespace MtrDesign\Krait\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Request;
use MtrDesign\Krait\CustomPreview;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;
use MtrDesign\Krait\Tables\BaseTable;

class PreviewConfigService
{
    public function getConfiguration(mixed $user, string $tableName): KraitPreviewConfiguration
    {
        $previewConfiguration = KraitPreviewConfiguration::where([
            ['user_id', $user->id],
            ['table_name', $tableName],
        ])->firstOrNew();

        $previewConfiguration->fill([
            'sort_column' => Request::get('sort_column', $previewConfiguration->sort_column),
            'sort_direction' => Request::get('sort_direction', $previewConfiguration->sort_direction),
            'items_per_page' => Request::get('ipp', $previewConfiguration->items_per_page),
        ]);

        return $previewConfiguration;
    }

    public function sort(
        array|Collection|Builder|EloquentCollection $records,
        KraitPreviewConfiguration $previewConfiguration,
        BaseTable $table,
    ) {
        if (in_array(null, [$previewConfiguration->sort_column, $previewConfiguration->sort_direction])) {
            return $records;
        }
        $column = $table->getColumn($previewConfiguration->sort_column);

        return $column->sort($records, $previewConfiguration->sort_direction);
    }
}
