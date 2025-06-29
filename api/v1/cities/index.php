<?php

use App\Utilities\Response;

include_once __DIR__ . "/../../../loader.php";



$re = new Response();
echo $re::responde("title:reza",200);





