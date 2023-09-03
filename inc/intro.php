<?php

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

// post form
if (isset($_POST['submit'])) {
    $targetDir = dirname(__DIR__) . '/uploads/';  
    create_dir($targetDir);

    $upload_image = $_FILES['upload_image'];
    $upload_watermark = $_FILES['upload_watermark'];

    if ($upload_image['error'] === 4 || $upload_watermark['error'] == 4) {
        $errorMsg = 'תמונה לא הועלתה או סימן מים לא הועלה.';
    }
    if ($upload_image['error'] !== 0 || $upload_watermark['error'] !== 0)
    {
        $errorMsg = 'תמונה לא הועלתה או סימן מים לא הועלה.';
    }
    else {
        // image upload path 
        $imageName = basename($upload_image["name"]);  
        $imageNameUq = get_filename_without_ext($imageName) . '_' . time() . '.' . get_ext($imageName);
        $targetImagePath = $targetDir . $imageNameUq; 
        $imageType = pathinfo($targetImagePath, PATHINFO_EXTENSION);

        // watermark upload path
        $wtName = basename($upload_watermark["name"]); 
        $targetWatermarkPath = $targetDir . $wtName; 
        $watermarkType = pathinfo($targetWatermarkPath, PATHINFO_EXTENSION);

        // Allow certain file formats 
        $allowTypes = ['jpg','jpeg','png', 'jfif'];
        if(in_array($imageType, $allowTypes) && in_array($watermarkType, $allowTypes)) {
            // Upload file to the server 
            if(move_uploaded_file($upload_image["tmp_name"], $targetImagePath)
                    && move_uploaded_file($upload_watermark["tmp_name"], $targetWatermarkPath)) { 
                $im = createImageFrom($imageType, $targetImagePath);
                $wt = createImageFrom($watermarkType, $targetWatermarkPath);

                // Set the margins for the watermark 
                $marge_right = 0; 
                $marge_bottom = 0; 
                 
                // Get the height/width of the watermark image 
                $sx = imagesx($wt); 
                $sy = imagesy($wt); 
                 
                // Copy the watermark image onto our photo using the margin offsets and  
                // the photo width to calculate the positioning of the watermark. 
                imagecopy(
                    $im, 
                    $wt, 
                    imagesx($im) / 2 - $sx / 2 - $marge_right, 
                    imagesy($im) / 2 - $sy / 2 - $marge_bottom, 
                    0, 
                    0, 
                    imagesx($wt), 
                    imagesy($wt)
                ); 
                 
                // Save image and free memory 
                imagepng($im, $targetImagePath); 
                imagedestroy($im);

                if (file_exists($targetImagePath)){
                    $mime = $upload_image['type'];
                    $quoted = sprintf('"%s"', addcslashes($imageName, '"\\'));

                    header('Pragma: public');  // required
                    header('Expires: 0');  // no cache
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Cache-Control: private', false);
                    header('Content-Type: ' . $mime);
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($targetImagePath)) . ' GMT');
                    header('Content-disposition: attachment; filename=' . $quoted);
                    header("Content-Transfer-Encoding:  binary");
                    header('Content-Length: ' . filesize($targetImagePath)); // provide file size
                    header('Connection: close');
                    readfile($targetImagePath);
                    flush();

                    if (file_exists($targetImagePath)) {
                        unlink($targetImagePath);
                    }

                    if (file_exists($targetWatermarkPath)) {
                        unlink($targetWatermarkPath);
                    }

                    $successMsg = "התמונה עם סימני המים נוצרה בהצלחה."; 
                } else { 
                    $errorMsg = "תהליך יצירת התמונה כשל, נא נסה שנית."; 
                }  
            } else { 
                $errorMsg = "אירעה טעות בהעלאת התמונה."; 
            }       
        } else{ 
            $errorMsg = 'ניתן להעלות רק את הפורמטים הבאים: JPG, JPEG, JFIF, ו- PNG.'; 
        } 
    }
}