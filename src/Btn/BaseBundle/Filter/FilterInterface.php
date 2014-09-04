<?php

namespace Btn\BaseBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Btn\BaseBundle\Form\AbstractFilterForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Btn\BaseBundle\Provider\EntityProviderInterface;

interface FilterInterface
{
    /**
     * @param  EntityProviderInterface $entityProvider
     * @return FilterInterface
     */
    public function setEntityProvider(EntityProviderInterface $entityProvider);

    /**
     * @return EntityProviderInterface
     */
    public function getEntityProvider();

    /**
     * @param  AbstractFilterForm $type
     * @return AbstractFilter
     */
    public function setType(AbstractFilterForm $type);

    /**
     * @return AbstractFilterForm
     */
    public function getType();

    /**
     * @param  FormInterface   $form
     * @return FilterInterface
     */
    public function setForm(FormInterface $form);

    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @param  FormFactoryInterface $formFactory
     * @return FilterInterface
     */
    public function setFormFactory(FormFactoryInterface $formFactory);

    /**
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array());

    /**
     * @return FormInterface
     */
    public function createNamedForm($name = '', $data = null, array $options = array());

    /**
     * @param  Request         $request
     * @return FilterInterface
     */
    public function setRequest(Request $request);

    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @param  Request         $request
     * @return FilterInterface
     */
    public function handleRequest(Request $request = null);

    /**
     * @param  QueryBuilder    $queryBuilder
     * @return FilterInterface
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder);

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder();

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getQuery();

    /**
     * @return array
     */
    public function getResult();

    /**
     * @param  string $field
     * @param  mixed  $default
     * @return mixed
     */
    public function getValue($field, $default = null);

    /**
     * Method where you apply filters to query builder
     */
    public function applyFilters();
}
