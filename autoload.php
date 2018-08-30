<?php
/**
 * Autoloader DIRECTORY_SEPARATOR
 */
function __autoload($class)
{
    $DS = DIRECTORY_SEPARATOR;
    $path = ".".$DS.'app'.$DS.'controller'.$DS.$class;
    require_once $path . '.php';
}

