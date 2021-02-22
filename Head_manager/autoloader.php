<?php
    spl_autoload_register(function ($class)
    {
        // import require file with namespace
        
        $fullPath = '../'.str_replace('\\', '/', $class).'.php';
        if(file_exists($fullPath)){
            require $fullPath;
        }
    });


?>