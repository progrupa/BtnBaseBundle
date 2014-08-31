<?php

namespace Btn\BaseBundle\Twig\TokenParser;

use Btn\BaseBundle\Twig\Node\AsseticNode;

class AsseticTokenParser extends \Twig_TokenParser
{
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
            'asset_var'      => 'asset_url',
            'asset_name_var' => null,
        );

        $attributes['asset_name_var'] = $stream->expect(\Twig_Token::NAME_TYPE)->getValue();

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $nodes['body'] = $parser->subparse(array($this, 'testEndTag'), true);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new AsseticNode($nodes, $attributes, $token->getLine(), $this->getTag());
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
