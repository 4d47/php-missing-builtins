<?php

/**
 * https://bugs.php.net/bug.php?id=40792
 * https://wiki.php.net/rfc/ifsetor
 *
 * @param array $array
 * @param scalar $key
 * @param mixed $default
 */
function array_get($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * @param mixed $var
 * @return void
 */
function pr($var, $sapi = PHP_SAPI) {
    $var = print_r($var, true);
    echo ($sapi === 'cli') ? $var : '<pre>' . htmlspecialchars($var) . '</pre>';
}
 
/**
 * @param string $string
 * @param integer $limit
 * @param string $ending
 * @return string
 */
function truncate($string, $limit, $ending = '...') {
    assert('is_int($limit) and $limit > 0');
    if (strlen($string) <= $limit) {
        return $string;
    }
    return substr($string, 0, $limit) . $ending;
}
 
/**
 * @param string $stirng
 * @param string $charset
 * @return string
 */
function slugify($string, $charset = null) {
    if (!isset($charset))
        $charset = iconv_get_encoding('internal_encoding');
    $string = trim(iconv($charset, 'ASCII//TRANSLIT', $string));
    $string = preg_replace('/[^a-z0-9\s\-_]/i', '', $string);
    $string = preg_replace('/[\s_]+/', '-', $string);
    return strtolower($string);
}

