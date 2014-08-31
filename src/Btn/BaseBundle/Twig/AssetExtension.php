<?php

namespace Btn\BaseBundle\Twig;

use Btn\BaseBundle\Assetic\Storage\AssetStorageInterface;
use Btn\BaseBundle\Assetic\Manager\AssetManager;
use Btn\BaseBundle\Assetic\Loader\AssetLoaderInterface;
use Btn\BaseBundle\Twig\TokenParser\AssetTokenParser;

class AssetExtension extends \Twig_Extension
{
    /** @var \Btn\BaseBundle\Assetic\Storage\AssetStorageInterface $storage */
    protected $storage;
    /** @var \Btn\BaseBundle\Assetic\Manager\AssetManager $manager */
    protected $manager;
    /** @var \Btn\BaseBundle\Assetic\Loader\AssetLoaderInterface $loader */
    protected $loader;

    /**
     *
     */
    public function __construct(AssetStorageInterface $storage, AssetManager $manager, AssetLoaderInterface $loader)
    {
        $this->storage = $storage;
        $this->manager = $manager;
        $this->loader  = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            new AssetTokenParser($this->manager),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'btn_get_assets'    => new \Twig_Function_Method($this, 'getAssets'),
            'btn_request_asset' => new \Twig_Function_Method($this, 'requestAsset'),
            'btn_load_assets'   => new \Twig_Function_Method($this, 'loadAssets'),
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
    public function requestAsset($group, $asset)
    {
        $this->loader->requset($group, $asset);
    }

    /**
     *
     */
    public function loadAssets($assets)
    {
        $this->loader->load($assets);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'btn_base.extension.asset';
    }
}
