<?php

namespace Btn\BaseBundle\Factory;

use Symfony\Bundle\AsseticBundle\Factory\AssetFactory as BaseAssetFactory;

class AssetFactory extends BaseAssetFactory
{
    /** @var array $removeInputFiles */
    protected $removeInputFiles = array();

    /** @var array $replaceInputFiles */
    protected $replaceInputFiles = array();

    /** @var boolean $ensureCombine */
    protected $ensureCombine = true;

    /** @var boolean $skipMissingAssets */
    protected $skipMissingAssets = false;

    /**
     *
     */
    public function setRemoveInputFiles(array $removeInputFiles)
    {
        $this->removeInputFiles = $removeInputFiles;
    }

    /**
     *
     */
    public function getRemoveInputFiles()
    {
        return $this->replaceInputFiles;
    }

    /**
     *
     */
    public function setReplaceInputFiles(array $replaceInputFiles)
    {
        $this->replaceInputFiles = $replaceInputFiles;
    }

    /**
     *
     */
    public function getReplaceInputFiles()
    {
        return $this->removeInputFiles;
    }

    /**
     *
     */
    public function setEnsureCombine($ensureCombine)
    {
        $this->ensureCombine = (boolean) $ensureCombine;
    }

    /**
     *
     */
    public function getEnsureCombine()
    {
        return $this->ensureCombine;
    }

    /**
     *
     */
    public function setSkipMissingAssets($skipMissingAssets)
    {
        $this->skipMissingAssets = (boolean) $skipMissingAssets;
    }

    /**
     *
     */
    public function getSkipMissingAssets()
    {
        return $this->skipMissingAssets;
    }

    /**
     * {@inheritDoc}
     */
    public function createAsset($inputs = array(), $filters = array(), array $options = array())
    {
        // prevent trying to fetch not existing input asset files on production while generating twig cache
        if ($this->skipMissingAssets && 'cli' !== php_sapi_name() && !$this->isDebug()) {
            if (0 === count($filters) && !empty($options['output'])) {
                return parent::createAsset(array(), $filters, $options);
            }
        }

        // filter input based on config settings
        $filteredInputs = $this->filterInputs($inputs);

        if ($this->ensureCombine && empty($options['combine']) && array_diff($filteredInputs, $inputs)) {
            throw new \Exception('combine option is required to modify assetic input files');
        }

        return parent::createAsset($filteredInputs, $filters, $options);
    }

    /**
     *
     */
    protected function filterInputs(array $inputs)
    {
        // remove input files that should be remove
        if ($this->removeInputFiles) {
            foreach ($inputs as $key => $file) {
                if (in_array($file, $this->removeInputFiles)) {
                    unset($inputs[$key]);
                }
            }
        }

        // replace input files that should be replaced
        if ($this->replaceInputFiles) {
            foreach ($inputs as $key => $file) {
                if (isset($this->replaceInputFiles[$file]) ) {
                    $inputs[$key] = $this->replaceInputFiles[$file];
                }
            }
        }

        return array_values($inputs);
    }
}
