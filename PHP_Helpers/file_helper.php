<?php
/**
 +------------------------------------------------------------------------------
 * �ļ����ƣ� file_helper.php
 +------------------------------------------------------------------------------
 * �ļ������� �ļ�����������
 +------------------------------------------------------------------------------
 */
 
/*
* @todo   ��ȡĿ¼��
* @param  ·��
* @return Ŀ¼��
*/ 
function get_dirname($path){
	return dirname($path);
}
/*
* @todo   ��ȡ�ļ�ȫ��
* @param  ·��
* @return �ļ���
*/ 
function get_basename($path){
	$path = rtrim($path, ' /\\');
	$path = explode('/', $path);
	return end($path);
}
/*
* @todo   ��ȡ�ļ���(ȥ����׺) 
* @param  ·��
* @return �ļ���
*/
function get_filename($path){
	$path = get_basename($path);
	$path = explode('.', $path);
	return $path[0];
}
/*
* @todo   ��ȡ�ļ���׺
* @param  ·��
* @return �ļ���׺
*/
function get_fileext($path){
	$path = get_basename($path);
	$path = explode('.', $path);
	return isset($path[1]) ? strtolower(end($path)) : '';
}
/*
* @todo   ��ȡ·����Ϣ
* @param  ·��
* @return ·����Ϣ���� 
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
* @todo   �ļ���С��ʽ��
* @param  �ļ���С ��λ
* @return ��ʽ������ļ���λ
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
* @todo   ��ȡ�ļ�����Ȩ��
* @param  �ļ�·�� ��ʾ��ʽ
* @return �ļ�Ȩ��
*/
function get_deal_chmod($path, $format=false){
	$perms = fileperms($path);
	if(!$format){
		$mode = substr(sprintf('%o', $perms), -4);
	}else{
		//������
		$mode = '';
		$mode .= (($perms & 0x0100) ? 'r' : '-');
		$mode .= (($perms & 0x0080) ? 'w' : '-');
		$mode .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

		//������
		$mode .= (($perms & 0x0020) ? ' r' : ' -');
		$mode .= (($perms & 0x0010) ? 'w' : '-');
		$mode .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

		//������
		$mode .= (($perms & 0x0004) ? ' r' : ' -');
		$mode .= (($perms & 0x0002) ? 'w' : '-');
		$mode .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
	}
	return $mode;
}
/*
* @todo   ҳ���ض���
* @param  URL
* @return NULL
*/
function file_redirect($url){
	exit("<script type='text/javascript'>document.location.href = '{$url}';</script>");
}

/*
* @todo �ļ��ϴ�������
* @param string $filename Ҫ�ϴ����ļ�������
* @param string $path �ϴ��ļ��ı���·��
* @oaram array ������ļ�����
* $return array ������Ԫ ["error"] true �ɹ� false ʧ��
* 						 ['info'] ���ʧ����Ϣ��ɹ��ļ���		    
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
				$upinfo['info'] = "�ϴ��ļ������������ļ��涨ֵ!";
				break;
			case 2:
				$upinfo['info'] = "�ϴ��ļ������˱�ѡ��涨ֵ!";
				break;
			case 3:
				$upinfo['info'] = "�ϴ��ļ����ֳɹ�!";
				break;
			case 4:
				$upinfo['info'] = "δѡ���ϴ��ļ�!";
				break;
			case 6:
				$upinfo['info'] = "�Ҳ�����ʱ�ļ���!";
				break;
			case 7:
				$upinfo['info'] = "�ļ�д��ʧ��!";
				break;
		}
		return $upinfo;
	}	
	
		if($upfile['size'] > 100000 ){
			$upinfo['info'] = '�ϴ���С��������';
			return $upinfo;
		}
		
		
		if(!in_array($upfile['type'],$typelist)){
			$upinfo['info'] = '�ϴ����ͳ�������!';
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
			$upinfo['info'] = '����һ���ϴ��ļ�!';
			return $upinfo;
		}
	
}

?>