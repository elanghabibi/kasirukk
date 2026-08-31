<?php

function loadCore($className) {
    $pathToFile = "../app/core/" . $className .".php";

    if (file_exists($pathToFile)) {
        require_once $pathToFile;    
    }
}


function loadHelpers($className) {
    $pathToFile = "../app/helpers/" . $className .".php";

    if (file_exists($pathToFile)) {
        require_once $pathToFile;    
    }
}


function loadMiddleware($className) {
    $pathToFile = "../app/middleware/" . $className .".php";

    if (file_exists($pathToFile)) {
        require_once $pathToFile;    
    }
}

spl_autoload_register('loadCore');
spl_autoload_register('loadHelpers');
spl_autoload_register('loadMiddleware');



?>