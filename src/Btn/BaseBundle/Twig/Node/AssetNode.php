<?php

namespace Btn\BaseBundle\Twig\Node;

// use Symfony\Bundle\AsseticBundle\Twig\AsseticNode as BaseAsseticNode;

class AssetNode extends \Twig_Node
{
    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $assetUrlVar = $this->getAttribute('asset_url_var');
        $nameVar     = $this->getAttribute('asset_name_var');
        $asset       = $this->getAttribute('asset');

        if ($asset) {
            $compiler
                ->write('$context[')->repr($assetUrlVar)->raw('] = ')
                ->raw('$this->env->getExtension(\'assets\')->getAssetUrl(')
                ->string($asset->getTargetPath())
                ->raw(");\n")
            ;
        } elseif ($nameVar) {
            $compiler
                ->write('$context[')->repr($assetUrlVar)->raw('] = ')
                ->raw('$this->env->getExtension(\'assets\')->getAssetUrl(')
                ->raw('$context[')->repr($nameVar)->raw(']->getTargetPath()')
                ->raw(");\n")
            ;
        }

        $compiler->subcompile($this->getNode('body'));

        $compiler->addDebugInfo($this);

        $compiler->write('unset($context[')->repr($assetUrlVar)->raw("]);\n");
    }
}
