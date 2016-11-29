<?php 
/*
* �ȱ����ź���(�Ա��淽ʽʵ��)
* @param string $picname �����ŵĴ���ͼ��Դ
* @param int $maxx ���ź�ͼƬ�������
* @param int $maxy ���ź�ͼƬ�����߶�
* @param string $pre ���ź�ͼƬ��ǰ׺��
* @return string ���ź�ͼƬ����
*/
function imageUpdateSize($picname,$maxx = 100,$maxy = 100,$pre = "s_"){
	$info = getimagesize($picname);                //��ȡͼ�������Ϣ
	$w = $info[0];                                 //��ȡͼ��Ŀ��
	$h = $info[1];                                 //��ȡͼ��ĸ߶�
	//��ȡԭͼ���
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
			die('ͼƬ���ʹ���!');
	}
	//�������ű���
	if(($maxx/$w) > ($maxy/$h)){
		$b = $maxy/$h;
	}else{
		$b = $maxx/$w;
	}
	//��������ź�ĳߴ�
	$nw = floor($w*$b);
	$nh = floor($h*$b);
	//����һ���µ�ͼ��Դ
	$nim = imagecreatetruecolor($nw,$nh);
	//ִ�еȱ�����
	imagecopyresampled($nim,$im,0,0,0,0,$nw,$nh,$w,$h);
	//����ԭͼ��·�����ļ���
	$picinfo = pathinfo($picname);
	$newpicname = $picinfo['dirname'].'/'.$pre.$picinfo['basename'];
	//���ͼ��
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
	//�ر�ͼƬ��Դ
	imagedestroy($im);
	imagedestroy($nim);
	return $newpicname;
}

/*
* Ϊһ��ͼƬ���һ��ͼƬˮӡ(�Ա��淽ʽʵ��)
* @param string $picname �����ŵĴ���ͼ��Դ
* @param int $logo ˮӡͼƬ
* @param string $pre ���ź�ͼƬ��ǰ׺��
* @return string ���ź�ͼƬ����
*/
function imageUpdateLogo($picname,$logo,$pre = "n_"){
	$picnameinfo = getimagesize($picname);                //��ȡͼ��Դ������Ϣ
	$logoinfo = getimagesize($logo);                      //��ȡlogo������Ϣ
	
	//����ͼƬ���ʹ�������ӦͼƬԴ
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
			die('ԴͼƬ���ʹ���!');
	}
	//����logo���ʹ�������ӦͼƬԴ
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
			die('logoͼƬ���ʹ���!');
	}
	
	//ִ��ͼƬˮӡ����
	imagecopyresampled($im,$logoim,$picnameinfo[0]-$logoinfo[0],$picnameinfo[1]-$logoinfo[1],0,0,$logoinfo[0],$logoinfo[1],$logoinfo[0],$logoinfo[1]);
	
	//����ԭͼ��·�����ļ���
	$picinfo = pathinfo($picname);
	$newpicname = $picinfo['dirname'].'/'.$pre.$picinfo['basename'];
	//���ͼ��
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
	//�ر�ͼƬ��Դ
	imagedestroy($im);
	imagedestroy($logoim);
	return $newpicname;
}


