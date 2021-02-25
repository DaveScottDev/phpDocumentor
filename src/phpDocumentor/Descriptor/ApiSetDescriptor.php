<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Descriptor;

use phpDocumentor\Dsn;
use phpDocumentor\Path;

final class ApiSetDescriptor extends DocumentationSetDescriptor
{
    /** @var Collection<FileDescriptor> */
    private $files;

    /** @var Collection<NamespaceDescriptor> */
    private $namespaces;

    /**
     * @param array{dsn: Dsn, paths: array<Path>} $source
     */
    public function __construct(string $name, array $source, string $output, Collection $files, Collection $namespaces)
    {
        $this->name = $name;
        $this->source = $source;
        $this->output = $output;
        $this->files = $files;
        $this->namespaces = $namespaces;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getNamespaces(): Collection
    {
        return $this->namespaces;
    }
}
