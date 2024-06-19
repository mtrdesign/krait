<?php

namespace MtrDesign\Krait;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use JsonException;
use MtrDesign\Krait\DTO\TableColumnDTO;

class Krait
{
    /**
     * @throws JsonException
     */
    public static function js()
    {
        $horizon = Js::from(static::scriptVariables());

        return new HtmlString(<<<HTML
            <script type="module">
                window.Horizon = {$horizon};
            </script>
            HTML);
    }

    public static function scriptVariables(): array
    {
        $config = [
            'apiBaseUrl' => config('krait.api_base_url'),
            'kraitApi' => config('krait.krait_api'),
            'resourceApi' => config('krait.resource_api'),
            'csrfToken' => csrf_token(),
//            'routes' => [
//                'hideColumns' => route('krait.preview-configuration.columns.hide'),
//                'reorderColumns' => route('krait.preview-configuration.columns.reorder'),
//                'resizeColumns' => route('krait.preview-configuration.columns.resize'),
//                'sortColumns' => route('krait.preview-configuration.columns.sort'),
//            ]
        ];

        $internalApiPath = config('krait.path', 'krait');
        if (str_ends_with($internalApiPath, '/')) {
            $internalApiPath = substr($internalApiPath, 0, -1);
        }
        $config['internalApiPath'] = $internalApiPath . '/api';

        return $config;
    }

    public static function column(
        string $name,
        string $label,
        bool $hide,
    ): TableColumnDTO {
        return new TableColumnDTO(
            $name,
            $label,
            $hide,
        );
    }
}
