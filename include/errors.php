<?php 
require_once __DIR__ . '/class/logger.php';

function handleErrors($errno, $errMsg, $errFile, $errLine) {
    (new Logger($errno, $errMsg, $errFile, $errLine))->error();

    exit();
}

function handleUncaughtException($e)
{
    (new Logger($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine()))->error();
    exit("An unexpected error occurred.");
}

set_exception_handler('handleUncaughtException');
set_error_handler('handleErrors');
?>