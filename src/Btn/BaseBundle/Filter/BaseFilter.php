<?php

namespace Btn\BaseBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Base filter class
 */
class BaseFilter
{
    /**
     * @var EntityRepository
     */
    protected $repo;

    /**
     * @var Request
     */
    protected $request = null;

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var string
     */
    protected $bundleNamespace;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $entityNamespace;

    /**
     * @var array
     */
    protected $query = array();

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Set repository
     *
     * @param EntityManager $em
     * @param string $bundleNamespace
     * @param string $entityName
     */
    public function __construct(EntityManager $em, $bundleNamespace, $entityName)
    {
        $this->bundleNamespace = $bundleNamespace;
        $this->entityNamespace = $bundleNamespace . '\Entity\\' . $entityName;
        $this->entityName      = $entityName;
        $this->repo            = $em->getRepository($bundleNamespace . '\Entity\\' . $entityName);
        $this->em              = $em;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        $this->query   = array_merge($request->query->all(), $request->request->all());
    }

    /**
     * get query string
     *
     * @return mixed
     **/
    public function getQuery($string)
    {
        if (isset($this->query[$string])) {
            return $this->query[$string];
        }

        return $this->request->get($string);
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->qb->getQuery()->getResult();
    }

    /**
     * Bind entity fields from array
     *
     * @return void
     **/
    public function exprEntityFields($fields, $alias)
    {
        foreach ($fields as $field) {
            if ($value = $this->getQuery($field)) {
                $this->qb
                    ->andWhere($alias . '.' . $field . ' = :' . $field)
                    ->setParameter($field, $value);
            }
        }
    }
}
