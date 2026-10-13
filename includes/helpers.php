<?php

/**
 * Escapes HTML characters to prevent XSS.
 * 
 * @param string $string
 * @return string
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirects to a specific URL and stops script execution.
 * 
 * @param string $url
 */
function redirect($url) {
    if (preg_match('/^https?:\/\//i', $url)) {
        header("Location: " . $url);
        exit();
    }
    
    $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if (substr($base_url, -1) !== '/') {
        $base_url .= '/';
    }
    
    $url = ltrim($url, '/');
    header("Location: " . $base_url . $url);
    exit();
}
