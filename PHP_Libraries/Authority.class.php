<?
class Authority{
	
	//Ȩ���б�
	private $auth_list = array();
	
	//����Ա��ʶ
	private $admin = '';
	
	//��¼�ʱ��
	private $max_time;
	
	//�û��洢��Ϣ
	private $userinfo = array('database' => '','table' => '','username' => '','password' => '');
	
	//���ݿ���Ϣ
	private $dbinfo = array('dbhost' => '','name' => '','pawd' => ''); 
	
	//���캯����ʼ��
	public function __construct($auth_arr,$admin,$time,$user_table,$data_info){
		$this->auth_list = $auth_arr;
		$this->admin = $admin;
		$this->max_time = $time;
		$this->userinfo = $user_table;
		$this->dbinfo = $data_info;
	}
	
	//��֤�Ƿ����Ա
	public function isAdmin($username){
		return $this->admin === $username;
	}

	// ��֤�Ƿ���Ȩ��
	public function isAllow($auth_info){
		return in_array($auth_info, $this->auth_list) ? true : false;
	}

	// ��֤�Ƿ��¼
	public function isLogin(){
		// У�� tokey
		$tokey1 = $this->getTokey();
		$tokey2 = $_SESSION['tokey'];
		$overtime = $_SESSION['time'] + $this->max_time;
		if(!empty($tokey2) && $tokey1 === $tokey2 && $overtime > time())
			return true;
		else
			return false;
	}
	
	// ��¼��֤����
	public function loginCheck($username,$password){
		//���ļ��л�ȡ�û���Ϣ
		$user_info = $this->getUserData($username);
		//��֤�û�������û���������
		if ($user_info['username'] == $username && $user_info['password'] == md5($password)) {
			//��֤ͨ��������SESSION��Ϣ
			$_SESSION = array();
			$_SESSION['authority'] = $user_info['authority'];
			$_SESSION['username'] = $user_info['username'];
			$_SESSION['tokey'] = $this->getTokey();
			$_SESSION['time'] = time();
			return true;
		} else {
			return false;
		}
	}
	// �˳���¼
	public function loginOut(){
	    //���SESSION
		$_SESSION = array();
		unset($_SESSION);
		exit();
	}
	
	// �������ֵ
	public function getTokey(){
		return md5($_SERVER['HTTP_USER_AGENT'] . date('Y-m-d'));
	}
	
	//��ȡ�û���Ϣ
	public function getUserData($username){
		
		//��ȡ����ֶ�ֵ
		$table_name = $this->userinfo['table'];
		$user_field = $this->userinfo['username'];
		$pawd_field = $this->userinfo['password'];

		//���������������ݿ�
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("�������ݿ�ʧ��!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		
		$sql = "select ".$user_field.",".$pawd_field." from ".$table_name." where ".$user_field."=".$username;
		$result = mysql_query($sql);
		
		$row = mysql_fetch_assoc($result);
		
		//��ȡ�û���������
		$user_arr['username'] = $row[$user_field];
		$user_arr['password'] = $row[$pawd_field];
		mysql_free_result($result);
		mysql_close($db_connect);
		//����
		return $user_arr;

	}
	//����û�
	public function addUserData($username,$password){
		
		$table_name = $this->userinfo['table'];
		$user_field = $this->userinfo['username'];
		$pawd_field = $this->userinfo['password'];
		//��������
		$hash_paswd = md5($password);
		//�������ݿⲢ����û�
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("�������ݿ�ʧ��!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		$sql = "insert into ".$table_name."(".$user_field.",".$pawd_field.") values('".$username."','".$hash_paswd."')";
		$result = mysql_query($sql,$db_connect);
		return $result;
	}
	//��������
	public function updateUserData($username,$newpassword){
		
		$table_name = $this->userinfo['table'];
		
		//�����벻Ϊ��
		if(!empty($newpassword))
			$new_pawd = md5($newpassword);
		else
			return false
		$user_field = $this->userinfo['username'];
		$pawd_field = $this->userinfo['password'];
		//�������ݿⲢ�����û�����
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("�������ݿ�ʧ��!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		$sql = "update ".$table_name." set ".$pawd_field."=".$new_pawd;
		$result = mysql_query($sql,$db_connect);
		return $result;
	}
	//ɾ���û�
	public function delUserData($username){
		$table_name = $this->userinfo['table'];
		$user_field = $this->userinfo['username'];
		//�������ݿⲢɾ���û���¼
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("�������ݿ�ʧ��!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		$sql = "delete * from ".$table_name." where ".$user_field."=".$username;
		$result = mysql_query($sql,$db_connect);
		return $result;
	}
}

?>
