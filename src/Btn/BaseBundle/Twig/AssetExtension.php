<?php

namespace Btn\BaseBundle\Twig;

use Btn\BaseBundle\Assetic\Storage\AssetStorageInterface;
use Assetic\Asset\AssetInterface;

class AssetExtension extends \Twig_Extension
{
    /** @var \Btn\BaseBundle\Storage\AssetStorageInterface $storage */
    protected $storage;

    /**
     *
     */
    public function __construct(AssetStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     *
     */
    public function getFunctions()
    {
        return array(
            'btn_get_assets'    => new \Twig_Function_Method($this, 'getAssets'),
            'btn_get_asset_src' => new \Twig_Function_Method($this, 'getSrc'),
        );
    }

    /**
     *
     */
    public function getAssets($group)
    {
        return $this->storage->get($group);
    }

    /**
     *
     */
    public function getSrc(AssetInterface $asset)
    {
        return $asset->getTargetPath();
    }

    /**
     *
     */
    public function getName()
    {
        return 'btn_base.extension.asset';
    }
}
