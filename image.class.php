<?php
/**
 * 图片工具类
 * @author lvfk
 *
 */
class Image {
    /**
     * 图片文件信息
     * @var array
     */
    private $fileInfo;
    /**
     * 操作集合
     * @var array
     */
    private $ops;
    
    /**
     * 构造函数
     * @param string $fileName
     * @param string $savePath 保存图片路径，默认向浏览器输出
     */
    public function __construct($fileName,$savePath='images/'){
        if(empty($fileName)){
            die('图片路径为空');
        }
        
        //检测文件是不是真实图片
        $this->getImageInfo($fileName);
        
        //
        $ops['createFun'] = str_replace('/', "createfrom", $this->fileInfo['mime']);
        $ops['outFun'] = str_replace('/', '', $this->fileInfo['mime']);;
//         $ops['ext'] = strtolower(image_type_to_extension($this->fileInfo[2],false));
        $ops['ext'] = strtolower(image_type_to_extension($this->fileInfo[2]));
        $ops['srcImage'] = $ops['createFun']($fileName);
        if(!empty($savePath)){
            $ops['savePath'] = $savePath;
            if(!file_exists($savePath)){
                mkdir($savePath,0755,true);
            }
        }
        $this->ops = $ops;
    }
    
    /**
     * 释放资源
     */
    public function __destruct(){
        unset($this->fileInfo);
        unset($this->ops);
    }
    
    /**
     * 检测文件是不是真实图片
     * @param string $filename
     */
    private function getImageInfo($filename){
        $fileInfo = getimagesize($filename);
        if(empty($fileInfo)){
            die('文件不是真实图片');
        }
        
        $this->fileInfo = $fileInfo;
    }
    
    /**
     * 生成文件夹名
     * @return string
     */
    private function getFileName(){
        return md5(microtime(time())).$this->ops['ext'];
    }
    
    /**
     * 普通缩放
     * @param int $dst_w    宽 
     * @param int $dst_h    高
     */
    public function thumb($dst_w,$dst_h){
        
        $dst_image = imagecreatetruecolor($dst_w, $dst_h);
        
        imagecopyresampled($dst_image, $this->ops['srcImage'], 0, 0, 0, 0, $dst_w, $dst_h, $this->fileInfo[0],$this->fileInfo[1]);
        
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($dst_image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($dst_image);
        }
        
        imagedestroy($dst_image);
    }
    
    /**
     * 按尺寸等比缩放
     * @param int $dst_w    宽
     * @param int $dst_h    高
     */
    public function thumbRatioWH($dst_w,$dst_h){
        //换算
        $ratio_orig = $this->fileInfo[0] /  $this->fileInfo[1];
        if($dst_w / $dst_h > $ratio_orig){
            $dst_w = $dst_h *$ratio_orig;
        }else{
            $dst_h = $dst_w / $ratio_orig;
        }
        
        $dst_image = imagecreatetruecolor($dst_w, $dst_h);
        
        imagecopyresampled($dst_image, $this->ops['srcImage'], 0, 0, 0, 0, $dst_w, $dst_h, $this->fileInfo[0],$this->fileInfo[1]);
        
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($dst_image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($dst_image);
        }
        
        imagedestroy($dst_image);
    }
    
    /**
     * 等比缩放
     * @param int $ratio    等比数字
     */
    public function thumbRatio($ratio){
        $dst_w = $this->fileInfo[0] * $ratio;
        $dst_h = $this->fileInfo[1] * $ratio;
        $dst_image = imagecreatetruecolor($dst_w, $dst_h);
        
        imagecopyresampled($dst_image, $this->ops['srcImage'], 0, 0, 0, 0, $dst_w, $dst_h, $this->fileInfo[0],$this->fileInfo[1]);
        
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($dst_image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($dst_image);
        }
        
        imagedestroy($dst_image);
    }
    
    /**
     * 文字水印
     * @param string $text  文字水印
     * @param int $x    x轴
     * @param int $y    y轴
     * @param array $rgb    颜色RGB数组
     * @param int $size 文字size
     * @param int $angle    文字旋转角度
     */
    public function thumbWaterText($text,$x=10, $y=30,$rgb = array(255,0,0),$size=30, $angle=0){
        $image = $this->ops['srcImage'];
        //文字水印颜色
        $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        //字体
        $fontFile = 'font/MSYHBD.TTF';
        
        imagettftext($image, $size, $angle, $x, $y, $color, $fontFile, $text);
        
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($image);
        }
    }
    
    /**
     * 透明文字水印
     * @param string $text  文字水印
     * @param int $x    x轴
     * @param int $y    y轴
     * @param int $alpha 透明值
     * @param array $rgb    颜色RGB数组
     * @param int $size 文字size
     * @param int $angle    文字旋转角度
     */
    public function thumbWaterAlphaText($text,$x=10, $y=30,$alpha=60,$rgb = array(255,0,0),$size=30, $angle=0){
        $image = $this->ops['srcImage'];
        //文字水印颜色
        $color = imagecolorallocatealpha($image, $rgb[0], $rgb[1], $rgb[2],$alpha);
        //字体
        $fontFile = 'font/MSYHBD.TTF';
    
        imagettftext($image, $size, $angle, $x, $y, $color, $fontFile, $text);
    
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($image);
        }
    }
    
    /**
     * 图片水印
     * @param string $imageFile 水印图片路径
     * @param int $x    x轴
     * @param int $y    y轴
     * @param int $pct  透明度(0:不显示，100:完全显示)
     */
    public function thumbWaterImage($imageFile,$x=0, $y=0,$pct=100){
        
        $dstImageInfo = getimagesize($imageFile);
        if(empty($dstImageInfo)){
            exit('传入的文件不是真实图片');
        }
        
        $createFun = str_replace('/', "createfrom", $dstImageInfo['mime']);
        $logo_im = $createFun($imageFile);

        $src_im = $this->ops['srcImage'];

        imagecopymerge($src_im,$logo_im, $x, $y, 0, 0, $dstImageInfo[0], $dstImageInfo[1], $pct);
        
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($src_im,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($src_im);
        }
        
        imagedestroy($src_im);
    }
}

$image = new Image("images/1.jpg",'images/');
// $image->thumb(100, 100);
// $image->thumbRatioWH(150, 250);
// $image->thumbRatio(0.5);
$image->thumbWaterImage('images/cat.jpg');


