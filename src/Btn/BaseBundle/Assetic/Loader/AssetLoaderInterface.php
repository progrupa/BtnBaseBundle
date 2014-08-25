<?php

namespace Btn\BaseBundle\Assetic\Loader;

interface AssetLoaderInterface
{
    public function requset($group, $asset);
    public function load($assets);
    public function has($asset);
}
