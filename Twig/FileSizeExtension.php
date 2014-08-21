<?php

namespace Btn\BaseBundle\Twig;

class FileSizeExtension extends \Twig_Extension
{
    /**
     *
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('size', array($this, 'sizeFilter')),
        );
    }

    /**
     *
     */
    public function sizeFilter($number)
    {
        return $this->formatSizeUnits($number);
    }

    /**
     *
     */
    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     *
     */
    public function getName()
    {
        return 'btn_base.extension.file_size';
    }
}
