<?php

namespace Btn\BaseBundle\Assetic\Storage;

interface AssetStorageInterface
{
    public function add($group, $asset);
    public function has($group);
    public function get($group);
}
