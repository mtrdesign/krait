<?php

namespace MtrDesign\Krait\Utils;

class PathUtils
{
    public static function dirToNamespace(string $directory): string
    {
        $namespace = str_replace(app_path(), 'App', $directory);
        $namespace = str_replace('.php', '', $namespace);

        return str_replace('/', '\\', $namespace);
    }

    public static function namespaceToDir(string $namespace, ?string $extension = null): string
    {
        $directory = str_replace('App', app_path(), $namespace);
        $directory = str_replace('\\', '/', $directory);

        if ($extension) {
            $directory = "$directory.$extension";
        }

        return $directory;
    }

    public static function camelToSlug(string $value)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $value));
    }

    public static function camelToSnake(string $value)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $value));
    }
}
