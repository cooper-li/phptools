<?php
class Thumb{
	
	//ԭͼƬ·��
	private $resFile = '';
	//��ͼƬ·��
	private $newFile = '';
	//��ͼƬ���
	private $newWidth = '';
	//��ͼƬ�߶�
	private $newHeight = '';
	
	//���췽��
	public function __construct($oldpath,$newpath,$width = 48,$height = 48){
		$this->resFile = $oldpath;
		$this->newFile = $newpath;
		$this->newWidth = $width;
		$this->newHeight = $height;
	}
	
	//��������ͼ
	public function create(){
		
		//��ȡԭͼ����Ϣ
		$info = $this->getImageInfo();
		if(!$info) return false;
		
		//������������
		$new_info = $this->tmbEffects($info['width'], $info['height'],  $this->newWidth, $this->newHeight);
		
		//����ͼ��
		if($info['mime'] == 'image/jpeg'){
			$img = imagecreatefromjpeg($this->resFile);
		}elseif ($info['mime'] == 'image/png'){
			$img = imagecreatefrompng($this->resFile);
		}elseif ($info['mime'] == 'image/gif'){
			$img = imagecreatefromgif($this->resFile);
		}else{
			return false;
		}
		
		//����ͼ��
		if ($img &&  false != ($tmp = imagecreatetruecolor($this->newWidth, $this->newHeight))){
			//����ͼ�񲢵�����С
			if (!imagecopyresampled($tmp, $img, 0, 0, $new_info[0], $new_info[1], $this->newWidth, $this->newHeight, $new_info[2], $new_info[3])) {
				return false;
			}
			//���ͼ���ļ�
			$result = imagejpeg($tmp, $this->newFile, 80);
			//����ͼ��
			imagedestroy($img);
			imagedestroy($tmp);
		}
		
		return $result ? true : false;
	}
	
	//��ʾ�����ͼƬ���������
	public function show(){
		$file = $this->newFile;
		header('Content-type: image/jpeg');
		header('Content-length: ' . filesize($file));
		readfile($file);
	}
	
	//��ȡͼ����Ϣ
	private function getImageInfo() {
		//��ȡͼ��ߴ���Ϣ
		$imageInfo = getimagesize($this->resFile);
		//�ɹ���ȡ�ߴ���Ϣ
		if (false !== $imageInfo) {
			//��ȡͼ������
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
			//��ȡͼ���С
			$imageSize = filesize($this->resFile);
			//��װͼ����Ϣ
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
	//����ԭͼ���Ŀ��ͼ�񣬽����������ݷ���
	private function tmbEffects($resWidth, $resHeight, $tmbWidth, $tmbHeight, $crop = true) {
		//��ʼ��
		$x = $y = 0;
		$size_w = $size_h = 0;

		//�������ű���
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
		//�������ź�Ķ�������Ϳ�Ⱥ͸߶�
		return array($x, $y, $size_w, $size_h);
	}

	
}

?>