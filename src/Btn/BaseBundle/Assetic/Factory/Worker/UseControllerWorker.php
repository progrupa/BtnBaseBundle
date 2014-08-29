<?php

namespace Btn\BaseBundle\Assetic\Factory\Worker;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\Worker\WorkerInterface;

class UseControllerWorker implements WorkerInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(AssetInterface $asset)
    {
        $asset->setTargetPath(str_replace('_controller/', '', $asset->getTargetPath()));

        return $asset;
    }
}
