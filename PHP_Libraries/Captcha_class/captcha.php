<?php
/**
 * 实例化验证码文件
 * ========================================================
 * 说明：本文件实例化验证码类，表单页面的图片地址指向本页面
 */
require('captcha.class.php');
//配置长度
$length = 8;
//配置宽度
$width = 150;
//配置高度
$height = 30;
//配置类型
$type = 2;
//实例化对象
$cal = new Captcha($length,$width,$height,$type);
//输出验证码
$cal->show_captcha();

?>

