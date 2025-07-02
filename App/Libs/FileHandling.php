<?php

namespace App\Libs;
date_default_timezone_set("Asia/Tehran");
if (!defined('Auth_Access')) {
    die("Access Denied");
}
class FileHandling
{
    public static function WriteErrorLog($message, $file, $line)
    {
        $file_name = __DIR__ . '/../Log';
        if (!file_exists($file_name)) {
            mkdir($file_name, 0777, true);
        }
        $final_filename = $file_name . "/Exception.Log.txt";
        $final_msg = $message . " in File => " . $file . " in line: " . $line ." at ". date('F j, Y, g:i a',time()). PHP_EOL;
        file_put_contents($final_filename, $final_msg, FILE_APPEND);
    }
}
