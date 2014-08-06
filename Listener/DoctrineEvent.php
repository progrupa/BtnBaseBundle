<?php

namespace Btn\BaseBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Table;

class DoctrineEvent
{
    /**
     * standard options array from parameters
     *
     * @var array
     **/
    private $options;

    /**
     * standard construct
     *
     * @return void
     **/
    public function __construct(array $options = null)
    {
        if (!$options) {
            $options = array();
        }

        $this->options = array_merge(array('collate' => 'utf8_general_ci', 'charset' => 'utf8'), $options);
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $reader      = new AnnotationReader();
        $metadata    = $eventArgs->getClassMetadata();
        $class       = $metadata->getReflectionClass();

        if ($class) {
            $annotations = $reader->getClassAnnotations($class);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Table) {
                    $metadata->table['options'] = $this->options;
                }
            }
        }
    }
}
