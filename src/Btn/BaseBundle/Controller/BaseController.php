<?php

namespace Btn\BaseBundle\Controller;

/**
 * @deprecated Deprecated in favore of AbstractController
 */
class BaseController extends AbstractController
{
    public function __construct()
    {
        trigger_error(
            'BaseController is deprecated. Use AbstractController.',
            E_USER_DEPRECATED
        );
    }
}
