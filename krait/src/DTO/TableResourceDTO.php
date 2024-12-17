<?php

namespace MtrDesign\Krait\DTO;

use Exception;
use MtrDesign\Krait\Utils\PathUtils;

/**
 * DTO Object for handling TableResource data
 * like Vue.js components, Controllers, Definition Classes, etc.
 */
readonly class TableResourceDTO
{
    /**
     * The resource namespace.
     */
    public string $namespace;

    /**
     * The resource pathname
     */
    public string $pathname;

    /**
     * Initialises a new instance of the DTO class.
     *
     * @param  string|null  $pathname  - the resource pathname
     * @param  string|null  $namespace  - the resource namespace
     *
     * @throws Exception
     */
    public function __construct(
        ?string $pathname = null,
        ?string $namespace = null,
    ) {
        if ($namespace) {
            $this->namespace = $namespace;
            $this->pathname = PathUtils::namespaceToDir($namespace, extension: 'php');
        } elseif ($pathname) {
            $this->namespace = PathUtils::dirToNamespace($pathname);
            $this->pathname = $pathname;
        } else {
            throw new Exception('Pathname or namespace must be specified.');
        }
    }
}
