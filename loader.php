<?php


define('Auth_Access', true);
define('CACHE_DIR',__DIR__."/cache/");
define('CACHE_ENABLED',1);


spl_autoload_register(function ($class) {
    $classfile = __DIR__."/".$class.".php";
    $classfile = str_replace("\\","/",$classfile);
    if(!(file_exists($classfile) && is_readable($classfile)))
        die("Class $classfile Not Found");
    require $classfile;
        
});


