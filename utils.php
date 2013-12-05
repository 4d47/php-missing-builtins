<?php

/**
 * Get an $array $key, else $default.
 *
 * @see https://bugs.php.net/bug.php?id=40792
 * @see https://wiki.php.net/rfc/ifsetor
 * @param array $array
 * @param scalar $key
 * @param mixed $default
 * @return mixed
 */
function array_get($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * <b>h</b>tmlspecialchars() shortchut.
 */
function h($string, $flags = null, $encoding = 'UTF-8', $double_encode = true) {
    return htmlspecialchars($string, $flags ?: ENT_COMPAT | ENT_HTML401, $encoding, $double_encode);
}

/**
 * <b>u</b>rlencode() shortcut.
 */
function u($string) {
    return urlencode($string);
}

/**
 * <b>p</b>rint_<b>r</b>() shortcut.
 *
 * @param mixed $var
 * @return void
 */
function pr($var) {
    $var = print_r($var, true);
    echo is_cli() ? $var : '<pre>' . htmlspecialchars($var) . '</pre>';
}
 
/**
 * Is running from console.
 */
function is_cli() {
    return php_sapi_name() === 'cli';
}

/**
 * Truncate $string at a specific $limit,
 * adding $ending if was truncated.
 *
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
 * Create a URL friendly version of $string.
 *
 * @param string $stirng
 * @param string $encoding
 * @return string
 */
function slugify($string, $encoding = 'UTF-8') {
    $string = trim(iconv($encoding, 'ASCII//TRANSLIT', $string));
    $string = preg_replace('/[^a-z0-9\s\-_]/i', '', $string);
    $string = preg_replace('/[\s_]+/', '-', $string);
    return strtolower($string);
}

/**
 * Create an absolute URL based on current $_SERVER environement.
 *
 * @param string $uri optional uri
 * @return string
 */
function url($uri = null) {
    $ssl = array_get($_SERVER, 'HTTPS') === 'on';
    $protocol = 'http' . ($ssl ? 's' : '');
    $port = $_SERVER['SERVER_PORT'];
    $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ":$port";
    $host = array_get($_SERVER, 'HTTP_X_FORWARDED_HOST', array_get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']));
    $uri = $uri ?: $_SERVER['REQUEST_URI'];
    return "$protocol://$host$port$uri";
}

/**
 * Record $name $message to the session and get it back,
 * only once. Requires a session_start()ed.
 *
 * @param $name
 * @param $message
 */
function session_flash($name, $message = null) {
    if (is_null($message)) {
        $message = array_get($_SESSION, $name, '');
        unset($_SESSION[$name]);
        return $message;
    }
    return $_SESSION[$name] = $message;
}

