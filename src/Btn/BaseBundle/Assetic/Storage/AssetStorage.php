<?php

namespace Btn\BaseBundle\Assetic\Storage;

use Assetic\Asset\AssetInterface;

class AssetStorage implements AssetStorageInterface
{
    /** @var array $assets */
    protected $assets = array();

    /**
     *
     */
    public function add($group, $key, AssetInterface $asset)
    {
        if (!$this->has($group)) {
            $this->assets[$group] = array();
        }

        $this->assets[$group][$key] = $asset;
    }

    /**
     *
     */
    public function has($group)
    {
        return isset($this->assets[$group]) ? true : false;
    }

    /**
     *
     */
    public function get($group)
    {
        if ($this->has($group)) {
            $assets = $this->assets[$group];

            unset($this->assets[$group]);

            return $assets;
        }
    }
}
