<?php
/**
 +------------------------------------------------------------------------------
 * �ļ����ƣ� php_mysql.php
 +------------------------------------------------------------------------------
 * �ļ������� mysql��չ���ݿ�ӿ�
 +------------------------------------------------------------------------------
 */
 
//���ݿ��û���
$username = "root";

//���ݿ�����
$userpass = "root";

//���ݿ�������
$dbhost = "localhost";

//���ݿ���
$dbdatabase = "cmssys";	

//����һ������
$db_connect = mysql_connect($dbhost,$username,$userpass) or die("Unable to connect to the MySQL!");
 
//ѡ��һ����Ҫ���������ݿ�
mysql_select_db($dbdatabase,$db_connect);
 
//ִ��MySQL���
$result = mysql_query("SELECT id,username FROM cms_user");
 
//��ȡ����
$row=mysql_fetch_row($result);

?>