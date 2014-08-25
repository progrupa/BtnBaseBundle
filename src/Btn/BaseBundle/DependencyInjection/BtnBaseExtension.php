<?php

namespace Btn\BaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class BtnBaseExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);

        $config = $this->getProcessedConfig($container, $configs);

        $container->setParameter('btn_base.livereload.enabled', $config['livereload']['enabled']);
        $container->setParameter('btn_base.livereload.host', $config['livereload']['host']);
        $container->setParameter('btn_base.livereload.port', $config['livereload']['port']);
        $container->setParameter('btn_base.doctrine.table.options', $config['doctrine']['table']['options']);

        $container->setParameter('btn_base.assetic.remove_input_files', $config['assetic']['remove_input_files']);
        $container->setParameter('btn_base.assetic.replace_input_files', $config['assetic']['replace_input_files']);
        $container->setParameter('btn_base.assetic.ensure_combine', $config['assetic']['ensure_combine']);
        $container->setParameter('btn_base.assetic.skip_missing_assets', $config['assetic']['skip_missing_assets']);
    }
}
