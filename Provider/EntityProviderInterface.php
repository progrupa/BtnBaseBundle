<?php

namespace Btn\BaseBundle\Provider;

interface EntityProviderInterface
{
    public function getEntityManager();
    public function getClass();
    public function getRepository();
    public function create();
    public function supports($class);
    public function delete($entity, $andFlush = true);
    public function save($entity, $andFlush = true);
}
