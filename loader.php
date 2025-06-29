<?php


define('Auth_Access', true);
include_once "App/iran.php";

spl_autoload_register(function ($class) {
    $classfile = __DIR__."/".$class.".php";
    $classfile = str_replace("\\","/",$classfile);
    if(!(file_exists($classfile) && is_readable($classfile)))
        die("Class $classfile Not Found");
    require $classfile;
        
});


