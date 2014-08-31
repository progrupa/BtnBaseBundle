<?php

namespace Btn\BaseBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AssetManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('btn_base.assetic.manager.asset')) {
            return;
        }

        $assetManager = $container->getDefinition('btn_base.assetic.manager.asset');

        // add resources
        foreach ($container->findTaggedServiceIds('assetic.formula_resource') as $id => $attributes) {
            foreach ($attributes as $attr) {
                if (isset($attr['loader'])) {
                    $assetManager->addMethodCall('addResource', array(new Reference($id), $attr['loader']));
                }
            }
        }
    }
}
