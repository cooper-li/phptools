<?php
/**
 +------------------------------------------------------------------------------
 * �ļ����ƣ� php_mysqli.php
 +------------------------------------------------------------------------------
 * �ļ������� mysqli��չ���ݿ�ӿ�
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

$db=new mysqli($dbhost,$username,$userpass,$dbdatabase);

if(mysqli_connect_error()){
   echo 'Could not connect to database.';
   exit;
}
 
$result=$db->query("SELECT id,username FROM cms_user");

$row=$result->fetch_row();

?>