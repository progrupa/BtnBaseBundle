<?php

namespace Btn\BaseBundle\Filter;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Btn\BaseBundle\Provider\EntityProviderInterface;

/**
 * Base filter class
 */
abstract class AbstractFilter
{
    /** @var \Btn\BaseBundle\Provider\EntityProviderInterface $entityProvider */
    protected $entityProvider;
    /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
    protected $queryBuilder;
    /** @var \Symfony\Component\Form\AbstractType $form */
    protected $form;
    /** @var \Symfony\Component\Form\FormFactoryInterface $formFactory */
    protected $formFactory;
    /** @var \Symfony\Component\HttpFoundation\Request $requset */
    protected $request;
    /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
    protected $queryBuilder;

    /**
     * Set repository
     *
     * @param EntityProvider $ep
     */
    public function __construct(EntityProvider $entityProvider)
    {
        $this->entityProvider = $entityProvider;
    }

    /**
     * @param AbstractType $form
     * @return AbstractFilter
     */
    public function setForm(AbstractType $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return AbstractFilter
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     *
     */
    public function createForm($data = null, array $options = array())
    {
        return $this->formFactory->create($this->getForm(), $data, $options);
    }

    /**
     * @param Request $request
     * @return AbstractFilter
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->getQueryBuilder()->getQuery();
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->getQuery()->getResult();
    }

    /**
     *
     */
    public function getValue($field, $default = null)
    {
        return $this->request->get($field, $default);
    }

    /**
     * Bind fields from array
     *
     * @return void
     */
    public function exprFields(array $fields, $alias)
    {
        foreach ($fields as $field) {
            if (($value = $this->getValue($field))) {
                $this->qb
                    ->andWhere($alias . '.' . $field . ' = :' . $field)
                    ->setParameter($field, $value)
                ;
            }
        }
    }
}
