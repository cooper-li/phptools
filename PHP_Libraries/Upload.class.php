<?php

class Upload {
	
	public $upfile;
	
	public $filepath;
	
	public $maxsize;
	
	public $mesg = array('error'=>true,'info'=>'');
	
	public $typelist = array('image/jpeg','image/png','image/jpg','image/gif','text/plain');
	
	public function __construct($filename,$filepath,$maxsize){
		$this->upfile = $_FILES[$filename];
		$this->filepath = $filepath;
		$this->maxsize = $maxsize;
	}
	
	public function do_upload(){
		
		if($this->upfile['error'] > 0){
	
			switch($this->upfile['error']){
				case 1:
					$this->mesg['info'] = "上传文件超出了配置文件规定值!";
					break;
				case 2:
					$this->mesg['info'] = "上传文件超出了表单选项规定值!";
					break;
				case 3:
					$this->mesg['info'] = "上传文件部分成功!";
					break;
				case 4:
					$this->mesg['info'] = "未选择上传文件!";
					break;
				case 6:
					$this->mesg['info'] = "找不到临时文件夹!";
					break;
				case 7:
					$this->mesg['info'] = "文件写入失败!";
					break;
			return $this->mesg;
			}
	
		}
		if($this->upfile['size'] > $this->maxsize ){
			$this->mesg['info'] = '上传文件超出限制!';
			return $this->mesg;
		}

		if(!in_array($this->upfile['type'],$this->typelist)){
			$this->mesg['info'] = '上传类型超出限制!';
			return $this->mesg;
		}
		
		$fileinfo = pathinfo($this->upfile["name"]);

		do{
			$newfile = date('YmdHis').rand(1000,9999).".".$fileinfo['extension'];
		}while(file_exists($this->filepath.$newfile));

		if(is_uploaded_file($this->upfile['tmp_name'])){
			
			if(move_uploaded_file($this->upfile['tmp_name'],$this->filepath.$newfile)){
				$this->mesg['error'] = false;
				$this->mesg['info'] = $this->filepath.$newfile;
				return $this->mesg;
			}
		}else{
			$this->mesg['info'] = '不是一个上传文件!';
			return $this->mesg;
		}
		
	}
}