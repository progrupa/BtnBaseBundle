<?php

namespace Btn\BaseBundle\Loader;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ConfigLoader extends YamlFileLoader
{
    /**
     *
     */
    public function load($file, $type = null)
    {
        $ext = substr($file, strrpos($file, '.') + 1);

        if ('yml' !== $ext) {
            $file = $file .= '.yml';
        }

        return parent::load($file);
    }

    /**
     *
     */
    public function tryLoad($file, $type = null)
    {
        try {
            return $this->load($file, $type);
        } catch (\InvalidArgumentException $e) {
            // silently got through if file could not be loaded
        }
    }

    /**
     *
     */
    public function tryLoadFromArray(array $fileArray, $type = null)
    {
        foreach ($fileArray as $file) {
            $this->tryLoad($file);
        }
    }

    /**
     *
     */
    public function tryLoadForExtension($extension, $file = null, $type = null) {
        if ($this->container->hasExtension($extension)) {
            if (null === $file) {
                $file = $extension;
            }
            return $this->tryLoad($file, $type);
        }
    }
}
