<?php

namespace Btn\BaseBundle\Assetic\Loader;

use Btn\BaseBundle\Assetic\Storage\AssetStorageInterface;

class AssetLoader implements AssetLoaderInterface
{
    /** @var \Btn\BaseBundle\Assetic\Storage\AssetStorageInterface $storage */
    protected $storage;

    /** @var \Assetic\AssetManager $manager */
    protected $manager;

    public function __construct(AssetStorageInterface $storage, \Assetic\AssetManager $manager)
    {
        $this->storage = $storage;
        $this->manager = $manager;
    }

    /**
     *
     */
    public function requset($group, $asset)
    {
        if (!$asset) {
            throw new \Exception('Asset key must be defined');
        }

        if (!$group) {
            throw new \Exception('Asset group must be defined');
        }

        if (!$this->has($asset)) {
            throw new \Exception(sprintf('Asset "%s" was not found in manager', $asset));
        }

        $this->storage->add($group, $asset, $this->manager->get($asset));
    }

    /**
     *
     */
    public function load($assets)
    {
        if (is_string($assets)) {
            $assets = array($assets);
        }

        foreach ($assets as $asset => $group) {
            // switch places if group is not defined
            if (is_int($asset)) {
                $asset = $group;
                $group = null;
            }
            // if group is null then try to autoload css, js sufixes
            if (null === $group) {
                if ($this->has($asset . '_css')) {
                    $this->requset('stylesheets', $asset.'_css');
                }
                if ($this->has($asset . '_js')) {
                    $this->requset('javascripts', $asset.'_js');
                }
            } else {
                $this->requset($group, $asset);
            }
        }
    }

    /**
     *
     */
    public function has($asset)
    {
        return $this->manager->has($asset) ? true : false;
    }
}
