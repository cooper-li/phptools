<?php
/**
 *+---------------------------------------------------------------------
 * 文件名称 php_mysql.class.php
 *+---------------------------------------------------------------------
 * 文件描述 php连接数据库操作类
 *+---------------------------------------------------------------------
 */
 
class mysql {
	//数据库主机
	private $db_host;
	//数据库用户名
    private $db_user;
	//数据库密码
    private $db_pwd;     
	//数据库名
    private $db_database;            
	//连接标识
    private $conn;   
	//结果标识
    private $result;             
	//SQL语句
    private $sql;              
	//返回条目
    private $row;                   
	//数据库编码
    private $coding;              
	//是否开启错误记录
    private $bulletin = true;        
	//是否开启错误显示
	private $show_error = true;        
	//发现错误是否终止
    private $is_error = true;       
 
    /**
	 * @param 1.数据库主机 2.数据库用户名 3.数据库密码 4.数据库连接标识 5.数据库连接类型 6.数据库编码
	 */
    public function __construct($db_host, $db_user, $db_pwd, $db_database, $conn, $coding){
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pwd = $db_pwd;
        $this->db_database = $db_database;
        $this->conn = $conn;
        $this->coding = $coding;
        $this->connect();
    }
 
    /**
	 * 连接数据库
	 * @param   null
	 * @return  null
	 */
    public function connect(){
		//指定数据库类型
        if($this->conn == "pconn"){
            //永久链接
            $this->conn = mysql_pconnect($this->db_host, $this->db_user, $this->db_pwd);
        }else{
            //即时链接
            $this->conn = mysql_connect($this->db_host, $this->db_user, $this->db_pwd);
        }
        //选择数据库
        if(!mysql_select_db($this->db_database, $this->conn)){
            if($this->show_error){
                $this->show_error("数据库不可用：", $this->db_database);
            }
        }
		//设置字符编码
        mysql_query("SET NAMES $this->coding");
    }
 
    /**
	 * 执行SQL语句
	 * @param  $sql 要执行的SQL语句
	 * @return $result 返回的结果集
	 */
    public function query($sql){
		//验证SQL语句是否为空
        if ($sql == "") {
            $this->show_error("SQL语句错误：", "SQL查询语句为空");
        }
        $this->sql = $sql;
		//执行SQL语句
        $result = mysql_query($this->sql, $this->conn);
		//判断是否成功
        if(!$result){
            //调试中使用，sql语句出错时会自动打印出来
            if ($this->show_error){
                $this->show_error("错误SQL语句：", $this->sql);
            }
        }else{
            $this->result = $result;
        }
		//返回成功结果
        return $this->result;
    }
 
    /**
	 * 创建数据库
	 * @param   $database_name 数据库名称
	 * @return  bool 创建是否成功
	 */
    public function create_database($database_name){
        $database = $database_name;
        $sqlDatabase = 'create database ' . $database;
        //执行SQL语句
		$result = $this->query($sqlDatabase);
		//判断是否创建成功
		if(!$result){
			if($this->show_error){
				$this->show_error("创建数据库失败：",$database);
			}
		}else{
			return true;
		}
    }
 
    /**
	 * 显示所有数据库
	 * @param  null
	 * @return 输出数据库数目和名称
	 */
    public function show_databases(){
		//执行SQL语句
        $res = $this->query("show databases");
        //输出现有数据库数目
		echo "现有数据库：" . $amount = $this->db_num_rows($res);
        echo "<br/>";
        $i = 1;
		//输出现有数据库名称
        while ($row = $this->fetch_array($res)) {
            echo "$i $row[Database]";
            echo "<br/>";
            $i++;
        }
    }
 
    /**
	 * 以数组形式返回数据库
	 * @param  null
	 * @return $rs array 包含所有数据库的数组
	 */
    public function databases() {
		//列出所有数据库
        $rsPtr = mysql_list_dbs($this->conn);
        $i = 0;
		//统计数据库数目
        $cnt = mysql_num_rows($rsPtr);
		//将数据库名称放入数组
        while ($i < $cnt) {
            $rs[] = mysql_db_name($rsPtr, $i);
            $i++;
        }
		//返回所有数据库数组
        return $rs;
    }
 
    /**
	 * 显示所有表
	 * @param  null
 	 * @return 输出表的数目和名称
	 */
    public function show_tables(){
		//执行SQL语句
        $res = $this->query("show tables");
        //统计表的数目
		echo "现有表：" . $amount = $this->db_num_rows($res);
        echo "<br/>";
        $i = 1;
		//输出表的名称
        while($row = $this->fetch_array($res)){
            $columnName = "Tables_in_" . $this->db_database;
            echo "$i $row[$columnName]";
            echo "<br/>";
            $i++;
        }
    }
	
	/**
	 * 以数组形式返回所有表
	 * @param  null
	 * @return $arr 包含所有表的数组
	 */
	public function tables(){
		//执行SQL语句
		$res = $this->query("show tables");
		$arr = array();
		//遍历结果将表存入数组
		while($row = $this->fetch_array($res)){
			$columnName = "Tables_in_" . $this->db_database;
			$arr[] = $row[$columnName];
		}
		return $arr;
	}
	
   /**
    * mysql_fetch_row()    array	从结果集中取得一行作为数字数组 
	* mysql_fetch_assoc()  array    从结果集中取得一行作为关联数组 
	* mysql_fetch_object() object   从结果集中取得一行作为对象 
    * mysql_fetch_array()  array    从结果集中取得一行作为关联数组，或索引数组，或二者兼有 
    */
 
    //取得结果数据
    public function mysql_result_li(){
        return mysql_result($str);
    }
 
    /**
	 * 获取第一条数据的数组
	 * @param  $result 结果集
	 * @return array   结果数组
	 */
    public function fetch_array($result="") {
		if($result<>""){
			//有参数时返回参数对应的数组
			return mysql_fetch_array($result);
        }else{
			//无参数时返回全局对应的数组
			return mysql_fetch_array($this->result);
        }
    }
 
    /**
	 * 获取第一条数据的关联数组
	 * @param $result 结果集
	 * @return array  关联数组
	 */
    public function fetch_assoc($result="") {
		if($result<>""){
			//有参数时返回参数对应的数组
			return mysql_fetch_assoc($result);
		}else{
			//无参数时返回全局对应的数组
			return mysql_fetch_assoc($this->result);
		}
    }
 
    /**
	 * 获取第一条数据的索引数组
	 * @param  $result 结果集
	 * @return array   索引数组
	 */
    public function fetch_row($result="") {
		if($result<>""){
			//有参数时返回参数对应的数组
			return mysql_fetch_row($result);
		}else{
			//无参数时返回全局对应的数组
			return mysql_fetch_row($this->result);
		}
	}
 
    /**
	 * 获取第一条数据的对象
	 * @param  $result 结果集
	 * @return object  结果对象
	 */
    public function fetch_object($result="") {
		if($result<>""){
			//有参数时返回参数对应的对象
			return mysql_fetch_object($result);
		}else{
			//无参数时返回全局对应的对象
			return mysql_fetch_object($this->result);
		}
	}
	
	/**
	 * 获取所有数据的数组
	 * @param  $result 结果集
	 * @return $arr array 包含所有数据的数组
	 */
	public function get_array($result=""){
		//若无结果集则默认从全局获取
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//将数组保存成二维数组
		while($row = mysql_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	/**
	 * 获取所有数据的关联数组
	 * @param  $result 结果集  
	 * @return $arr array 包含关联数组的二维数组   
	 */	
	public function get_assoc($result=""){
		//若无结果集则默认从全局获取
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//将关联数组保存成二维数组
		while($row = mysql_fetch_assoc($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	/**
	 * 获取所有数据的索引数组
	 * @param  $result 结果集
	 * @return $arr array 包含索引数组的二维数组
	 */
	public function get_row($result=""){
		//若无结果集则默认从全局获取
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//将索引数组保存成二维数组
		while($row = mysql_fetch_row($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	/**
	 * 获取所有数据的对象数组
	 * @param  $result 结果集
	 * @return $arr array 包含数据对象的二维数组
	 */
	public function get_object($result=""){
		//若无结果集则默认从全局获取
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//将对象保存成二维对象数组
		while($row = mysql_fetch_object($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
    /**
	 * 获取表中所有数据
	 * @param  $table 表名
	 * @return $res 结果集
	 */
    public function find_all($table) {
		//执行SQL语句
        $res = $this->query("SELECT * FROM $table");
		//返回结果集
		return $res;
    }
 
    /**
	 * 自定义查询方法
	 * @param $table string 表 $columnName string 字段名 $where array 条件
	 * @return 成功：查询所得结果集  失败：false
	 */
    public function select($table, $columnName = "*", $where = ''){
		//拼接条件语句
		$condition = "";
		foreach($where as $key => $val){
			$condition .= "$key=$val,";
		}
		$condition = rtrim($condition,",");
		if(!empty($condition)){
			$condition = "where ".$condition;
		}
		//执行SQL语句，返回执行结果
        if($res = $this->query("SELECT $columnName FROM $table $condition")){
			return $res;
		}else{
			return false;
		}
    }
 
    /**
	 * 自定义删除方法
	 * @param $table string 表 $condition array 条件 
	 * @return bool 删除成功与否
	 */
    public function delete($table, $where){
		//拼接条件语句
		$condition = "";
		foreach($where as $key => $val){
			$condition = "$key=$val,";
		}
		$condition = rtrim($condition,",");
		if(!empty($condition)){
			$condition = "where ".$condition;
		}
		//执行SQL语句，返回执行结果
        if($this->query("DELETE FROM $table WHERE $condition")) {
            return true;
        }else{
			return false;
		}
    }
 
    /**
	 * 自定义插入方法
	 * @param  $table string 表 $data array 数据
	 * @return bool 插入成功与否
	 */
    public function insert($table,$data){
		//将数组拆分成字段和值
		$string1 = "";
		$string2 = "";
		foreach($data as $key => $val){
			$string1 .= "$key,";
			$string2 .= "$val,";
		}
		$string1 = rtrim($string1,",");
		$string2 = rtrim($string2,",");
		//拼接插入条件
		$cdata = "(".$string1.")"."VALUES"."(".$string2.")";
        //执行SQL语句，返回执行结果
		if ($this->query("INSERT INTO $table $cdata")) {
            return true;
        }else{
			return false;
		}
    }
 
    /**
	 * 自定义修改方法
	 * @param $table string 表 $mod_content array 设置内容 $condition array 条件语句
	 * $return bool 更新成功与否
	 */
    public function update($table, $mod_content, $where){
		//拼接插入数据和插入条件
		$string1 = "";
		$string2 = "";
		foreach($mod_content as $key => $val){
			$string1 .= "$key=$val,";
		}
		foreach($where as $key => $val){
			$string2 .= "$key=$val";
		}
		$string1 = rtrim($string1,",");
		$string2 = rtrim($string2,",");
		$condition = "SET ".$string1." WHERE ".$string2;
		//执行SQL语句
        if ($this->query("UPDATE $table $condition")) {
            return true;
        }else{
			return false;
		}
    }
 
    /**
	 * 获取最后插入数据的id
	 * @param  null
	 * @return null
	 */
    public function insert_id() {
        return mysql_insert_id();
    }
 
    /*
	 * 获取确定的一条数据记录
	 * @param  $id int 记录id
	 * @return $row array 数据对应的数组 
	 */
    public function db_data_seek($id) {
		//处理数据编号
        if ($id > 0) {
            $id = $id -1;
        }
		//查询数据是否存在
        if (!mysql_data_seek($this->result, $id)) {
            $this->show_error("SQL语句有误：", "指定的数据为空");
        }
        return $row = mysql_fetch_array($id,$this->result);
    }
 
    /**
	 * 计算结果集数目
	 * @param null
	 * @return int 结果集中的记录条数
	 */
    public function db_num_rows(){
		//判断结果集是否为空
        if($this->result == null){
            if($this->show_error){
                $this->show_error("SQL语句错误", "暂时为空，没有任何内容！");
            }
        }else{
			//返回结果集中记录数目
            return mysql_num_rows($this->result);
        }
    }
 
    /*
	 * 获取受影响行数
	 * @param  null
	 * @return null
	 */
    public function db_affected_rows() {
        return mysql_affected_rows();
    }
 
    /**
	 * 输出错误提示信息
	 * @param  $message string 错误信息 $sql string 错误的sql语句
	 * @return 显示错误信息，并根据选项写入日志文件
	 */
    public function show_error($message = "", $sql = "") {
        //显示非sql查询错误
		if (!$sql) {
            echo "<font color='red'>" . $message . "</font>";
            echo "<br />";
        } else {
			//显示sql查询错误
            echo "<fieldset>";
            echo "<legend>错误信息提示:</legend><br/>";
            echo "<div style='font-size:14px; clear:both; font-family:Verdana, Arial, Helvetica, sans-serif;'>";
            echo "<div style='height:20px; background:#000000; border:1px #000000 solid'>";
            echo "<font color='white'>错误号：". mysql_errno() ."</font>";
            echo "</div><br/>";
            echo "错误原因：" . mysql_error() . "<br /><br />";
            echo "<div style='height:20px; background:#FF0000; border:1px #FF0000 solid'>";
            echo "<font color='white'>" . $message . "</font>";
            echo "</div>";
            echo "<font color='red'><pre>" . $sql . "</pre></font>";
			//获取客户端ip地址
            $ip = $this->getip();
			//是否记录错误日志
            if ($this->bulletin) {
                $time = date("Y-m-d H:i:s");
				//拼接错误信息
                $message = $message . "\r\n$this->sql" . "\r\n客户IP:$ip" . "\r\n时间 :$time" . "\r\n\r\n";
                $server_date = date("Y-m-d");
				//设置错误日志名
                $filename = $server_date . ".txt";
				//设置错误日志路径
                $file_path = "error/" . $filename;
                //设置错误信息提示
				$error_content = $message;
                //设置日志保存目录
                $file = "error"; 
 
                //建立文件夹
                if (!file_exists($file)) {
					 //默认的 mode 是 0777，意味着最大可能的访问权
                    if (!mkdir($file, 0777)) {
                        die("upload files directory does not exist and creation failed");
                    }
                }
 
                //建立txt日期文件
                if (!file_exists($file_path)) {
                    //创建错误日志读写方式打开
                    fopen($file_path, "w+");
 
                    //首先要确定文件存在并且可写
                    if (is_writable($file_path)) {
                        //使用添加模式打开$filename，文件指针将会在文件的开头
                        if (!$handle = fopen($file_path, 'a')) {
                            echo "不能打开文件 $filename";
                            exit;
                        }
 
                        //将$somecontent写入到我们打开的文件中。
                        if (!fwrite($handle, $error_content)) {
                            echo "不能写入到文件 $filename";
                            exit;
                        }
                        echo "——错误记录被保存!";
                        //关闭文件
                        fclose($handle);
                    }else{
                        echo "文件 $filename 不可写";
                    }
 
                }else{
                    //首先要确定文件存在并且可写
                    if (is_writable($file_path)) {
                        //使用添加模式打开$filename，文件指针将会在文件的开头
                        if (!$handle = fopen($file_path, 'a')) {
                            echo "不能打开文件 $filename";
                            exit;
                        }
 
                        //将$somecontent写入到我们打开的文件中。
                        if (!fwrite($handle, $error_content)) {
                            echo "不能写入到文件 $filename";
                            exit;
                        }
                        echo "——错误记录被保存!";
                        //关闭文件
                        fclose($handle);
                    } else {
                        echo "文件 $filename 不可写";
                    }
                }
 
            }
            echo "<br />";
			//发现错误是否终止
            if ($this->is_error) {
                exit;
            }
        }
        echo "</div>";
        echo "</fieldset>";
        echo "<br />";
    }
 
    /**
	 * 释放结果集
	 * @param  null
	 * @return bool 释放成功与否
	 */
    public function free() {
        return mysql_free_result($this->result);
    }
 
    /**
	 * 切换数据库
	 * @param  $db_database string 数据库
	 * @return 切换成功与否
	 */
    public function select_db($db_database){
		$this->db_database = $db_database;
        return mysql_select_db($db_database);
    }
 
    /*
	 * 获取字段数量
	 * @param $table_name 表
	 * @return 输出字段数量和字段名
	 */
    public function num_fields($table_name) {	
        $this->query("select * from $table_name");
        echo "<br/>";
        echo "字段数：" . $total = mysql_num_fields($this->result);
        echo "<pre>";
        for ($i = 0; $i < $total; $i++) {
            print_r(mysql_fetch_field($this->result, $i));
        }
        echo "</pre>";
        echo "<br />";
    }
 
    /**
	 * 获取MySQL数据库信息
	 * @param  $num int 信息类别
	 * @return 服务器信息
	 */
    public function mysql_server($num = '') {
        switch ($num) {
            case 1 :
                return mysql_get_server_info();       //取得 MySQL 服务器信息
                break;
 
            case 2 :
                return mysql_get_host_info();         //取得 MySQL 主机信息
                break;
 
            case 3 :
                return mysql_get_client_info();       //取得 MySQL 客户端信息
                break;
 
            case 4 :
                return mysql_get_proto_info();        //取得 MySQL 协议信息
                break;
 
            default :
                return mysql_get_client_info();       //默认取得 MySQL 版本信息
        }
    }
 
    /**
	 * 析构函数释放结果集，关闭连接
	 * @param  null
	 * @return null
	 */
    public function __destruct() {
		//获取结果集类型
		$type = gettype($this->result);
        //如果是资源类型则释放资源
		if (!empty ($this->result) && $type == "resource") {
            $this->free();
        }
        mysql_close($this->conn);
    }
 
    /**
	 * 获取客户端ip地址
	 * @param   null 
	 * @return  $ip  string 客户端ip地址
	 */
    function getip(){
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
            $ip = getenv("HTTP_CLIENT_IP");
        }else
            if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            }else
                if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
                    $ip = getenv("REMOTE_ADDR");
                }else
                    if(isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }else{
                        $ip = "unknown";
                    }
        return $ip;
    }
	/**
	 * 防SQL注入
	 * @param   $sql_str string 要执行的SQL语句
	 * @return  $sql_str string 过滤后的SQL语句
	 */
    function inject_check($sql_str) { 
        $check = eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
        if ($check) {
            echo "输入非法注入内容！";
            exit ();
        }else{
            return $sql_str;
        }
    }
	/**
	 * 检查来路
	 * @param  null
	 * @return null
	 */
    function checkurl() {
        if (preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
            header("Location: http://www.dareng.com");
            exit();
        }
    }
 
}
?>