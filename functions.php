<?php

/**
 * Functions file. Here placed some general functions which needed
 * in some places in the project.
 */

/**
 * @param $delimiters
 * @param $string
 * @return array
 */
function multiExplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return $launch;
}
