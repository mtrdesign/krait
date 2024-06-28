<?php

namespace MtrDesign\Krait;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use JsonException;

/**
 * Krait Helper
 */
class Krait
{
    /**
     * Returns the Krait Config JS code.
     *
     * @throws JsonException
     */
    public static function js()
    {
        $krait = Js::from(static::scriptVariables());

        return new HtmlString(<<<HTML
            <script type="module">
                window.Krait = {$krait};
            </script>
            HTML);
    }

    /**
     * Returns the JS Config variables.
     */
    public static function scriptVariables(): array
    {
        $kraitPath = config('krait.krait_path', 'krait');
        self::sanityPath($kraitPath);
        $tablesPath = config('krait.tables_path', 'krait');
        self::sanityPath($tablesPath);

        return [
            'kraitPath' => $kraitPath,
            'tablesPath' => $tablesPath,
            'useCsrf' => config('krait.use_csrf', true),
            'csrfToken' => csrf_token(),
        ];
    }

    /**
     * Sanitises path to ensure that it
     * does not end with slash.
     */
    public static function sanityPath(string &$path): void
    {
        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }
    }
}
