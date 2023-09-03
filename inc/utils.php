<?php
define ('FLASH_MESSAGE', 'flash_message');

function styled_var_dump($data) { 
    echo '<pre>';
    var_dump($data);
    echo '</pre>';  
}

function create_dir($dir) {
    if (!is_dir($dir) && !mkdir($dir, 0777, true)){
        exit("Error creating folder $dir");
    }
}

function get_ext($name) {
    $n = strrpos($name, '.');
    return ($n === false) ? '' : substr($name, $n+1);
}

function get_filename_without_ext($filename) {
    return preg_replace("/\.[^.]+$/", "", $filename);
}

function createImageFrom($type, $targetPath) {
    switch($type){ 
        case 'jpg':
            $im = imagecreatefromjpeg($targetPath); 
            break; 
        case 'jpeg': 
            $im = imagecreatefromjpeg($targetPath); 
            break; 
        case 'png': 
            $im = imagecreatefrompng($targetPath); 
            break; 
        default: 
            $im = imagecreatefromjpeg($targetPath); 
    } 

    return $im;
}

function setFlash($value) {
    $_SESSION[FLASH_MESSAGE] = $value;
}

function getFlash() {
    if(isset($_SESSION[FLASH_MESSAGE])) {
        $value = $_SESSION[FLASH_MESSAGE];
        unset($_SESSION[FLASH_MESSAGE]);
        return $value;
    }
    return null;
}