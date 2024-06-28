<?php

namespace MtrDesign\Krait\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;
use MtrDesign\Krait\Tables\BaseTable;

/**
 * PreviewConfigService
 *
 * Handles all complex preview-related functionalities.
 */
class PreviewConfigService
{
    /**
     * Returns the current user preview config for specific table.
     */
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

    /**
     * Sorts records collection based on the Table and User configurations.
     *
     * @throws Exception
     */
    public function sort(
        array|Collection|Builder|EloquentCollection $records,
        KraitPreviewConfiguration $previewConfiguration,
        BaseTable $table,
    ): array|Builder|EloquentCollection|Collection {
        if (in_array(null, [$previewConfiguration->sort_column, $previewConfiguration->sort_direction])) {
            return $records;
        }
        $column = $table->getColumn($previewConfiguration->sort_column);

        return $column->sort($records, $previewConfiguration->sort_direction);
    }
}
