<?php

namespace Btn\BaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('btn_base');
        $rootNode
            ->children()
                ->arrayNode('livereload')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultValue(false)->end()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->integerNode('port')->defaultValue(35729)->end()
                    ->end()
                ->end()

                ->arrayNode('assetic')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('auto_register_bundles')->defaultValue(false)->end()
                        ->booleanNode('ensure_combine')->info('')->defaultValue(true)->end()
                        ->booleanNode('skip_missing_assets')->defaultValue(false)->end()
                        ->arrayNode('remove_input_files')
                            ->defaultValue(array())
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('replace_input_files')
                            ->defaultValue(array())
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('doctrine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('table')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('options')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('collate')->defaultValue('utf8_general_ci')->end()
                                        ->scalarNode('charset')->defaultValue('utf8')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
