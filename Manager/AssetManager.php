<?php

namespace Btn\BaseBundle\Manager;

class AssetManager implements AssetManagerInterface
{
    /** $manager \Assetic\AssetManager $manager */
    protected $manager;

    /** @var array $assets */
    protected $assets = array();

    /**
     *
     */
    public function __construct(\Assetic\AssetManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     *
     */
    public function requset($asset, $group)
    {
        if (!$asset) {
            throw new \Exception('Asset name must be defined');
        }

        if (!$group) {
            throw new Exception('Asset group must be defined');
        }

        if ($this->hasGroup($group)) {
            $this->assets[$group] = array();
        }

        $this->assets[$group][$asset] = $asset;
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
                if ($this->hasAsset($asset . '_css')) {
                    $this->requset($asset.'_css', 'stylesheets');
                }
                if ($this->hasAsset($asset . '_js')) {
                    $this->requset($asset.'_js', 'javascripts');
                }
            } else {
                if ($this->hasAsset($asset)) {
                    $this->requset($asset, $group);
                } else {
                    throw new \Exception(sprintf('Asset "%s" was not found in manager', $asset));
                }
            }
        }
    }

    /**
     *
     */
    public function hasGroup($group)
    {
        return isset($this->assets[$group]) ? true : false;
    }

    /**
     *
     */
    public function hasAsset($asset)
    {
        return $this->manager->has($asset) ? true : false;
    }

    /**
     *
     */
    public function getAssets($group)
    {
        if ($this->has($group)) {
            $assets = $this->assets[$group];

            unset($this->assets[$group]);

            return $assets;
        }
    }
}
