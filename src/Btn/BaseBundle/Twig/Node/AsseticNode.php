<?php

namespace Btn\BaseBundle\Twig\Node;

// use Symfony\Bundle\AsseticBundle\Twig\AsseticNode as BaseAsseticNode;

class AsseticNode extends \Twig_Node
{
    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $nameVar = $this->getAttribute('asset_name_var');

        $compiler
            ->write('$context[')
            ->repr($this->getAttribute('asset_var'))
            ->raw('] = ')
                ->raw('$this->env->getExtension(\'assets\')->getAssetUrl(')
                    ->raw('$context[')->repr($nameVar)->raw(']->getTargetPath()')
                ->raw(')')
            ->raw(";\n")
        ;

        $compiler->subcompile($this->getNode('body'));

        $compiler->addDebugInfo($this);

        $compiler
            ->write('unset($context[')
            ->repr($this->getAttribute('asset_var'))
            ->raw("]);\n")
        ;
    }
}
