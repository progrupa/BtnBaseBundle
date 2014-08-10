<?php

namespace Btn\BaseBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * PreExecute
 *
 * add preExecute functionality to the controllers
 */
class PreExecuteListener
{
    /** @var \ControllerResolverInterface $resolver */
    private $resolver;

    /**
     *
     */
    public function __construct(ControllerResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     *
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $controllers = $event->getController();
        if (is_array($controllers)) {
            $controller = $controllers[0];
            //call preExecute if exists
            if (is_object($controller) && method_exists($controller, 'preExecute')) {
                $controller->preExecute($event, $this->resolver);
            }
        }
    }
}
