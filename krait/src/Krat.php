<?php

namespace MtrDesign\Krait;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use RuntimeException;

class Krat
{
    public static function js()
    {
        if (($js = @file_get_contents(__DIR__.'/../dist/app.js')) === false) {
            throw new RuntimeException('Unable to load the Horizon dashboard JavaScript.');
        }

        $horizon = Js::from(static::scriptVariables());

        return new HtmlString(<<<HTML
            <script type="module">
                window.Horizon = {$horizon};
                {$js}
            </script>
            HTML);
    }

    private static function scriptVariables()
    {
    }
}
