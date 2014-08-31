<?php

namespace Btn\BaseBundle\Twig\TokenParser;

use Btn\BaseBundle\Assetic\Manager\AssetManager;
use Btn\BaseBundle\Twig\Node\AssetNode;

class AssetTokenParser extends \Twig_TokenParser
{
    /** @var \Btn\BaseBundle\Assetic\Manager\AssetManager $manager */
    protected $manager;

    /**
     *
     */
    public function __construct(AssetManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $nodes = array(
            'body' => null,
        );

        $attributes = array(
            'asset_url_var'  => 'asset_url',
            'asset'          => null,
            'asset_name'     => null,
            'asset_name_var' => null,
        );

        if ($stream->test(\Twig_Token::STRING_TYPE)) {
            $attributes['asset_name'] = ltrim($stream->expect(\Twig_Token::STRING_TYPE)->getValue(), '@');
            $attributes['asset'] = $this->manager->get($attributes['asset_name']);
        }

        if ($stream->test(\Twig_Token::NAME_TYPE)) {
            $attributes['asset_name_var'] = $stream->expect(\Twig_Token::NAME_TYPE)->getValue();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $nodes['body'] = $parser->subparse(array($this, 'testEndTag'), true);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new AssetNode($nodes, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'btn_asset';
    }

    /**
     *
     */
    public function testEndTag(\Twig_Token $token)
    {
        return $token->test(array('end'.$this->getTag()));
    }
}
