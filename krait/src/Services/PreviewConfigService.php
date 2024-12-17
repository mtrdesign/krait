<?php

namespace MtrDesign\Krait\Services;

use Exception;
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
     * Returns the user preview configuration.
     *
     * @param  mixed  $user  - the target user object
     * @param  string  $tableName  - the table name
     * @return KraitPreviewConfiguration - the preview configuration
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
     * @param  mixed  $records  - the target records
     * @param  KraitPreviewConfiguration  $previewConfiguration  - the user preview configuration
     * @param  BaseTable  $table  - the table instance
     * @return mixed - the sorted records
     *
     * @throws Exception
     */
    public function sort(
        mixed $records,
        KraitPreviewConfiguration $previewConfiguration,
        BaseTable $table,
    ): mixed {
        if (
            in_array(null, [
                $previewConfiguration->sort_column,
                $previewConfiguration->sort_direction,
            ]) ||
            ! $table->hasColumn($previewConfiguration->sort_column)
        ) {
            return $records;
        }

        $column = $table->getColumn($previewConfiguration->sort_column);

        return $column->sort($records, $previewConfiguration->sort_direction);
    }
}
