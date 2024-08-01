<?php

namespace MtrDesign\Krait\Utils;

/**
 * PathUtils
 *
 * Handles all path-related functionalities.
 */
class PathUtils
{
    /**
     * Converts directory to a namespace.
     *
     * @param  string  $directory  - the target directory
     * @return string - the corresponding namespace
     */
    public static function dirToNamespace(string $directory): string
    {
        $namespace = str_replace(app_path(), 'App', $directory);
        $namespace = str_replace('.php', '', $namespace);

        return str_replace('/', '\\', $namespace);
    }

    /**
     * Converts namespace to directory.
     *
     * @param  string  $namespace  - the target namespace
     * @param  string|null  $extension  - the file extension
     * @return string - the corresponding directory
     */
    public static function namespaceToDir(string $namespace, ?string $extension = null): string
    {
        $directory = str_replace('App', app_path(), $namespace);
        $directory = str_replace('\\', '/', $directory);

        if ($extension) {
            $directory = "$directory.$extension";
        }

        return $directory;
    }

    /**
     * Converts a string from camelCase to slug-case.
     *
     * @param  string  $value  - the target string
     * @return string - the slug-case version of the string
     */
    public static function camelToSlug(string $value): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $value));
    }

    /**
     * Converts a string from camelCase to snake_case.
     *
     * @param  string  $value  - the target string
     * @return string - the snake_case version of the string
     */
    public static function camelToSnake(string $value): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $value));
    }
}
