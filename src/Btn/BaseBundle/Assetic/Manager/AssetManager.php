<?php

namespace Btn\BaseBundle\Assetic\Manager;

use Assetic\Factory\LazyAssetManager;
use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;

class AssetManager extends LazyAssetManager
{
    /** @var array $loaders */
    private $loaders = array();
    /** @var array $resources */
    private $resources = array();

    /**
     * {@inheritdoc}
     */
    public function setLoader($alias, FormulaLoaderInterface $loader)
    {
        parent::setLoader($alias, $loader);

        $this->loaders[$alias] = $loader;
    }

    /**
     *
     */
    public function hasLoader($alias)
    {
        return isset($this->loaders[$alias]) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function addResource(ResourceInterface $resource, $loader)
    {
        if ($this->hasLoader($loader)) {
            parent::addResource($resource, $loader);

            $this->resources[$loader][] = $resource;
        }
    }
}
