<?php
/**
 +------------------------------------------------------------------------------
 * 文件名称： file_helper.php
 +------------------------------------------------------------------------------
 * 文件描述： 文件操作函数库
 +------------------------------------------------------------------------------
 */
 
/*
* @todo   获取目录名
* @param  路径
* @return 目录名
*/ 
function get_dirname($path){
	return dirname($path);
}
/*
* @todo   获取文件全名
* @param  路径
* @return 文件名
*/ 
function get_basename($path){
	$path = rtrim($path, ' /\\');
	$path = explode('/', $path);
	return end($path);
}
/*
* @todo   获取文件名(去除后缀) 
* @param  路径
* @return 文件名
*/
function get_filename($path){
	$path = get_basename($path);
	$path = explode('.', $path);
	return $path[0];
}
/*
* @todo   获取文件后缀
* @param  路径
* @return 文件后缀
*/
function get_fileext($path){
	$path = get_basename($path);
	$path = explode('.', $path);
	return isset($path[1]) ? strtolower(end($path)) : '';
}
/*
* @todo   获取路径信息
* @param  路径
* @return 路径信息数组 
*/
function get_pathinfo($path){
	$info = array(
	'dirname'   => get_dirname($path),
	'basename'  => get_basename($path),
	'filename'  => get_filename($path),
	'extension' => get_fileext($path),
	);
	return $info;
}
/*
* @todo   文件大小格式化
* @param  文件大小 单位
* @return 格式化后的文件单位
*/
function get_deal_size($size, $did=0){
	$dna = array('Byte','KB','MB','GB','TB','PB');
	while ($size >= 900){
		$size = round($size*100/1024)/100;
		$did++;
	}
	return $size.' '.$dna[$did];
}
/*
* @todo   获取文件操作权限
* @param  文件路径 显示方式
* @return 文件权限
*/
function get_deal_chmod($path, $format=false){
	$perms = fileperms($path);
	if(!$format){
		$mode = substr(sprintf('%o', $perms), -4);
	}else{
		//所有者
		$mode = '';
		$mode .= (($perms & 0x0100) ? 'r' : '-');
		$mode .= (($perms & 0x0080) ? 'w' : '-');
		$mode .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

		//所属组
		$mode .= (($perms & 0x0020) ? ' r' : ' -');
		$mode .= (($perms & 0x0010) ? 'w' : '-');
		$mode .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

		//其他人
		$mode .= (($perms & 0x0004) ? ' r' : ' -');
		$mode .= (($perms & 0x0002) ? 'w' : '-');
		$mode .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
	}
	return $mode;
}
/*
* @todo   页面重定向
* @param  URL
* @return NULL
*/
function file_redirect($url){
	exit("<script type='text/javascript'>document.location.href = '{$url}';</script>");
}

/*
* @todo 文件上传处理函数
* @param string $filename 要上传的文件表单项名
* @param string $path 上传文件的保存路径
* @oaram array 允许的文件类型
* $return array 两个单元 ["error"] true 成功 false 失败
* 						 ['info'] 存放失败信息或成功文件名		    
*/
function uploadFile($filename,$path,$typelist = null){
	
	$upfile = $_FILES[$filename];
	
	if(empty($typelist)){
		$typelist = array('image/gif','image/jpg','image/jpeg','image/png');	
	}
	
	$upinfo = array('error'=>true,'info'=>'');
	
	
	if($upfile['error'] > 0){
	
		switch($upfile['error']){
			case 1:
				$upinfo['info'] = "上传文件超出了配置文件规定值!";
				break;
			case 2:
				$upinfo['info'] = "上传文件超出了表单选项规定值!";
				break;
			case 3:
				$upinfo['info'] = "上传文件部分成功!";
				break;
			case 4:
				$upinfo['info'] = "未选择上传文件!";
				break;
			case 6:
				$upinfo['info'] = "找不到临时文件夹!";
				break;
			case 7:
				$upinfo['info'] = "文件写入失败!";
				break;
		}
		return $upinfo;
	}	
	
		if($upfile['size'] > 100000 ){
			$upinfo['info'] = '上传大小超出限制';
			return $upinfo;
		}
		
		
		if(!in_array($upfile['type'],$typelist)){
			$upinfo['info'] = '上传类型超出限制!';
			return $upinfo;
		}

		$fileinfo = pathinfo($upfile["name"]);

		do{
			$newfile = date('YmdHis').rand(1000,9999).".".$fileinfo['extension'];
		}while(file_exists($path.$newfile));

		if(is_uploaded_file($upfile['tmp_name'])){
			if(move_uploaded_file($upfile['tmp_name'],$path.$newfile)){
				$upinfo['error'] = false;
				$upinfo['info'] = $path.$newfile;
				return $upinfo;
			}
		}else{
			$upinfo['info'] = '不是一个上传文件!';
			return $upinfo;
		}
	
}

?>