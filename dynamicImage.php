<?php
//动态根据图片类型进行创建
$filename = 'images/3.jpg';
$fileInfo = getimagesize($filename);
if(!empty($fileInfo)){
    list($src_w,$src_h) = $fileInfo;
    $mime = $fileInfo['mime'];
}else{
    die('不是真实图片');
}
// var_dump($fileInfo);
$createFun = str_replace("/", "createfrom", $mime);
// var_dump($createFun);

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

$src_image = $createFun($filename);

$dst_image = imagecreatetruecolor($dst_w, $dst_h);

imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

$outFun = str_replace("/", "", $mime);
$outFun($dst_image,"images/3_{$dst_w}x{$dst_h}.jpg");

imagedestroy($dst_image);
imagedestroy($src_image);