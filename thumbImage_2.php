<?php

//等比的缩放


$filename = 'images/2.jpg';
$fileInfo = getimagesize($filename);

if(empty($fileInfo))
    die('文件不是真实图片');
    
list($src_w,$src_h) = $fileInfo;

//等比缩放
//设置最大宽和高
$dst_w = 300;
$dst_h = 600;
$ratio_orig = $src_w / $src_h;
if($dst_w / $dst_h > $ratio_orig){
    $dst_w = $dst_h *$ratio_orig;
}else{
    $dst_h = $dst_w / $ratio_orig;
}

$src_image = imagecreatefromjpeg($filename);

$dst_image = imagecreatetruecolor($dst_w, $dst_h);

imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

imagejpeg($dst_image,"images/2_{$dst_w}x{$dst_h}.jpg");

imagedestroy($dst_image);
imagedestroy($src_image);
