<?php

namespace MtrDesign\Krait\Utils;

class PathUtils
{
    static function dirToNamespace(string $directory): string
    {
        $namespace = str_replace(app_path(), 'App', $directory);
        $namespace = str_replace('.php', '', $namespace);

        return str_replace('/', '\\', $namespace);
    }

    static function namespaceToDir(string $namespace, ?string $extension = null): string
    {
        $directory = str_replace('App', app_path(), $namespace);
        $directory = str_replace('\\', '/', $directory);

        if ($extension) {
            $directory = "$directory.$extension";
        }

        return $directory;
    }

    static function camelToSlug(string $value)
    {
        return strtolower(preg_replace("/([a-z])([A-Z])/", "$1-$2", $value));
    }

    static function camelToSnake(string $value)
    {
        return strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $value));
    }
}
