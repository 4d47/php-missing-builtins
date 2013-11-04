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
 * htmlspecialchars() shortchut.
 */
function h($string, $flags = null, $encoding = 'UTF-8', $double_encode = true) {
    return htmlspecialchars($string, $flags ?: ENT_COMPAT | ENT_HTML401, $encoding, $double_encode);
}

/**
 * urlencode() shortcut.
 */
function u($string) {
    return urlencode($string);
}

/**
 * print_r() shortcut.
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
 * A simple cache abstraction function.
 * Will use APC if available and fallback to files.
 *
 * @param string $key
 * @param mixed $var
 * @param int $ttl seconds to live
 * @return mixed
 */
function cache($k, $v = null, $ttl = 0)
{
    if (extension_loaded('APC')) {
        return (func_num_args() === 1) ? apc_fetch($k) : apc_store($k, $v, $ttl);
    }

    # file base caching
    $fname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cache_' . md5($k); 
    if (func_num_args() == 1) {
        $data = file_get_contents($fname);
        $data = @unserialize($data);
        if ($data === false or $_SERVER['REQUEST_TIME'] > $data[0]) {
            unlink($fname);
            return false;
        }
        return $data[1]; 
    } else {
        if ($ttl == 0) $ttl = 3600;
        $v = serialize( array( $_SERVER['REQUEST_TIME'] + $ttl, $v ) ); 
        return file_put_contents($fname, $v, LOCK_EX) !== false;
    }
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

