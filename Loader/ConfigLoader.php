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
    public function loadFromArray(array $fileArray, $type = null)
    {
        foreach ($fileArray as $file) {
            $this->load($file);
        }
    }
}
