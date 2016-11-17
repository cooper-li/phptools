<?php
/**
 +------------------------------------------------------------------------------
 * 文件名称： php_pdo.php
 +------------------------------------------------------------------------------
 * 文件描述： pdo扩展数据库接口
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

//数据源名
$dsn='mysql:host='.$dbhost.';dbname='.$dbdatabase.';';

$dbh=new PDO($dsn,$username,$userpass);

$stmt=$dbh->query('SELECT id,username FROM cms_user');

$row=$stmt->fetch();

?>