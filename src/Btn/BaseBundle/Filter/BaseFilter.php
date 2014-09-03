<?php

namespace Btn\BaseBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
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
     * @var EntityProvider
     */
    protected $ep;

    /**
     * Set repository
     *
     * @param EntityProvider $ep
     */
    public function __construct(EntityProvider $ep)
    {
        $this->ep   = $ep;
        $this->repo = $ep->getRepository();
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
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
