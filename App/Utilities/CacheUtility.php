<?php

namespace App\Utilities;

use App\Utilities\Response;
if (!defined('Auth_Access')) {
    die("Access Denied");
}

class CacheUtility
{
    protected static $cache_file;
    protected static $cache_enabled = CACHE_ENABLED;
    const EXPIRE_TIME = 3;

    public static function init()
    {
        self::$cache_file = CACHE_DIR . md5($_SERVER['REQUEST_URI']) . ".json";
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            self::$cache_enabled = 0;
        }
    }

    public static function start()
    {
        self::init();
        if(!self::$cache_enabled){
            return;
        }
        if(file_exists(self::$cache_file) && ((time() < (filemtime(self::$cache_file) + self::EXPIRE_TIME)))){
            Response::SetHeaders(Response::HTTP_OK);
            readfile(self::$cache_file);
            exit;
            
        }
        ob_start();
    }

    public static function end()
    {
        if(!self::$cache_enabled){
            return;
        }
        $cached_file = fopen(self::$cache_file,'w');
        fwrite($cached_file,ob_get_contents());
        fclose($cached_file);
        ob_end_flush();
    }

    public static function flush()
    {
        $files = glob(CACHE_DIR . '*.json');
        foreach ($files as $file) {
           if(is_file($file)){
            $file = str_replace('\\','/',$file);
            unlink($file);
           }
        }
    }
}
