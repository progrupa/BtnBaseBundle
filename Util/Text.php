<?php

namespace Btn\BaseBundle\Util;

class Text {
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
}