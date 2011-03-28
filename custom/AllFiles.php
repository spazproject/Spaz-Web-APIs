<?php
// If you remove this. You might die.
define('FRAPI_CACHE_ADAPTER', 'apc');


// Use the constant CUSTOM_MODEL to access the custom model directory
require CUSTOM_MODEL . '/Spaz/Thumb.php';
require CUSTOM_MODEL . '/Spaz/Url.php';
require CUSTOM_MODEL . '/Frapi/Limit.php';
require CUSTOM_MODEL . '/AppSTW.php';

require dirname(__FILE__) . DIRECTORY_SEPARATOR.'helpers.php';