<?php

namespace Btn\BaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Btn\BaseBundle\Loader\ConfigLoader;

/**
 *
 */
abstract class AbstractExtension extends Extension implements PrependExtensionInterface
{
    /** @var \ReflectionClass $reflectionClass of \Btn\BaseBundle\DependencyInjection\AbstractExtension */
    protected $reflectionClass;

    /** @var array $resourceDir common bundle config directory */
    protected $resourceDir = '/../Resources/config';

    /** @var array $bundleConfigFiles common bundle config files to autoload */
    protected $bundleConfigFiles = array(
        'parameters',
        'services',
        'menus',
        'forms',
    );

    /** @var array $bundleConfigFiles common extensions config files to autoload */
    protected $commonExtensionsConfigFiles = array(
        'assetic',
    );

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // try to load bitnoise standard common bundle config files
        try {
            $loader = $this->getConfigLoader($container);
            $loader->loadFromArray($this->bundleConfigFiles);
        } catch (\InvalidArgumentException $e) {
            // silently got through if file could not be loaded
        }
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // try to load bitnoise standard common other extensions config files
        try {
            $loader = $this->getConfigLoader($container);
            $loader->loadFromArray($this->commonExtensionsConfigFiles);
        } catch (\InvalidArgumentException $e) {
            // silently got through if file could not be loaded
        }
    }

    /**
     *
     */
    protected function getConfigLoader(ContainerBuilder $container, $rootDir = null, $resourceDir = null)
    {
        if (null === $rootDir) {
            $fileName = $this->getReflectionClass()->getFileName();
            $rootDir = substr($fileName, 0, strrpos($fileName, DIRECTORY_SEPARATOR));
        }

        if (null === $resourceDir) {
            $resourceDir = $this->resourceDir;
        }

        $loader = new ConfigLoader($container, new FileLocator($rootDir . $this->resourceDir));

        return $loader;
    }

    /**
     * @todo  make alias parameter work
     */
    protected function getProcessedConfig(ContainerBuilder $container, array $configs = null, $alias = null)
    {
        if (null === $alias) {
            $alias = $this->getAlias();
        }

        if (null === $configs) {
            $configs = $container->getExtensionConfig($alias);
        }

        $fakeConfigArray = array();
        $configuration = $this->getConfiguration($fakeConfigArray, $container);
        $config = $this->processConfiguration($configuration, $configs);

        return $config;
    }
}
