<?php

namespace Btn\BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension as BaseAbstractTypeExtension;
use Btn\BaseBundle\Assetic\Loader\AssetLoaderInterface;

abstract class AbstractTypeExtension extends BaseAbstractTypeExtension
{
    /** @var \Btn\BaseBundle\Assetic\Loader\AssetLoaderInterface $assetLoader */
    protected $assetLoader;

    /**
     *
     */
    public function setAssetLoader(AssetLoaderInterface $assetLoader)
    {
        $this->assetLoader = $assetLoader;

        return $this;
    }
}
