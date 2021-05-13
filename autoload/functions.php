<?php
if (!function_exists('__')) {
function __($message, ...$args) {
    $message = \sergiosgc\translation\Translation::singleton()->translate($message);
    if (class_exists('sergiosgc\sprintf', true)) $message = \sergiosgc\sprintf($message, ...$args);
    return $message;
}
}
if (!function_exists('__p')) {
function __p($message, ...$args) {
    print(__($message, ---$args));
}
}
