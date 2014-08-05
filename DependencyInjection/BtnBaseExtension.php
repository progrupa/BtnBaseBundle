<?php

namespace Btn\BaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BtnBaseExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('btn_base', array());

        $container->setParameter('btn_base.livereload_port', isset($config['livereload_port']) ? $config['livereload_port'] : 35729);
        $container->setParameter('btn_base.livereload_enabled', isset($config['livereload_enabled']) ? $config['livereload_enabled'] : false);
        $container->setParameter('btn_base.doctrine', isset($config['doctrine']) ? $config['doctrine'] : array());

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
