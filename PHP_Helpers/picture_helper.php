<?php 
/*
* 等比缩放函数(以保存方式实现)
* @param string $picname 被缩放的处理图像源
* @param int $maxx 缩放后图片的最大宽度
* @param int $maxy 缩放后图片的最大高度
* @param string $pre 缩放后图片的前缀名
* @return string 缩放后图片名称
*/
function imageUpdateSize($picname,$maxx = 100,$maxy = 100,$pre = "s_"){
	$info = getimagesize($picname);                //获取图像基本信息
	$w = $info[0];                                 //获取图像的宽度
	$h = $info[1];                                 //获取图像的高度
	//获取原图像的
	switch($info[2]){
		case 1: //gif
			$im = imagecreatefromgif($picname);
			break;
		case 2: //jpg
			$im = imagecreatefromjpeg($picname);
			break;
		case 3: //png
			$im = imagecreatefrompng($picname);
			break;
		default:
			die('图片类型错误!');
	}
	//计算缩放比例
	if(($maxx/$w) > ($maxy/$h)){
		$b = $maxy/$h;
	}else{
		$b = $maxx/$w;
	}
	//计算出缩放后的尺寸
	$nw = floor($w*$b);
	$nh = floor($h*$b);
	//创建一个新的图像源
	$nim = imagecreatetruecolor($nw,$nh);
	//执行等比缩放
	imagecopyresampled($nim,$im,0,0,0,0,$nw,$nh,$w,$h);
	//解析原图像路径和文件名
	$picinfo = pathinfo($picname);
	$newpicname = $picinfo['dirname'].'/'.$pre.$picinfo['basename'];
	//输出图像
	switch($info[2]){
		case 1:
			imagegif($nim,$newpicname);
			break;
		case 2:
			imagejpeg($nim,$newpicname);
			break;
		case 3:
			imagepng($nim,$newpicname);
			break;
	}
	//关闭图片资源
	imagedestroy($im);
	imagedestroy($nim);
	return $newpicname;
}

/*
* 为一张图片添加一个图片水印(以保存方式实现)
* @param string $picname 被缩放的处理图像源
* @param int $logo 水印图片
* @param string $pre 缩放后图片的前缀名
* @return string 缩放后图片名称
*/
function imageUpdateLogo($picname,$logo,$pre = "n_"){
	$picnameinfo = getimagesize($picname);                //获取图像源基本信息
	$logoinfo = getimagesize($logo);                      //获取logo基本信息
	
	//根据图片类型创建出对应图片源
	switch($picnameinfo[2]){
		case 1: //gif
			$im = imagecreatefromgif($picname);
			break;
		case 2: //jpg
			$im = imagecreatefromjpeg($picname);
			break;
		case 3: //png
			$im = imagecreatefrompng($picname);
			break;
		default:
			die('源图片类型错误!');
	}
	//根据logo类型创建出对应图片源
	switch($logoinfo[2]){
		case 1: //gif
			$logoim = imagecreatefromgif($logo);
			break;
		case 2: //jpg
			$logoim = imagecreatefromjpeg($logo);
			break;
		case 3: //png
			$logoim = imagecreatefrompng($logo);
			break;
		default:
			die('logo图片类型错误!');
	}
	
	//执行图片水印处理
	imagecopyresampled($im,$logoim,$picnameinfo[0]-$logoinfo[0],$picnameinfo[1]-$logoinfo[1],0,0,$logoinfo[0],$logoinfo[1],$logoinfo[0],$logoinfo[1]);
	
	//解析原图像路径和文件名
	$picinfo = pathinfo($picname);
	$newpicname = $picinfo['dirname'].'/'.$pre.$picinfo['basename'];
	//输出图像
	switch($picnameinfo[2]){
		case 1:
			imagegif($im,$newpicname);
			break;
		case 2:
			imagejpeg($im,$newpicname);
			break;
		case 3:
			imagepng($im,$newpicname);
			break;
	}
	//关闭图片资源
	imagedestroy($im);
	imagedestroy($logoim);
	return $newpicname;
}


