<?php

namespace Btn\BaseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Btn\BaseBundle\Controller\BaseController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class RestController extends BaseController
{
    /**
     * REST delete resource action
     *
     * @Route("/{id}")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $this->getManager()->remove($this->getRepository($this->resource)->find($id));
        $this->getManager()->flush();

        return $this->renderRest(array(
            'message' => $this->get('translator')->trans(ucfirst($this->resource) . ' with id ' . $id . ' deleted successfully!')
        ));
    }

    /**
     * REST index action
     * works with the ?page=2 request parameter
     * for more complicated things like filters etc. use overloading for index action
     *
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        //get short class name without namespace
        $function     = new \ReflectionClass($this->resource);
        $resourceName = strtolower($function->getShortName());

        //trigger pagination
        $paginator    = $this->getPaginator('btn.' . $resourceName . '_manager', 2);

        return $this->renderRest(array('results' => $paginator->getItems()));
    }

    /**
     * REST show action
     *
     * @Route("/{id}")
     * @Method({"GET"})
     */
    public function showAction($id)
    {
        return new Response(
            $this->get('serializer')->serialize(
                $this->getRepository($this->resource)->find($id), 'json'
            )
        );
    }

    /**
     * REST create, edit, update action
     *
     * @Route("/{id}", defaults={"id" = null})
     * @Method({"POST", "PUT", "PATCH"})
     */
    public function operateAction($id = NULL)
    {
        //resolve entity and form
        $entity   = $id ? $this->getRepository($this->resource)->find($id) : (new $this->resource());
        $formName = $id ? $this->editForm : $this->createForm;
        $form = $this->createForm(
            new $formName, $entity
        );

        //run validation
        $validation = $this->get('btn.rest_form')->validateRequest(
            $this->getRequest(),
            $entity,
            $form,
            $id
        );

        /* if no entity, $id was wrong, or entity doesn't exists */
        if ($id != NULL && $validation['entity'] == NULL) {
            return $this->renderRest(
                array('message' => 'No ' . $this->resource . ' with id ' . $id), 400);
        }

        /* validation goes wrong, return errors */
        if (!$validation['isValid']) {
            return $this->renderRest($validation, 400);
        }

        /* do some custom actions, like setting current user */
        $this->callCustomActions($this, $entity);

        //save changes
        $this->getManager()->persist($validation['entity']);
        $this->getManager()->flush();

        return $this->renderRest($validation['entity']);
    }

    /**
     * Helper method for custom callback to main controller before entity is flushed
     *
     * @param  Controller $controller
     * @param  Entity     $entity
     * @return void
     **/
    private function callCustomActions(&$controller, &$entity)
    {
        if (property_exists($controller, 'customCallbacks') && is_array($controller->customCallbacks)) {
            foreach ($controller->customCallbacks as $action) {
                if (method_exists($this, $action)) {
                    call_user_func_array(array($this, $action), array(&$this, &$entity));
                }
            }
        }
    }
}