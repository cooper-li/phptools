<?php
/**
 +------------------------------------------------------------------------------
 * 文件名称： php_mysql.php
 +------------------------------------------------------------------------------
 * 文件描述： mysql扩展数据库接口
 +------------------------------------------------------------------------------
 */
 
//数据库用户名
$username = "root";

//数据库密码
$userpass = "root";

//数据库主机名
$dbhost = "localhost";

//数据库名
$dbdatabase = "cmssys";	

//生成一个连接
$db_connect = mysql_connect($dbhost,$username,$userpass) or die("Unable to connect to the MySQL!");
 
//选择一个需要操作的数据库
mysql_select_db($dbdatabase,$db_connect);
 
//执行MySQL语句
$result = mysql_query("SELECT id,username FROM cms_user");
 
//提取数据
$row=mysql_fetch_row($result);

?>