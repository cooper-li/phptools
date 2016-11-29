<?php
/**
 +------------------------------------------------------------------------------
 * �ļ����ƣ� char_helper.php
 +------------------------------------------------------------------------------
 * �ļ������� �ַ�������������
 +------------------------------------------------------------------------------
 */
/*
* @todo   UTF-8תGB2312
* @param  �ַ���
* @return ת������ַ�
*/
function str_u2g($str){
	return iconv('UTF-8', 'GB2312//IGNORE', $str);
}
/*
* @todo   GB2312תUTF-8
* @param  �ַ���
* @return ת������ַ�
*/
function str_g2u($str){
	return iconv('GB2312', 'UTF-8//IGNORE', $str);
}
/*
* @todo   ת��ȫ�������е��ַ�
* @param  �������� �������� ת�庯�� 
* @return ת�����ַ���
*/
function str_gpc($key, $type='g', $func=null){
	switch (strtoupper($type)){
		case 'G': $var = &$_GET;     break;
		case 'P': $var = &$_POST;    break;
		case 'R': $var = &$_REQUEST; break;
		case 'C': $var = &$_COOKIE;  break;
		case 'S': $var = &$_SESSION; break;
	}
	$data = isset($var[$key]) ? $var[$key] : null;
	$data = isset($func) ? $func($data) : $data;
	return $data;
}
/*
* @todo   ��ʽ����ӡ����
* @param  ���� ��ǩ �������
* @return ������Ϣ
*/
function str_dump($var, $label='', $echo=true){
	ob_start();
	var_dump($var);
	$output = ob_get_clean();
	$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
	$output = '<pre>' . $label .' '. htmlspecialchars($output, ENT_QUOTES) . '</pre>';
	if ($echo){
		echo($output);
	} else{
		return $output;
	}
}
/*
* @todo   ����json����
* @param  ���� ��Ϣ ����
* @return ��ʽ����json
*/
function show_json($code, $mess='', $data=array()) {
	header('Content-Type: application/json; charset=utf-8');
	$json = array('code'=>$code, 'message'=>$mess, 'data'=>$data);
	$json = json_encode($json);
	exit($json);
}
?>