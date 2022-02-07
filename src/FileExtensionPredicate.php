<?php

/*
 * This file is part of the Hierarchy package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Brain\Hierarchy;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class FileExtensionPredicate
{
    /**
     * @var string[]
     */
    private $extensions = [];

    /**
     * @param string ...$rawExtensions
     * @return list<string>
     */
    public static function parseExtensions(string ...$rawExtensions): array
    {
        $parsed = [];
        foreach ($rawExtensions as $rawExtension) {
            $extensions = explode('|', strtolower(trim($rawExtension)));
            foreach ($extensions as $extension) {
                $parsed[] = ltrim(trim($extension), '.');
            }
        }

        return array_values(array_unique($parsed));
    }

    /**
     * @param string ...$extension
     */
    public function __construct(string ...$extension)
    {
        $this->extensions = self::parseExtensions(...$extension);
    }

    /**
     * @param string $templatePath
     * @return bool
     */
    public function __invoke(string $templatePath): bool
    {
        $parts = explode('.', $templatePath);
        // support for "composed" extension like `.html.php`
        $target = (count($parts) > 2)
            ? strtolower(implode('.', array_slice($parts, -2, 2)))
            : strtolower(array_pop($parts));

        foreach ($this->extensions as $extension) {
            if ($target === $extension) {
                return true;
            }
        }

        return false;
    }
}
