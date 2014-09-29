<?php

/**
 * Get an $array $key, else $default.
 *
 * @see https://bugs.php.net/bug.php?id=40792
 * @see https://wiki.php.net/rfc/ifsetor
 * @see https://nikic.github.io/2014/01/10/The-case-against-the-ifsetor-function.html
 * @param array $array
 * @param scalar $key
 * @param mixed $default
 * @return mixed
 */
function array_get(array $array, $key, $default = null) {
    return array_key_exists($key, $array) ? $array[$key] : $default;
}


/**
 * <b>h</b>tmlspecialchars() shortchut.
 */
function h($string, $flags = null, $encoding = 'UTF-8', $double_encode = true) {
    return htmlspecialchars($string, $flags ?: ENT_COMPAT | ENT_HTML401, $encoding, $double_encode);
}


/**
 * raw<b>u</b>rlencode() shortcut.
 */
function u($string) {
    return rawurlencode($string);
}


/**
 * <b>p</b>rint_<b>r</b>() shortcut.
 *
 * @param mixed $var
 * @return void
 */
function pr($var) {
    $var = print_r($var, true);
    echo (php_sapi_name() === 'cli') ? $var : '<pre>' . htmlspecialchars($var) . '</pre>';
}
 

/**
 * Create an absolute URL from a relative 
 * based on current $_SERVER environement.
 *
 * @param string $uri optional relative uri
 * @param array $query optional query string
 * @param string $base base url
 * @return string
 */
function url($rel = null, $query = array(), $base = null) {
    // Fix params
    if (func_num_args() == 2 && is_string($query)) {
        $base = $query;
        $query = array();
    }

    // build base url from $_SERVER data
    if (!$base) {
        $ssl = array_get($_SERVER, 'HTTPS') === 'on';
        $protocol = 'http' . ($ssl ? 's' : '');
        $port = $_SERVER['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ":$port";
        $host = array_get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']);
        $base = "$protocol://$host$port{$_SERVER['REQUEST_URI']}";
    }

    if (is_null($rel)) return $base;

    if (empty($rel)) $rel = '/';

    // return if already absolute URL
    if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;

    // queries and anchors
    if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;

    // parse base URL
    $components = parse_url($base);
    $scheme = $components['scheme'];
    $host = $components['host'];

    // remove non-directory element from path
    $path = !empty($components['path']) ? preg_replace('#/[^/]*$#', '', $components['path']) : '';

    // destroy path if relative url points to root
    if ($rel[0] == '/') $path = '';

    // dirty absolute URL
    $abs = "$host$path/$rel";

    // replace '//' or '/./' or '/foo/../' with '/'
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

    // absolute URL is ready!
    $url = "$scheme://$abs";

    // optionally append query string
    if ($query) {
        $url .= '?' . http_build_query($query);
    }

    return $url;
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

