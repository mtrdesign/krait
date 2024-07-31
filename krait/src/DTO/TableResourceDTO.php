<?php

namespace MtrDesign\Krait\DTO;

use MtrDesign\Krait\Utils\PathUtils;

/**
 * DTO Object for handling consistent column generation.
 */
readonly class TableResourceDTO
{
    /**
     * The column date formatting (if it's datetime column).
     */
    public string $namespace;

    /**
     * The column date formatting (if it's datetime column).
     */
    public string $pathname;

    /**
     * @throws \Exception
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
            throw new \Exception('Pathname or namespace must be specified.');
        }
    }
}
