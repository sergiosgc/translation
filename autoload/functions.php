<?php
if (!function_exists('__')) {
function __($message, ...$args) {
    $message = \sergiosgc\translation\Translation::singleton()->translate($message);
    if (function_exists('sergiosgc\sprintf')) try {
        $message = \sergiosgc\sprintf($message, ...$args);
    } catch (\sergiosgc\MissingConversionSpecifierException $e) { }
    return $message;
}
}
if (!function_exists('__p')) {
function __p($message, ...$args) {
    print(__($message, ...$args));
}
}
