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
            'routeUri' => request()->route()->uri,
            'apiBaseUrl' => config('krait.api_base_url'),
            'authToken' => config('krait.api_auth_token')
        ];

        if (config('krait.api_use_csrf')) {
            $config['csrfToken'] = csrf_token();
        }

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
