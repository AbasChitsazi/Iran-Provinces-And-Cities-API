<?php


define('Auth_Access', true);
define('CACHE_DIR',__DIR__."/App/cache/");
define("BASE_PATH",__DIR__);
define('BASE_URL',$_SERVER['HTTP_HOST']);
define('CACHE_ENABLED',0);
define('JWT_SECRET','28058097852365');
define('JWT_ALG','HS256');

include __DIR__."/vendor/autoload.php";


spl_autoload_register(function ($class) {
    $classfile = __DIR__."/".$class.".php";
    $classfile = str_replace("\\","/",$classfile);
    if(!(file_exists($classfile) && is_readable($classfile)))
        die("Class $classfile Not Found");
    require $classfile;
        
});


