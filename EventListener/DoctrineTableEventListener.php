<?php

namespace Btn\BaseBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Table;

class DoctrineTableEventListener
{
    /** @var array $options */
    protected $options;

    /**
     *
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     *
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $reader      = new AnnotationReader();
        $metadata    = $eventArgs->getClassMetadata();
        $class       = $metadata->getReflectionClass();

        if ($class) {
            $annotations = $reader->getClassAnnotations($class);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Table) {
                    $metadata->table['options'] = array_merge($metadata->table['options'], $this->options);
                }
            }
        }
    }
}
