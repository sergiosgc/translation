<?php
function __($message, ...$args) {
    $message = \sergiosgc\translation\Translation::singleton()->translate($message);
    if (class_exists('sergiosgc\sprintf', true)) $message = \sergiosgc\sprintf($message, ...$args);
    return $message;
}