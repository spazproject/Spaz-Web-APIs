<?php

/**
 * checks if the passed string is a valid URL (in a common sense)
 *
 * @param string $url The URL to validate
 * @return boolean
 * @author Ed Finkler
 */
function is_valid_url($url) {
    $regex = '';

    $regex .= '&';
    $regex .= '^(ftp|http|https):';					// protocol
    $regex .= '(//)';								// authority-start
    $regex .= '([-a-z0-9/~;:@=+$,.!*()\']+@)?';		// userinfo
    $regex .= '(';
    $regex .= '((?:[^\W_]((?:[^\W_]|-){0,61}[^\W_])?\.)+[a-zA-Z]{2,6}\.?)';		// domain name
    $regex .= '|';
    $regex .= '([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?(\.[0-9]{1,3})?)';	// OR ipv4
    $regex .= ')';
    $regex .= '(:([0-9]*))?';						// port
    $regex .= '(/((%[0-9a-f]{2}|[-_a-z0-9/~;:@=+$,.!*()\'\&]*)*)/?)?';	// path
    $regex .= '(\?[^#]*)?';							// query
    $regex .= '(#([-a-z0-9_]*))?';					// anchor (fragment)
    $regex .= '$&i';

    $result = preg_match($regex, $url, $subpatterns);

    if ($result === 1) {
        return true;
    }

    return false;
}