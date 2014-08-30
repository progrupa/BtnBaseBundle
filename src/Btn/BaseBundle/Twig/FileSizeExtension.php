<?php

namespace Btn\BaseBundle\Twig;

use Btn\BaseBundle\Helper\FileHelper;

class FileSizeExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('file_size_humanize', array($this, 'fileSizeHumanize')),
        );
    }

    /**
     *
     */
    public function fileSizeHumanize($number)
    {
        return FileHelper::humanize($number);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'btn_base.extension.file_size';
    }
}
