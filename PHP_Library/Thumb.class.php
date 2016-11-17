<?php
class Thumb{
	
	//原图片路径
	private $resFile = '';
	//新图片路径
	private $newFile = '';
	//新图片宽度
	private $newWidth = '';
	//新图片高度
	private $newHeight = '';
	
	//构造方法
	public function __construct($oldpath,$newpath,$width = 48,$height = 48){
		$this->resFile = $oldpath;
		$this->newFile = $newpath;
		$this->newWidth = $width;
		$this->newHeight = $height;
	}
	
	//创建缩略图
	public function create(){
		
		//获取原图像信息
		$info = $this->getImageInfo();
		if(!$info) return false;
		
		//生成缩略数据
		$new_info = $this->tmbEffects($info['width'], $info['height'],  $this->newWidth, $this->newHeight);
		
		//创建图像
		if($info['mime'] == 'image/jpeg'){
			$img = imagecreatefromjpeg($this->resFile);
		}elseif ($info['mime'] == 'image/png'){
			$img = imagecreatefrompng($this->resFile);
		}elseif ($info['mime'] == 'image/gif'){
			$img = imagecreatefromgif($this->resFile);
		}else{
			return false;
		}
		
		//创建图像
		if ($img &&  false != ($tmp = imagecreatetruecolor($this->newWidth, $this->newHeight))){
			//拷贝图像并调整大小
			if (!imagecopyresampled($tmp, $img, 0, 0, $new_info[0], $new_info[1], $this->newWidth, $this->newHeight, $new_info[2], $new_info[3])) {
				return false;
			}
			//输出图象到文件
			$result = imagejpeg($tmp, $this->newFile, 80);
			//销毁图象
			imagedestroy($img);
			imagedestroy($tmp);
		}
		
		return $result ? true : false;
	}
	
	//显示并输出图片到浏览器中
	public function show(){
		$file = $this->newFile;
		header('Content-type: image/jpeg');
		header('Content-length: ' . filesize($file));
		readfile($file);
	}
	
	//获取图像信息
	private function getImageInfo() {
		//获取图像尺寸信息
		$imageInfo = getimagesize($this->resFile);
		//成功获取尺寸信息
		if (false !== $imageInfo) {
			//获取图像类型
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
			//获取图像大小
			$imageSize = filesize($this->resFile);
			//封装图像信息
			$info = array(
				'width' => $imageInfo[0], 
				'height' => $imageInfo[1],
				'type' => $imageType,
				'size' => $imageSize,
				'mime' => $imageInfo['mime']
			);
			return $info;
		} else {
			return false;
		}
	}
	//处理原图像和目标图像，将处理后的数据返回
	private function tmbEffects($resWidth, $resHeight, $tmbWidth, $tmbHeight, $crop = true) {
		//初始化
		$x = $y = 0;
		$size_w = $size_h = 0;

		//计算缩放比例
		$scale1  = $resWidth / $resHeight;
		$scale2  = $tmbWidth / $tmbHeight;
		
		if ($scale1 < $scale2){
			$size_w = $resWidth;
			$size_h = round($size_w * ($tmbHeight / $tmbWidth));
			$y = ceil(($resHeight - $size_h)/2);
		}else{
			$size_h = $resHeight;
			$size_w = round($size_h * ($tmbWidth / $tmbHeight));
			$x = ceil(($resWidth - $size_w)/2);
		}
		//返回缩放后的定点坐标和宽度和高度
		return array($x, $y, $size_w, $size_h);
	}

	
}

?>