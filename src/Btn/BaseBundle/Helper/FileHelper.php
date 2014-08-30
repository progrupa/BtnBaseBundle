<?php

namespace Btn\BaseBundle\Helper;

class FileHelper
{
    /**
     * Convert a given size with units to bytes
     *
     * @param string $str
     */
    public static function toBytes($str)
    {
        $val  = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g':
                $val *= 1024;
                // no break
            case 'm':
                $val *= 1024;
                // no break
            case 'k':
                $val *= 1024;
                // no break
        }

        return $val;
    }

    /**
     * Convert byts to human readable size format
     */
    public static function humanize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes.' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes.' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * Internal function that checks if server's may sizes match the
     * object's maximum size for uploads
     */
    public static function checkServerSizeLimit($sizeLimit)
    {
        $sizeLimitHumanized = self::humanize($sizeLimit);

        if (self::toBytes(ini_get('post_max_size')) < $sizeLimit) {
            throw new \Exception(sprintf('Increase post_max_size to "%s".', $sizeLimitHumanized));
        }

        if (self::toBytes(ini_get('upload_max_filesize')) < $sizeLimit) {
            throw new \Exception(sprintf('Increase upload_max_filesize and to "%s".', $sizeLimitHumanized));
        }
    }
}
