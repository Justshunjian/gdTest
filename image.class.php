<?php
/**
 * ͼƬ������
 * @author lvfk
 *
 */
class Image {
    /**
     * ͼƬ�ļ���Ϣ
     * @var array
     */
    private $fileInfo;
    /**
     * ��������
     * @var array
     */
    private $ops;
    
    /**
     * ���캯��
     * @param string $fileName
     * @param string $savePath ����ͼƬ·����Ĭ������������
     */
    public function __construct($fileName,$savePath='images/'){
        if(empty($fileName)){
            die('ͼƬ·��Ϊ��');
        }
        
        //����ļ��ǲ�����ʵͼƬ
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
     * �ͷ���Դ
     */
    public function __destruct(){
        unset($this->fileInfo);
        unset($this->ops);
    }
    
    /**
     * ����ļ��ǲ�����ʵͼƬ
     * @param string $filename
     */
    private function getImageInfo($filename){
        $fileInfo = getimagesize($filename);
        if(empty($fileInfo)){
            die('�ļ�������ʵͼƬ');
        }
        
        $this->fileInfo = $fileInfo;
    }
    
    /**
     * �����ļ�����
     * @return string
     */
    private function getFileName(){
        return md5(microtime(time())).$this->ops['ext'];
    }
    
    /**
     * ��ͨ����
     * @param int $dst_w    �� 
     * @param int $dst_h    ��
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
     * ���ߴ�ȱ�����
     * @param int $dst_w    ��
     * @param int $dst_h    ��
     */
    public function thumbRatioWH($dst_w,$dst_h){
        //����
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
     * �ȱ�����
     * @param int $ratio    �ȱ�����
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
     * ����ˮӡ
     * @param string $text  ����ˮӡ
     * @param int $x    x��
     * @param int $y    y��
     * @param array $rgb    ��ɫRGB����
     * @param int $size ����size
     * @param int $angle    ������ת�Ƕ�
     */
    public function thumbWaterText($text,$x=10, $y=30,$rgb = array(255,0,0),$size=30, $angle=0){
        $image = $this->ops['srcImage'];
        //����ˮӡ��ɫ
        $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        //����
        $fontFile = 'font/MSYHBD.TTF';
        
        imagettftext($image, $size, $angle, $x, $y, $color, $fontFile, $text);
        
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($image);
        }
    }
    
    /**
     * ͸������ˮӡ
     * @param string $text  ����ˮӡ
     * @param int $x    x��
     * @param int $y    y��
     * @param int $alpha ͸��ֵ
     * @param array $rgb    ��ɫRGB����
     * @param int $size ����size
     * @param int $angle    ������ת�Ƕ�
     */
    public function thumbWaterAlphaText($text,$x=10, $y=30,$alpha=60,$rgb = array(255,0,0),$size=30, $angle=0){
        $image = $this->ops['srcImage'];
        //����ˮӡ��ɫ
        $color = imagecolorallocatealpha($image, $rgb[0], $rgb[1], $rgb[2],$alpha);
        //����
        $fontFile = 'font/MSYHBD.TTF';
    
        imagettftext($image, $size, $angle, $x, $y, $color, $fontFile, $text);
    
        if(isset($this->ops['savePath'])){
            $this->ops['outFun']($image,$this->ops['savePath'].$this->getFileName());
        }else{
            $this->ops['outFun']($image);
        }
    }
    
    /**
     * ͼƬˮӡ
     * @param string $imageFile ˮӡͼƬ·��
     * @param int $x    x��
     * @param int $y    y��
     * @param int $pct  ͸����(0:����ʾ��100:��ȫ��ʾ)
     */
    public function thumbWaterImage($imageFile,$x=0, $y=0,$pct=100){
        
        $dstImageInfo = getimagesize($imageFile);
        if(empty($dstImageInfo)){
            exit('������ļ�������ʵͼƬ');
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


