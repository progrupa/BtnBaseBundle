<?php

namespace Btn\BaseBundle\Assetic\Storage;

use Assetic\Asset\AssetInterface;

interface AssetStorageInterface
{
    public function add($group, $key, AssetInterface $asset);
    public function has($group);
    public function get($group);
}
