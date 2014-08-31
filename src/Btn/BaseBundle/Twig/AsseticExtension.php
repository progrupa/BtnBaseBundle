<?php

namespace Btn\BaseBundle\Twig;

use Btn\BaseBundle\Assetic\Storage\AssetStorageInterface;
use Btn\BaseBundle\Twig\TokenParser\AsseticTokenParser;

class AsseticExtension extends \Twig_Extension
{
    /** @var \Btn\BaseBundle\Storage\AssetStorageInterface $storage */
    protected $storage;

    /**
     *
     */
    public function __construct(AssetStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            new AsseticTokenParser($this->factory, $this->storage),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'btn_get_assets' => new \Twig_Function_Method($this, 'getAssets'),
        );
    }

    /**
     *
     */
    public function getAssets($group)
    {
        return $this->storage->get($group);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'btn_base.extension.assetic';
    }
}
