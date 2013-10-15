<?php

namespace Btn\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * Set flash message
     *
     * @param string $type
     * @param string $message
     *
     */
    public function setFlash($message = 'success!', $type = 'success')
    {
        $this->getRequest()->getSession()->getFlashBag()->set($type, $message);
    }

    /**
     * Renders JSON for our View wrapper.
     *
     * @param array    $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function renderJson($content = '', $verdict = 'success', $custom = array())
    {
        return $this->container->get('btn.templating')->renderJson($content, $verdict, $custom);
    }

    /**
     * Renders array into JSON.
     *
     * @param array    $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function json(array $array, Response $response = null)
    {
        return $this->container->get('btn.templating')->json($array, $response);
    }

    /**
     * Shortcut to return Doctrine EntityManager service.
     *
     * @param string $name
     *
     * @return EntityManager
     */
    public function getManager($name = null)
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * Shortcut to return Doctrine Entity Repository.
     *
     * @param string $repositoryName
     * @param string $managerName
     *
     * @return EntityRepository
     */
    public function getRepository($repositoryName, $managerName = null)
    {
        return $this->getManager($managerName)->getRepository($repositoryName);
    }

    /**
     * Shortcut to find an entity by id
     *
     * @param string $class
     * @param mixed  $id
     * @param string $managerName
     *
     * @return object or NULL if the entity was not found
     */
    public function findEntity($class, $id = null, $managerName = null)
    {
        return $id ? $this->getRepository($class, $managerName)->find($id) : null;
    }

    /**
     * Shortcut to find an entity by criteria
     *
     * @param string $class
     * @param array  $criteria    An array of criteria (field => value)
     * @param string $managerName
     *
     * @return object or NULL if the entity was not found
     */
    public function findEntityBy($class, array $criteria, $managerName = null)
    {
        return $this->getRepository($class, $managerName)->findOneBy($criteria);
    }

    /**
     * Finds the entity by id or throws a NotFoundHttpException
     *
     * @param string $class
     * @param mixed  $id
     * @param string $managerName
     *
     * @return object The found entity
     *
     * @throws NotFoundHttpException if the entity was not found
     */
    public function findEntityOr404($class, $id, $managerName = null)
    {
        $entity = $this->findEntity($class, $id, $managerName);

        if (null === $entity) {
            throw $this->createNotFoundException(sprintf(
                'The %s entity with id "%s" was not found.',
                $class,
                is_array($id) ? implode(' ', $id) : $id
            ));
        }

        return $entity;
    }

    /**
     * Finds the entity matching the specified criteria or throws a NotFoundHttpException
     *
     * @param string $class
     * @param array  $criteria An array of criteria (field => value)
     * @param string $class
     *
     * @return object The found entity
     *
     * @throws NotFoundHttpException if the entity was not found
     */
    public function findEntityByOr404($class, array $criteria, $managerName = null)
    {
        $entity = $this->findEntityBy($class, $criteria, $managerName);

        if (null === $entity) {
            throw $this->createNotFoundException(sprintf(
                'The %s entity with %s was not found.',
                $class,
                implode(' and ', array_map(
                    function ($k, $v) { sprintf('%s "%s"', $k, $v); },
                    array_flip($criteria),
                    $criteria
                ))
            ));
        }

        return $entity;
    }

    /**
    * check if object belongs to the logged user or user has $roles granted
    * if not throw exception
    *
    * @param object $object - must have getUser() which return User object
    * @param array/string  $roles  - roles/role which pass, ROLE_ADMIN by default, might
    *
    */
    public function exceptionIfNotUserOrIsGranted($object, $roles = array('ROLE_ADMIN'))
    {
        if (!$object->getUser()->getId() != $this->getUser()->getId() && $this->get('security.context')->isGranted($roles)) {
            throw $this->createNotFoundException('You can not do this');
        }
    }

    /**
     * check if user is authenticated
     *
     * @return bool auth status
     **/
    public function isAuthenticated()
    {
        return $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * Restful view handler, return json data from any data using jms serializer
     *
     * @param  mixed $data
     * @param  integer $code /http code
     * @return json $data
     **/
    protected function renderRest($data, $code = 200)
    {

        return new Response($this->get('serializer')->serialize($data, 'json'), $code);
    }

    /**
     * Get basic paginator for resource name
     *
     * @return Pagination $pagination
     **/
    protected function getPaginator($service, $perPage = 2)
    {
        //get manager
        $manager = $this->container->get($service)
            ->setNs($service)
            ->setRequest($this->getRequest())
            ->paginate($perPage)
        ;

        //we have a sliding pagination here with iterator interface
        return $manager->getPagination();
    }

    /**
     * Get basic pagination data
     *
     * @param Pagination $pagination
     * @return array $data
     **/
    protected function getPaginatorDetails($paginator)
    {
        return array(
            'current'      => $paginator->getCurrentPageNumber(),
            'totalRecords' => $paginator->getTotalItemCount(),
            'perPage'      => $paginator->getItemNumberPerPage(),
            'results'      => $paginator->getItems(),
        );
    }
}
