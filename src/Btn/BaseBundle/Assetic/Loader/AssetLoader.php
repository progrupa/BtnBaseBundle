<?php

namespace Btn\BaseBundle\Assetic\Loader;

use Btn\BaseBundle\Assetic\Storage\AssetStorageInterface;
use Assetic\AssetManager;

class AssetLoader implements AssetLoaderInterface
{
    /** @var \Btn\BaseBundle\Assetic\Storage\AssetStorageInterface $storage */
    protected $storage;

    /** @var \Assetic\AssetManager $manager */
    protected $manager;

    /**
     *
     */
    public function __construct(AssetStorageInterface $storage, AssetManager $manager)
    {
        $this->storage = $storage;
        $this->manager = $manager;
    }

    /**
     *
     */
    public function request($group, $asset)
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
                if ('_js' === substr($asset, -3)) {
                    $this->request('javascripts', $asset);
                } elseif ('_css' === substr($asset, -4)) {
                    $this->request('stylesheets', $asset);
                } else {
                    // try to load by prefix
                    if ($this->has($asset.'_css')) {
                        $this->request('stylesheets', $asset.'_css');
                    }
                    if ($this->has($asset.'_js')) {
                        $this->request('javascripts', $asset.'_js');
                    }
                }
            } else {
                $this->request($group, $asset);
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
