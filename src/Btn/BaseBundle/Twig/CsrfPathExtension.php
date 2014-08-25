<?php

namespace Btn\BaseBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;

/**
 *
 */
class CsrfPathExtension extends \Twig_Extension
{
    /** @var \Symfony\Component\Routing\UrlGeneratorInterface */
    protected $router;
    /** @var \Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface */
    protected $csrfProvider;

    /**
     *
     */
    public function __construct(UrlGeneratorInterface $router, CsrfProviderInterface $csrfProvider)
    {
        $this->router       = $router;
        $this->csrfProvider = $csrfProvider;
    }

    /**
     *
     */
    public function getFunctions()
    {
        return array(
            'btn_csrf_path' => new \Twig_Function_Method($this, 'csrfPath'),
            'csrf_path'     => new \Twig_Function_Method($this, 'csrfPath'),
        );
    }

    /**
     *
     */
    public function csrfPath($name, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if (empty($parameters['csrf_token'])) {
            $parameters['csrf_token'] = $this->csrfProvider->generateCsrfToken($name);
        }

        return $this->router->generate($name, $parameters, $referenceType);
    }

    /**
     *
     */
    public function getName()
    {
        return 'btn_base.extension.csrf_path';
    }
}
