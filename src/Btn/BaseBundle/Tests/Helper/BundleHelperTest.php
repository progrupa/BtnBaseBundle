<?php

namespace Btn\BaseBundle\Tests\Helper;

use Btn\BaseBundle\Helper\BundleHelper;
use Btn\BaseBundle\Controller\RedirectingController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BundleHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Btn\BaseBundle\Helper\BundleHelper $helper */
    protected $helper;
    /** @var \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller */
    protected $controller;
    /** @var \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller */
    protected $redirectController;

    /**
     *
     */
    protected function setUp()
    {
        $this->helper             = new BundleHelper();
        $this->controller         = new Controller();
        $this->redirectController = new RedirectingController();
    }

    /**
     *
     */
    public function testGetReflectionClass()
    {
        $controllerRefClass       = $this->helper->getReflectionClass($this->controller);
        $cachedControllerRefClass = $this->helper->getReflectionClass($this->controller);
        $redControllerRefClass    = $this->helper->getReflectionClass($this->redirectController);

        $this->assertTrue($controllerRefClass instanceof \ReflectionClass);
        $this->assertTrue($redControllerRefClass instanceof \ReflectionClass);
        $this->assertNotEquals($controllerRefClass, $redControllerRefClass);
        $this->assertSame($controllerRefClass, $cachedControllerRefClass);
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetClassName(array $input)
    {
        if (isset($input['className'])) {
            $className = $this->helper->getClassName($input['class']);
            $this->assertEquals($input['className'], $className);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetBundlePath(array $input)
    {
        if (isset($input['bundlePath'])) {
            $bundlePath = $this->helper->getBundlePath($input['class']);
            $this->assertEquals($input['bundlePath'], $bundlePath);
        }
    }

    /**
     *
     */
    public function testGetBundlePathException()
    {
        try {
            $this->helper->getBundlePath('SomeRandomName');
            $this->assertFalse(true, 'Should throw exception');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetBundleName(array $input)
    {
        if (isset($input['bundleName'])) {
            $bundleName = $this->helper->getBundleName($input['class']);
            $this->assertEquals($input['bundleName'], $bundleName);
        }
    }

    /**
     *
     */
    public function testGetBundleNameException()
    {
        try {
            $this->helper->getBundleName('SomeRandomName');
            $this->assertFalse(true, 'Should throw exception');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetBundleAlias(array $input)
    {
        if (isset($input['bundleAlias'])) {
            $bundleAlias = $this->helper->getBundleAlias($input['class']);
            $this->assertEquals($input['bundleAlias'], $bundleAlias);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetControllerName(array $input)
    {
        if (isset($input['controllerName'])) {
            $controllerName = $this->helper->getControllerName($input['class']);
            $this->assertEquals($input['controllerName'], $controllerName);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetUnderscoreControllerName(array $input)
    {
        if (isset($input['controller_name'])) {
            $controller_name = $this->helper->getUnderscoreControllerName($input['class']);
            $this->assertEquals($input['controller_name'], $controller_name);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetTemplatePrefix(array $input)
    {
        if (isset($input['templatePrefix'])) {
            $templatePrefix = $this->helper->getTemplatePrefix($input['class']);
            $this->assertEquals($input['templatePrefix'], $templatePrefix);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetRoutePrefix(array $input)
    {
        if (isset($input['routePrefix'])) {
            $routePrefix = $this->helper->getRoutePrefix($input['class']);
            $this->assertEquals($input['routePrefix'], $routePrefix);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetProviderId(array $input)
    {
        if (isset($input['providerId'])) {
            $providerId = $this->helper->getProviderId($input['class']);
            $this->assertEquals($input['providerId'], $providerId);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetFormId(array $input)
    {
        if (isset($input['formId'])) {
            $formId = $this->helper->getFormId($input['class']);
            $this->assertEquals($input['formId'], $formId);
        }
    }

    /**
     * @dataProvider getFixtures
     */
    public function testGetFormAlias(array $input)
    {
        if (isset($input['formAlias'])) {
            $formAlias = $this->helper->getFormAlias($input['class']);
            $this->assertEquals($input['formAlias'], $formAlias);
        }
    }

    /**
     *
     */
    public function getFixtures()
    {
        return array(
            array(
                array(
                    'class'           => new RedirectingController(),
                    'className'       => 'Btn\\BaseBundle\\Controller\\RedirectingController',
                    'bundlePath'      => 'Btn\\BaseBundle',
                    'bundleName'      => 'BtnBaseBundle',
                    'bundleAlias'     => 'btn_base',
                    'controllerName'  => 'Redirecting',
                    'controller_name' => 'redirecting',
                    'templatePrefix'  => 'BtnBaseBundle:Redirecting:',
                    'routePrefix'     => 'btn_base_redirecting',
                ),
            ),
            array(
                array(
                    'class'           => new Controller(),
                    'className'       => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\Controller',
                    'bundlePath'      => 'Symfony\\Bundle\\FrameworkBundle',
                    'bundleName'      => 'FrameworkBundle',
                    'bundleAlias'     => 'framework',
                ),
            ),
            array(
                array(
                    'class'           => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController',
                    'className'       => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController',
                    'bundlePath'      => 'Symfony\\Bundle\\TwigBundle',
                    'bundleName'      => 'TwigBundle',
                    'bundleAlias'     => 'twig',
                    'controllerName'  => 'Exception',
                    'controller_name' => 'exception',
                    'templatePrefix'  => 'TwigBundle:Exception:',
                    'routePrefix'     => 'twig_exception',
                ),
            ),
            array(
                array(
                    'class'           => 'Btn\\Some\\Dir\\SimpleBundle\\Controller\\SomeController',
                    'className'       => 'Btn\\Some\\Dir\\SimpleBundle\\Controller\\SomeController',
                    'bundlePath'      => 'Btn\\Some\\Dir\\SimpleBundle',
                    'bundleName'      => 'BtnSomeDirSimpleBundle',
                    'bundleAlias'     => 'btn_some_dir_simple',
                    'controllerName'  => 'Some',
                    'controller_name' => 'some',
                    'templatePrefix'  => 'BtnSomeDirSimpleBundle:Some:',
                    'routePrefix'     => 'btn_some_dir_simple_some',
                ),
            ),
            array(
                array(
                    'class'           => 'Btn\\Some\\Dir\\CamelCaseBundle\\Controller\\SomeControlController',
                    'className'       => 'Btn\\Some\\Dir\\CamelCaseBundle\\Controller\\SomeControlController',
                    'bundlePath'      => 'Btn\\Some\Dir\\CamelCaseBundle',
                    'bundleName'      => 'BtnSomeDirCamelCaseBundle',
                    'bundleAlias'     => 'btn_some_dir_camelcase',
                    'controllerName'  => 'SomeControl',
                    'controller_name' => 'some_control',
                    'templatePrefix'  => 'BtnSomeDirCamelCaseBundle:SomeControl:',
                    'routePrefix'     => 'btn_some_dir_camelcase_somecontrol',
                    'providerId'      => 'btn_some_dir_camelcase.provider.some',
                    'formId'          => 'btn_some_dir_camelcase.form.some_control',
                    'formAlias'       => 'btn_some_dir_camelcase_form_some_control',
                ),
            ),
        );
    }
}
