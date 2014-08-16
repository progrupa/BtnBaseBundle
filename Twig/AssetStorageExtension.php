<?php

namespace Btn\BaseBundle\Twig;

use Btn\BaseBundle\Assetic\Storage\AssetStorageInterface;

    class AssetStorageExtension extends \Twig_Extension
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
            'btn_get_assets' => new \Twig_Function_Method($this, 'getAssets'),
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
    public function getName()
    {
        return 'btn_base.asset_storage_extension';
    }
}
