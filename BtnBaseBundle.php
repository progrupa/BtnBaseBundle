<?php

namespace Btn\BaseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Btn\BaseBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BtnBaseBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Compiler\AsseticCompilerPass());
    }
}
