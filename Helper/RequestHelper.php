<?php

namespace BtnBaseBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{
    /**
     *
     */
    public static function isControlRequest(Request $request)
    {
        $isControl = (0 === strpos($request->getPathInfo(), '/control/')) ? true : false;

        return $isControl;
    }

    /**
     *
     */
    public static function isAsseticRequest(Request $request)
    {
        $attr = $request->attributes;
        $isAssetic = ($attr->get('_controller') === 'assetic.controller:render' || substr($attr->get('_route'), 0, 8) === '_assetic') ? true : false;

        return $isAssetic;
    }

    /**
     *
     */
    public static function  isToolbarRequest(Request $request)
    {
        $attr = $request->attributes;
        $isToolbar = ($attr->get('_controller') == 'web_profiler.controller.profiler:toolbarAction' || $attr->get('_route') == '_wdt') ? true : false;

        return $isToolbar;
    }

    /**
     *
     */
    public static function isProfilerRequest(Request $request)
    {
        $attr = $request->attributes;
        $isProfiler = ($attr->get('_controller') == 'web_profiler.controller.profiler:panelAction' || $attr->get('_route') == '_profiler') ? true : false;

        return $isProfiler;
    }
}
