<?php

//固定尺寸的缩放

function thumbImage($src_image,$src_w,$src_h,$dst_w, $dst_h){

    $dst_image = imagecreatetruecolor($dst_w, $dst_h);
    
    imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    
    imagejpeg($dst_image,"images/1_{$dst_w}x{$dst_h}.jpg");
    
    imagedestroy($dst_image);
}

$filename = 'images/1.jpg';
$fileInfo = getimagesize($filename);

if(empty($fileInfo))
    die('文件不是真实图片');
    
list($src_w,$src_h) = $fileInfo;

$src_image = imagecreatefromjpeg($filename);

thumbImage($src_image,$src_w,$src_h,100,100);
thumbImage($src_image,$src_w,$src_h,270,270);
thumbImage($src_image,$src_w,$src_h,500,500);
imagedestroy($src_image);
