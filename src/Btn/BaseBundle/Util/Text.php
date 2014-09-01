<?php

namespace Btn\BaseBundle\Util;

class Text
{
    /** @var array $slugifyReplaceArray */
    static protected $slugifyReplaceArray = array(
        'ą' => 'a',
        'Ą' => 'A',
        'ć' => 'c',
        'Ć' => 'C',
        'ę' => 'e',
        'Ę' => 'E',
        'ł' => 'l',
        'Ł' => 'L',
        'ń' => 'n',
        'Ń' => 'N',
        'ó' => 'o',
        'Ó' => 'O',
        'ś' => 's',
        'Ś' => 'S',
        'ź' => 'z',
        'Ż' => 'Z',
        'ż' => 'z',
        'Ż' => 'Z',
    );

    /**
    * Slugify the string
    */
    public static function slugify($text, $strtolower = true, $replaceSpace = true)
    {
        $text = strtr($text, self::$slugifyReplaceArray);

        // strip all non word chars
        $text = preg_replace('/\W/', ' ', $text);

        // replace all white space sections with a dash
        if ($replaceSpace) {
            $text = preg_replace('/\ +/', '-', $text);
        }

        // trim dashes
        $text = preg_replace('/\-$/', '', $text);
        $text = preg_replace('/^\-/', '', $text);

        return $strtolower ? strtolower($text) : $text;
    }

    /**
    * Truncates +text+ to the length of +length+ and replaces the last three characters with the +truncateString+
    * if the +text+ is longer than +length+.
    */
    public static function truncate($text, $length = 30, $truncateString = '...', $truncateLastSpace = false)
    {
        if (is_string($text) && '' !== $text) {
            $mbstring = extension_loaded('mbstring');
            if ($mbstring) {
                @mb_internal_encoding(mb_detect_encoding($text));
            }

            $strlen = $mbstring ? 'mb_strlen' : 'strlen';
            $substr = $mbstring ? 'mb_substr' : 'substr';

            if ($strlen($text) > $length) {
                $text = $substr($text, 0, $length - $strlen($truncateString));
                if ($truncateLastSpace) {
                    $text = preg_replace('/\s+?(\S+)?$/', '', $text);
                }

                $text .= $truncateString;
            }
        }

        return $text;
    }
}
