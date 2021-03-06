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

namespace phpDocumentor\Guides\Twig;

use League\Flysystem\FilesystemInterface;
use phpDocumentor\Guides\Environment;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Webmozart\Assert\Assert;
use function sprintf;
use function trim;

final class AssetsExtension extends AbstractExtension
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('asset', [$this, 'asset'], ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    /**
     * Copies the referenced asset and returns the canonical path to that asset; thus taking the BASE tag into account.
     *
     * The layout for guides includes a BASE tag in the head, which creates the need for all relative urls to actually
     * be relative not to the current file's path; but the root of the Documentation Set. This means that, when
     * rendering paths, you always need to include the canonical path; not that relative to the current file.
     *
     * @param mixed[] $context
     */
    public function asset(array $context, string $path) : string
    {
        $outputPath = $this->copyAsset(
            $context['env'] ?? null,
            $context['destination'] ?? null,
            $path
        );

        // make it relative so it plays nice with the base tag in the HEAD
        return trim($outputPath, '/');
    }

    private function copyAsset(?Environment $environment, ?FilesystemInterface $destination, string $path) : string
    {
        if (!$environment instanceof Environment) {
            return $path;
        }

        if (!$destination instanceof FilesystemInterface) {
            return $path;
        }

        $sourcePath = $environment->absoluteRelativePath($path);
        $outputPath = $environment->outputUrl($path);
        Assert::string($outputPath);
        if ($environment->getOrigin()->has($sourcePath) === false) {
            $this->logger->error(sprintf('Image reference not found "%s"', $sourcePath));

            return $outputPath;
        }

        $fileContents = $environment->getOrigin()->read($sourcePath);
        if ($fileContents === false) {
            $this->logger->error(sprintf('Could not read image file "%s"', $sourcePath));

            return $outputPath;
        }

        $destination->put($outputPath, $fileContents);

        return $outputPath;
    }
}
