<?php

namespace Btn\BaseBundle\Util;

class Text
{
    /**
    * Slugify the string
    */
    public static function slugify($text, $strtolower = true, $replace_space = true)
    {
        $org = array('ą', 'Ą', 'ć', 'Ć', 'ę', 'Ę', 'ł', 'Ł', 'ń', 'Ń', 'ó', 'Ó', 'ś', 'Ś', 'ź', 'Ź', 'ż', 'Ż');
        $new = array('a', 'A', 'c', 'C', 'e', 'E', 'l', 'L', 'n', 'N', 'o', 'O', 's', 'S', 'z', 'Z', 'z', 'Z');
        $text = str_replace($org, $new, $text);

        // strip all non word chars
        $text = preg_replace('/\W/', ' ', $text);

        // replace all white space sections with a dash
        if ($replace_space) {
            $text = preg_replace('/\ +/', '-', $text);
        }

        // trim dashes
        $text = preg_replace('/\-$/', '', $text);
        $text = preg_replace('/^\-/', '', $text);

        return $strtolower ? strtolower($text) : $text;
    }

    /**
    * Truncates +text+ to the length of +length+ and replaces the last three characters with the +truncate_string+
    * if the +text+ is longer than +length+.
    */
    public static function truncate($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false)
    {
        if ($text == '') {
            return '';
        }

        $mbstring = extension_loaded('mbstring');
        if ($mbstring) {
            @mb_internal_encoding(mb_detect_encoding($text));
        }

        $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
        $substr = ($mbstring) ? 'mb_substr' : 'substr';

        if ($strlen($text) > $length) {
            $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
            if ($truncate_lastspace) {
                $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
            }

            return $truncate_text.$truncate_string;
        } else {
            return $text;
        }
    }
}
