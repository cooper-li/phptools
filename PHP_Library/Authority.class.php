<?
class Authority{
	
	//权限列表
	private $auth_list = array();
	
	//管理员标识
	private $admin = '';
	
	//登录最长时间
	private $max_time;
	
	//用户存储信息
	private $userinfo = array('database' => '','table' => '','username' => '','password' => '');
	
	//数据库信息
	private $dbinfo = array('dbhost' => '','name' => '','pawd' => ''); 
	
	//构造函数初始化
	public function __construct($auth_arr,$admin,$time,$user_table,$data_info){
		$this->auth_list = $auth_arr;
		$this->admin = $admin;
		$this->max_time = $time;
		$this->userinfo = $user_table;
		$this->dbinfo = $data_info;
	}
	
	//验证是否管理员
	public function isAdmin($username){
		return $this->admin === $username;
	}

	// 验证是否有权限
	public function isAllow($auth_info){
		return in_array($auth_info, $this->auth_list) ? true : false;
	}

	// 验证是否登录
	public function isLogin(){
		// 校验 tokey
		$tokey1 = $this->getTokey();
		$tokey2 = $_SESSION['tokey'];
		$overtime = $_SESSION['time'] + $this->max_time;
		if(!empty($tokey2) && $tokey1 === $tokey2 && $overtime > time())
			return true;
		else
			return false;
	}
	
	// 登录认证过程
	public function loginCheck($username,$password){
		//从文件中获取用户信息
		$user_info = $this->getUserData($username);
		//验证用户输入的用户名和密码
		if ($user_info['username'] == $username && $user_info['password'] == md5($password)) {
			//验证通过，设置SESSION信息
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
	// 退出登录
	public function loginOut(){
	    //清空SESSION
		$_SESSION = array();
		unset($_SESSION);
		exit();
	}
	
	// 产生随机值
	public function getTokey(){
		return md5($_SERVER['HTTP_USER_AGENT'] . date('Y-m-d'));
	}
	
	//获取用户信息
	public function getUserData($username){
		
		//获取表和字段值
		$table_name = $this->userinfo['table'];
		$user_field = $this->userinfo['username'];
		$pawd_field = $this->userinfo['password'];

		//根据配置连接数据库
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("连接数据库失败!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		
		$sql = "select ".$user_field.",".$pawd_field." from ".$table_name." where ".$user_field."=".$username;
		$result = mysql_query($sql);
		
		$row = mysql_fetch_assoc($result);
		
		//获取用户名和密码
		$user_arr['username'] = $row[$user_field];
		$user_arr['password'] = $row[$pawd_field];
		mysql_free_result($result);
		mysql_close($db_connect);
		//返回
		return $user_arr;

	}
	//添加用户
	public function addUserData($username,$password){
		
		$table_name = $this->userinfo['table'];
		$user_field = $this->userinfo['username'];
		$pawd_field = $this->userinfo['password'];
		//加密密码
		$hash_paswd = md5($password);
		//连接数据库并添加用户
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("连接数据库失败!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		$sql = "insert into ".$table_name."(".$user_field.",".$pawd_field.") values('".$username."','".$hash_paswd."')";
		$result = mysql_query($sql,$db_connect);
		return $result;
	}
	//更新密码
	public function updateUserData($username,$newpassword){
		
		$table_name = $this->userinfo['table'];
		
		//新密码不为空
		if(!empty($newpassword))
			$new_pawd = md5($newpassword);
		else
			return false
		$user_field = $this->userinfo['username'];
		$pawd_field = $this->userinfo['password'];
		//连接数据库并更新用户密码
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("连接数据库失败!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		$sql = "update ".$table_name." set ".$pawd_field."=".$new_pawd;
		$result = mysql_query($sql,$db_connect);
		return $result;
	}
	//删除用户
	public function delUserData($username){
		$table_name = $this->userinfo['table'];
		$user_field = $this->userinfo['username'];
		//连接数据库并删除用户记录
		$db_connect = mysql_connect($this->dbinfo['dbhost'],$this->dbinfo['name'],$this->dbinfo['pawd']) or die("连接数据库失败!");
		mysql_select_db($this->userinfo['database'],$db_connect);
		$sql = "delete * from ".$table_name." where ".$user_field."=".$username;
		$result = mysql_query($sql,$db_connect);
		return $result;
	}
}

?>
