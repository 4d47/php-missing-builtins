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
 * Is running from console.
 */
function is_cli() {
    return php_sapi_name() === 'cli';
}

/**
 * @param mixed $var
 * @return void
 */
function pr($var) {
    $var = print_r($var, true);
    echo is_cli() ? $var : '<pre>' . htmlspecialchars($var) . '</pre>';
}
 
/**
 * @param string $string
 * @param integer $limit
 * @param string $ending
 * @param string $encoding
 * @return string
 */
function truncate($string, $limit, $ending = '...', $encoding = 'UTF-8') {
    assert('is_int($limit) and $limit > 0');
    if (mb_strlen($string, $encoding) <= $limit) {
        return $string;
    }
    return mb_substr($string, 0, $limit, $encoding) . $ending;
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

