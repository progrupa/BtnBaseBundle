<?php

namespace Btn\BaseBundle\Provider;

use Doctrine\ORM\EntityManager;

abstract class AbstractEntityProvider implements EntityProviderInterface
{
    /** @var \Doctrine\ORM\EntityManager $em */
    protected $em;
    /** @var string $class */
    protected $class;

    /**
     *
     */
    public function __construct($class, EntityManager $em = null)
    {
        $this->class = $class;
        $this->em    = $em;

    }

    /**
     *
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }

    /**
     *
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     *
     */
    public function getClass()
    {
        if (!$this->class) {
            throw new \Exception('Class name not defined');
        }

        return $this->class;
    }

    /**
     *
     */
    public function getRepository()
    {
        return $this->em->getRepository($this->getClass());
    }

    /**
     *
     */
    public function create()
    {
        $class = $this->getClass();

        $entity = new $class();

        return $entity;
    }

    /**
     *
     */
    public function supports($class)
    {
        return is_a($class, $this->getClass()) ? true : false;
    }

    /**
     *
     */
    public function delete($entity, $andFlush = true)
    {
        if (!$this->supports($entity)) {
            throw new \Exception('This provider does not supports this entity type');
        }

        $this->em->remove($entity);

        if ($andFlush) {
            $this->em->flush($entity);
        }
    }

    /**
     *
     */
    public function save($entity, $andFlush = true)
    {
        if (!$this->supports($entity)) {
            throw new \Exception('This provider does not supports this entity type');
        }

        if (!$entity->getId()) {
            $this->em->persist($entity);
        }

        if ($andFlush) {
            $this->em->flush($entity);
        }
    }
}
