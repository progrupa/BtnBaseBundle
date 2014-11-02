<?php

namespace Btn\BaseBundle\Helper;

use Symfony\Component\DependencyInjection\Container;

class BundleHelper
{
    /** @var \ReflectionClass[] $reflectionClasses */
    protected $reflectionClasses = array();

    /**
     *
     */
    public function getReflectionClass($class)
    {
        $hash = spl_object_hash($class);
        if (!isset($this->reflectionClasses[$hash])) {
            $this->reflectionClasses[$hash] = new \ReflectionClass($class);
        }

        return $this->reflectionClasses[$hash];
    }

    /**
     *
     */
    public function getClassName($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return $class;
    }

    /**
     *
     */
    public function getBundlePath($class)
    {
        $className = $this->getClassName($class);

        if (preg_match(('~[A-Za-z\\\\0-9]+Bundle~'), $className, $matches)) {
            $bundlePath = $matches[0];

            return $bundlePath;
        }

        throw new \Exception(sprintf('Could not get bundle path from "%s"', $className));
    }

    /**
     *
     */
    public function getBundleName($class)
    {
        $bundlePath = $this->getBundlePath($class);

        $bundleName = $bundlePath;
        $bundleName = str_replace('\\', '', $bundleName);
        $bundleName = preg_replace('~^SymfonyBundle~', '', $bundleName);

        return $bundleName;
    }

    /**
     *
     */
    public function getBundleAlias($class)
    {
        $bundleName = $this->getBundleName($class);
        $bundlePath = $this->getBundlePath($class);

        $bundleAlias = substr($bundlePath, 0, -6);
        $bundleAlias = str_replace('\\', '_', $bundleAlias);
        $bundleAlias = Container::underscore($bundleAlias);
        $bundleAlias = str_replace('_', '', $bundleAlias);
        $bundleAlias = str_replace('.', '_', $bundleAlias);
        $bundleAlias = preg_replace('~^symfony\_bundle\_~', '', $bundleAlias);

        return $bundleAlias;
    }

    /**
     *
     */
    public function getControllerName($class)
    {
        $className = $this->getClassName($class);

        if (preg_match(('~\\\\([A-Za-z0-9]+)Controller$~'), $className, $matches)) {
            return $matches[1];
        }

        throw new \Exception(sprintf('Could not get controller name from "%s"', $className));
    }

    /**
     *
     */
    public function getUnderscoreControllerName($class)
    {
        return Container::underscore($this->getControllerName($class));
    }

    /**
     *
     */
    public function getTemplatePrefix($class)
    {
        $className      = $this->getClassName($class);
        $bundleName     = $this->getBundleName($className);
        $controllerName = $this->getControllerName($className);

        return $bundleName.':'.$controllerName.':';
    }

    /**
     *
     */
    public function getRoutePrefix($class)
    {
        $bundleAlias    = $this->getBundleAlias($class);
        $controllerName = $this->getControllerName($class);

        return $bundleAlias.'_'.strtolower($controllerName);
    }

    /**
     *
     */
    public function getProviderId($class)
    {
        $className = $this->getClassName($class);

        if (preg_match(('~\\\\([A-Za-z0-9]+)ControlController$~'), $className, $matches)) {
            return $this->getBundleAlias($class).'.provider.'.Container::underscore($matches[1]);
        }

        throw new \Exception(sprintf('Could not get provider id from "%s"', $className));
    }

    /**
     *
     */
    public function getFormId($class)
    {
        $bundleAlias    = $this->getBundleAlias($class);
        $controllerName = $this->getControllerName($class);

        return $bundleAlias.'.form.'.Container::underscore($controllerName);
    }

    /**
     *
     */
    public function getFormAlias($class)
    {
        return str_replace('.', '_', $this->getFormId($class));
    }
}
