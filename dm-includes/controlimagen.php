<?php
//exportimg("original.jpg" , "new.jpg" , 400 , 300 , 80 , 0 , 1);
function exportimg($source, $target, $width, $height, $quality, $resize, $center){
    $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    list($width_orig, $height_orig) = getimagesize($source);
 
    if($center==1){///Crop centre
        $image_p = imagecreatetruecolor($width, $height);
        $width_temp=$width;
        $height_temp=$height;
 
        if($width_orig/$height_orig>$width/$height){
            $width = $width_orig*$height/$height_orig;
            $x_pos = -($width-$width_temp)/2;
            $y_pos = 0;
        }else{
            $height = $height_orig*$width/$width_orig;
            $y_pos = -($height-$height_temp)/2;
            $x_pos = 0;
        }
 
    }else{///Just resize
        if($resize == 0){}///Resize to exact new width&height
 
        if($resize == 1){///Resize to these max width&height
            if($width_orig<$width && $height_orig<$height){
                $width = $width_orig;
                $height = $height_orig;
            }else{
                if($width_orig/$height_orig>$width/$height){
                    $height = $width*$height_orig/$width_orig;
                }else{
                    $width = $height*$width_orig/$height_orig;
                }
            }
        }
 
        if($resize == 2){if($width_orig>$width) $height = $height_orig*$width/$width_orig; else {$width = $width_orig;$height = $height_orig;}}///Dynamic Height
 
        if($resize == 3){if($height_orig>$height) $width = $width_orig*$height/$height_orig; else {$width = $width_orig;$height = $height_orig;}}///Dynamic Width
 
        $image_p = imagecreatetruecolor($width, $height);//
 
    }
 
    if($ext == "jpg" || $ext == "jpeg") $image = imagecreatefromjpeg($source);
    else if($ext == "png"){imagealphablending($image_p, false);imagesavealpha($image_p, true);$image = imagecreatefrompng($source);}//convert transparent
 
    if($center==1) imagecopyresampled($image_p, $image, $x_pos, $y_pos, 0, 0, $width, $height, $width_orig, $height_orig);
    else imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
 
    if($ext == "jpg" || $ext == "jpeg") imagejpeg($image_p, $target, $quality);
    if($ext == "png") imagepng($image_p, $target, 9);
 
}
?>