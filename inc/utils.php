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

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function download($fileName, $filePath, $mime) {
    header('Pragma: public');  // required
    header('Expires: 0');  // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: ' . $mime);
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');
    header('Content-disposition: attachment; filename=' . $fileName);
    header("Content-Transfer-Encoding:  binary");
    header('Content-Length: ' . filesize($filePath)); // provide file size
    header('Connection: close');
    readfile($filePath);
    flush();
}