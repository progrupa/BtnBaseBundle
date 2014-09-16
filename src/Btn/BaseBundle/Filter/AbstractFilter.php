<?php

namespace Btn\BaseBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Btn\BaseBundle\Form\AbstractFilterForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Btn\BaseBundle\Provider\EntityProviderInterface;

/**
 * Base filter class
 */
abstract class AbstractFilter implements FilterInterface
{
    /** @var \Btn\BaseBundle\Provider\EntityProviderInterface $entityProvider */
    protected $entityProvider;
    /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
    protected $queryBuilder;
    /** @var \Btn\BaseBundle\Form\AbstractFilterForm $type */
    protected $type;
    /** @var \Symfony\Component\Form\FormInterface $form */
    protected $form;
    /** @var \Symfony\Component\Form\FormFactoryInterface $formFactory */
    protected $formFactory;
    /** @var \Symfony\Component\HttpFoundation\Request $request */
    protected $request;

    /**
     * {@inheritdoc}
     */
    public function setEntityProvider(EntityProviderInterface $entityProvider)
    {
        $this->entityProvider = $entityProvider;

        if (!$this->queryBuilder) {
            $repo = $this->entityProvider->getRepository();
            if (method_exists($repo, 'getFilterQueryBuilder')) {
                $this->queryBuilder = $repo->getFilterQueryBuilder();
            } elseif (method_exists($repo, 'getBaseQueryBuilder')) {
                $this->queryBuilder = $repo->getBaseQueryBuilder();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityProvider()
    {
        return $this->entityProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(AbstractFilterForm $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        $this->handleRequest();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createForm($data = null, array $options = array())
    {
        $form = $this->formFactory->create($this->getType(), $data, $options);

        $this->setForm($form);

        return $this->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedForm($name = '', $data = null, array $options = array())
    {
        $form = $this->formFactory->createNamed($name, $this->getType(), $data, $options);

        $this->setForm($form);

        return $this->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request = null)
    {
        if ($request) {
            $this->setRequest($request);
        }

        if ($this->request && $this->form) {
            $this->form->handleRequest($this->request);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilder()
    {
        if (!$this->queryBuilder) {
            throw new \Exception('Query builder was not injected via setQueryBuilder() or setEntityProvider()');
        }

        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->getQueryBuilder()->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($field, $default = null)
    {
        if (($form = $this->getForm())) {
            return $form->get($field)->getData() ?: $default;
        }

        if (($request = $this->getRequest())) {
            return $request->get($field, $default);
        }

        throw new \Exception();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function applyFilters();
}
