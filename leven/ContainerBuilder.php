<?php

/*
 * slim-php-di (https://github.com/juliangut/slim-php-di).
 * Slim Framework PHP-DI container implementation.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/slim-php-di
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Leven;

use DI\Container as DIContainer;
use DI\ContainerBuilder as DIContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * Helper to create and configure a Container.
 *
 * Default Slim services are included in the generated container.
 */
class ContainerBuilder
{
    /**
     * Build PHP-DI container.
     *
     * @param Configuration|null $configuration
     *
     * @throws \RuntimeException
     *
     * @return DIContainer
     */
    public static function build(Configuration $configuration = null): DIContainer
    {
        if ($configuration === null) {
            $configuration = new Configuration();
        }

        $containerBuilder = self::getContainerBuilder($configuration);

        // Default definitions
        $defaultDefinitions = array_merge(
            require __DIR__ . '/definitions.php',
            [Configuration::class => $configuration]
        );

        $containerBuilder->addDefinitions($defaultDefinitions);



        // Custom definitions
        foreach (self::parseDefinitions($configuration->getDefinitions()) as $definitions) {

            $containerBuilder->addDefinitions($definitions);
        }

        $container = $containerBuilder->build();

        //dump($container->get("settings"));

        // Add container itself
        $container->set(ContainerInterface::class, $container);

        return $container;
    }

    /**
     * Get configured container builder.
     *
     * @param Configuration $configuration
     *
     * @return DIContainerBuilder
     */
    private static function getContainerBuilder(Configuration $configuration): DIContainerBuilder
    {
        $containerBuilder = new DIContainerBuilder($configuration->getContainerClass());

        $containerBuilder->useAutowiring($configuration->doesUseAutowiring());
        $containerBuilder->useAnnotations($configuration->doesUseAnnotations());
        $containerBuilder->ignorePhpDocErrors($configuration->doesIgnorePhpDocErrors());

        if ($configuration->doesUseDefinitionCache()) {
            $containerBuilder->enableDefinitionCache();
        }

        if ($configuration->getWrapContainer() !== null) {
            $containerBuilder->wrapContainer($configuration->getWrapContainer());
        }

        if ($configuration->getProxiesPath() !== null) {
            $containerBuilder->writeProxiesToFile(true, $configuration->getProxiesPath());
        }

        if (!empty($configuration->getCompilationPath())) {
            $containerBuilder->enableCompilation(
                $configuration->getCompilationPath(),
                'CompiledContainer',
                $configuration->getCompiledContainerClass()
            );
        }

        return $containerBuilder;
    }

    /**
     * Parse definitions.
     *
     * @param array $definitions
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    private static function parseDefinitions(array $definitions): array
    {
        if (count($definitions) === 0) {
            return $definitions;
        }

        return array_map(
            function ($definition) {
                if (is_array($definition)) {
                    return $definition;
                }

                return self::loadDefinitionsFromPath($definition);
            },
            $definitions
        );
    }

    /**
     * Load definitions from path.
     *
     * @param string $path
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    private static function loadDefinitionsFromPath(string $path): array
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf('Path "%s" does not exist', $path));
        }

        if (!is_dir($path)) {
            return self::loadDefinitionsFromFile($path);
        }

        $definitions = [];
        foreach (glob($path . '/*.php', GLOB_ERR) as $file) {
            if (is_file($file)) {
                $definitions[] = self::loadDefinitionsFromFile($file);
            }
        }

        return count($definitions) === 0 ? [] : array_merge(...$definitions);
    }

    /**
     * Load definitions from file.
     *
     * @param string $file
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    private static function loadDefinitionsFromFile(string $file): array
    {
        if (!is_file($file) || !is_readable($file)) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException(sprintf('"%s" must be a readable file', $file));
            // @codeCoverageIgnoreEnd
        }

        $definitions = require $file;

        if (!is_array($definitions)) {
            throw new \RuntimeException(
                sprintf('Definitions file should return an array. "%s" returned', gettype($definitions))
            );
        }

        return $definitions;
    }
}
