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
        $this->checkSupportOrThrowException($entity);

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
        $this->checkSupportOrThrowException($entity);

        if (!$entity->getId()) {
            $this->em->persist($entity);
        }

        if ($andFlush) {
            $this->em->flush($entity);
        }
    }

    /**
     *
     */
    protected function checkSupportOrThrowException($entity)
    {
        if (!is_object($entity)) {
            throw new \Exception(sprintf(
                'Invalid argument for "%s". Object required, "%s" given.',
                __CLASS__,
                gettype($entity)
            ));
        }

        if (!$this->supports($entity)) {
            throw new \Exception(sprinf(
                'This provider only supports "%s", "%s" is given.',
                $this->getClass(),
                get_class($entity)
            ));
        }
    }
}
