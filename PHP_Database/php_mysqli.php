<?php
/**
 +------------------------------------------------------------------------------
 * 文件名称： php_mysqli.php
 +------------------------------------------------------------------------------
 * 文件描述： mysqli扩展数据库接口
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

$db=new mysqli($dbhost,$username,$userpass,$dbdatabase);

if(mysqli_connect_error()){
   echo 'Could not connect to database.';
   exit;
}
 
$result=$db->query("SELECT id,username FROM cms_user");

$row=$result->fetch_row();

?>