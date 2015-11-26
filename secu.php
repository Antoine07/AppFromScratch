<?php


function generate_salt($length = 16, $strong = true)
{

    $string = '';
    $bytes = openssl_random_pseudo_bytes($length, $strong);
    while (($len = strlen($string)) < $length) {
        $size = $length - $len;

        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }

    return $string;
}

echo generate_salt();
