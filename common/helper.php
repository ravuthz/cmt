<?php

/**
 * Convert UTF-8 to UCS-2 string into binary hexadecimal form to save to database.
 * @param [string] $text [String to convert]
 * @return [string] [Encoded string]
 */
function encodeUnicode($text) {
    $ucs2 = iconv('UTF-8', 'UCS-2LE', $text);
    $arr = unpack('H*hex', $ucs2);
    $hex = "0x{$arr['hex']}";
    return $hex;
}

/**
 * Convert UCS-2 to UTF-8 string from binary hexadecimal form to output client.
 * @param [string] $text [String to convert]
 * @return [string] [Decoded string]
 */
function decodeUnicode($text) {
    return iconv('UCS-2LE', 'UTF-8', $text);
}

function arrayJoinToString($array = array(), $seperator = ', ') {
    if (empty($array)) return;
    return (implode($seperator, array_filter($array)));
}

function echoDebug($object) {
    echo "<hr><pre>" , print_r($object, true) , "</pre>";
}