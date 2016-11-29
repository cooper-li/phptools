<?php
/**
 +------------------------------------------------------------------------------
 * 文件名称： char_helper.php
 +------------------------------------------------------------------------------
 * 文件描述： 字符串操作函数库
 +------------------------------------------------------------------------------
 */
/*
* @todo   UTF-8转GB2312
* @param  字符串
* @return 转换后的字符
*/
function str_u2g($str){
	return iconv('UTF-8', 'GB2312//IGNORE', $str);
}
/*
* @todo   GB2312转UTF-8
* @param  字符串
* @return 转换后的字符
*/
function str_g2u($str){
	return iconv('GB2312', 'UTF-8//IGNORE', $str);
}
/*
* @todo   转义全局数组中的字符
* @param  数组索引 数组种类 转义函数 
* @return 转义后的字符串
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
* @todo   格式化打印变量
* @param  变量 标签 输出返回
* @return 变量信息
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
* @todo   返回json数据
* @param  编码 信息 数据
* @return 格式化的json
*/
function show_json($code, $mess='', $data=array()) {
	header('Content-Type: application/json; charset=utf-8');
	$json = array('code'=>$code, 'message'=>$mess, 'data'=>$data);
	$json = json_encode($json);
	exit($json);
}
?>