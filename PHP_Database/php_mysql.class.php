<?php
/**
 *+---------------------------------------------------------------------
 * �ļ����� php_mysql.class.php
 *+---------------------------------------------------------------------
 * �ļ����� php�������ݿ������
 *+---------------------------------------------------------------------
 */
 
class mysql {
	//���ݿ�����
	private $db_host;
	//���ݿ��û���
    private $db_user;
	//���ݿ�����
    private $db_pwd;     
	//���ݿ���
    private $db_database;            
	//���ӱ�ʶ
    private $conn;   
	//�����ʶ
    private $result;             
	//SQL���
    private $sql;              
	//������Ŀ
    private $row;                   
	//���ݿ����
    private $coding;              
	//�Ƿ��������¼
    private $bulletin = true;        
	//�Ƿ���������ʾ
	private $show_error = true;        
	//���ִ����Ƿ���ֹ
    private $is_error = true;       
 
    /**
	 * @param 1.���ݿ����� 2.���ݿ��û��� 3.���ݿ����� 4.���ݿ����ӱ�ʶ 5.���ݿ��������� 6.���ݿ����
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
	 * �������ݿ�
	 * @param   null
	 * @return  null
	 */
    public function connect(){
		//ָ�����ݿ�����
        if($this->conn == "pconn"){
            //��������
            $this->conn = mysql_pconnect($this->db_host, $this->db_user, $this->db_pwd);
        }else{
            //��ʱ����
            $this->conn = mysql_connect($this->db_host, $this->db_user, $this->db_pwd);
        }
        //ѡ�����ݿ�
        if(!mysql_select_db($this->db_database, $this->conn)){
            if($this->show_error){
                $this->show_error("���ݿⲻ���ã�", $this->db_database);
            }
        }
		//�����ַ�����
        mysql_query("SET NAMES $this->coding");
    }
 
    /**
	 * ִ��SQL���
	 * @param  $sql Ҫִ�е�SQL���
	 * @return $result ���صĽ����
	 */
    public function query($sql){
		//��֤SQL����Ƿ�Ϊ��
        if ($sql == "") {
            $this->show_error("SQL������", "SQL��ѯ���Ϊ��");
        }
        $this->sql = $sql;
		//ִ��SQL���
        $result = mysql_query($this->sql, $this->conn);
		//�ж��Ƿ�ɹ�
        if(!$result){
            //������ʹ�ã�sql������ʱ���Զ���ӡ����
            if ($this->show_error){
                $this->show_error("����SQL��䣺", $this->sql);
            }
        }else{
            $this->result = $result;
        }
		//���سɹ����
        return $this->result;
    }
 
    /**
	 * �������ݿ�
	 * @param   $database_name ���ݿ�����
	 * @return  bool �����Ƿ�ɹ�
	 */
    public function create_database($database_name){
        $database = $database_name;
        $sqlDatabase = 'create database ' . $database;
        //ִ��SQL���
		$result = $this->query($sqlDatabase);
		//�ж��Ƿ񴴽��ɹ�
		if(!$result){
			if($this->show_error){
				$this->show_error("�������ݿ�ʧ�ܣ�",$database);
			}
		}else{
			return true;
		}
    }
 
    /**
	 * ��ʾ�������ݿ�
	 * @param  null
	 * @return ������ݿ���Ŀ������
	 */
    public function show_databases(){
		//ִ��SQL���
        $res = $this->query("show databases");
        //����������ݿ���Ŀ
		echo "�������ݿ⣺" . $amount = $this->db_num_rows($res);
        echo "<br/>";
        $i = 1;
		//����������ݿ�����
        while ($row = $this->fetch_array($res)) {
            echo "$i $row[Database]";
            echo "<br/>";
            $i++;
        }
    }
 
    /**
	 * ��������ʽ�������ݿ�
	 * @param  null
	 * @return $rs array �����������ݿ������
	 */
    public function databases() {
		//�г��������ݿ�
        $rsPtr = mysql_list_dbs($this->conn);
        $i = 0;
		//ͳ�����ݿ���Ŀ
        $cnt = mysql_num_rows($rsPtr);
		//�����ݿ����Ʒ�������
        while ($i < $cnt) {
            $rs[] = mysql_db_name($rsPtr, $i);
            $i++;
        }
		//�����������ݿ�����
        return $rs;
    }
 
    /**
	 * ��ʾ���б�
	 * @param  null
 	 * @return ��������Ŀ������
	 */
    public function show_tables(){
		//ִ��SQL���
        $res = $this->query("show tables");
        //ͳ�Ʊ����Ŀ
		echo "���б�" . $amount = $this->db_num_rows($res);
        echo "<br/>";
        $i = 1;
		//����������
        while($row = $this->fetch_array($res)){
            $columnName = "Tables_in_" . $this->db_database;
            echo "$i $row[$columnName]";
            echo "<br/>";
            $i++;
        }
    }
	
	/**
	 * ��������ʽ�������б�
	 * @param  null
	 * @return $arr �������б������
	 */
	public function tables(){
		//ִ��SQL���
		$res = $this->query("show tables");
		$arr = array();
		//������������������
		while($row = $this->fetch_array($res)){
			$columnName = "Tables_in_" . $this->db_database;
			$arr[] = $row[$columnName];
		}
		return $arr;
	}
	
   /**
    * mysql_fetch_row()    array	�ӽ������ȡ��һ����Ϊ�������� 
	* mysql_fetch_assoc()  array    �ӽ������ȡ��һ����Ϊ�������� 
	* mysql_fetch_object() object   �ӽ������ȡ��һ����Ϊ���� 
    * mysql_fetch_array()  array    �ӽ������ȡ��һ����Ϊ�������飬���������飬����߼��� 
    */
 
    //ȡ�ý������
    public function mysql_result_li(){
        return mysql_result($str);
    }
 
    /**
	 * ��ȡ��һ�����ݵ�����
	 * @param  $result �����
	 * @return array   �������
	 */
    public function fetch_array($result="") {
		if($result<>""){
			//�в���ʱ���ز�����Ӧ������
			return mysql_fetch_array($result);
        }else{
			//�޲���ʱ����ȫ�ֶ�Ӧ������
			return mysql_fetch_array($this->result);
        }
    }
 
    /**
	 * ��ȡ��һ�����ݵĹ�������
	 * @param $result �����
	 * @return array  ��������
	 */
    public function fetch_assoc($result="") {
		if($result<>""){
			//�в���ʱ���ز�����Ӧ������
			return mysql_fetch_assoc($result);
		}else{
			//�޲���ʱ����ȫ�ֶ�Ӧ������
			return mysql_fetch_assoc($this->result);
		}
    }
 
    /**
	 * ��ȡ��һ�����ݵ���������
	 * @param  $result �����
	 * @return array   ��������
	 */
    public function fetch_row($result="") {
		if($result<>""){
			//�в���ʱ���ز�����Ӧ������
			return mysql_fetch_row($result);
		}else{
			//�޲���ʱ����ȫ�ֶ�Ӧ������
			return mysql_fetch_row($this->result);
		}
	}
 
    /**
	 * ��ȡ��һ�����ݵĶ���
	 * @param  $result �����
	 * @return object  �������
	 */
    public function fetch_object($result="") {
		if($result<>""){
			//�в���ʱ���ز�����Ӧ�Ķ���
			return mysql_fetch_object($result);
		}else{
			//�޲���ʱ����ȫ�ֶ�Ӧ�Ķ���
			return mysql_fetch_object($this->result);
		}
	}
	
	/**
	 * ��ȡ�������ݵ�����
	 * @param  $result �����
	 * @return $arr array �����������ݵ�����
	 */
	public function get_array($result=""){
		//���޽������Ĭ�ϴ�ȫ�ֻ�ȡ
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//�����鱣��ɶ�ά����
		while($row = mysql_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	/**
	 * ��ȡ�������ݵĹ�������
	 * @param  $result �����  
	 * @return $arr array ������������Ķ�ά����   
	 */	
	public function get_assoc($result=""){
		//���޽������Ĭ�ϴ�ȫ�ֻ�ȡ
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//���������鱣��ɶ�ά����
		while($row = mysql_fetch_assoc($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	/**
	 * ��ȡ�������ݵ���������
	 * @param  $result �����
	 * @return $arr array ������������Ķ�ά����
	 */
	public function get_row($result=""){
		//���޽������Ĭ�ϴ�ȫ�ֻ�ȡ
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//���������鱣��ɶ�ά����
		while($row = mysql_fetch_row($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	/**
	 * ��ȡ�������ݵĶ�������
	 * @param  $result �����
	 * @return $arr array �������ݶ���Ķ�ά����
	 */
	public function get_object($result=""){
		//���޽������Ĭ�ϴ�ȫ�ֻ�ȡ
		if($result === ""){
			$result = $this->result;
		}
		$arr = array();
		//�����󱣴�ɶ�ά��������
		while($row = mysql_fetch_object($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
    /**
	 * ��ȡ������������
	 * @param  $table ����
	 * @return $res �����
	 */
    public function find_all($table) {
		//ִ��SQL���
        $res = $this->query("SELECT * FROM $table");
		//���ؽ����
		return $res;
    }
 
    /**
	 * �Զ����ѯ����
	 * @param $table string �� $columnName string �ֶ��� $where array ����
	 * @return �ɹ�����ѯ���ý����  ʧ�ܣ�false
	 */
    public function select($table, $columnName = "*", $where = ''){
		//ƴ���������
		$condition = "";
		foreach($where as $key => $val){
			$condition .= "$key=$val,";
		}
		$condition = rtrim($condition,",");
		if(!empty($condition)){
			$condition = "where ".$condition;
		}
		//ִ��SQL��䣬����ִ�н��
        if($res = $this->query("SELECT $columnName FROM $table $condition")){
			return $res;
		}else{
			return false;
		}
    }
 
    /**
	 * �Զ���ɾ������
	 * @param $table string �� $condition array ���� 
	 * @return bool ɾ���ɹ����
	 */
    public function delete($table, $where){
		//ƴ���������
		$condition = "";
		foreach($where as $key => $val){
			$condition = "$key=$val,";
		}
		$condition = rtrim($condition,",");
		if(!empty($condition)){
			$condition = "where ".$condition;
		}
		//ִ��SQL��䣬����ִ�н��
        if($this->query("DELETE FROM $table WHERE $condition")) {
            return true;
        }else{
			return false;
		}
    }
 
    /**
	 * �Զ�����뷽��
	 * @param  $table string �� $data array ����
	 * @return bool ����ɹ����
	 */
    public function insert($table,$data){
		//�������ֳ��ֶκ�ֵ
		$string1 = "";
		$string2 = "";
		foreach($data as $key => $val){
			$string1 .= "$key,";
			$string2 .= "$val,";
		}
		$string1 = rtrim($string1,",");
		$string2 = rtrim($string2,",");
		//ƴ�Ӳ�������
		$cdata = "(".$string1.")"."VALUES"."(".$string2.")";
        //ִ��SQL��䣬����ִ�н��
		if ($this->query("INSERT INTO $table $cdata")) {
            return true;
        }else{
			return false;
		}
    }
 
    /**
	 * �Զ����޸ķ���
	 * @param $table string �� $mod_content array �������� $condition array �������
	 * $return bool ���³ɹ����
	 */
    public function update($table, $mod_content, $where){
		//ƴ�Ӳ������ݺͲ�������
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
		//ִ��SQL���
        if ($this->query("UPDATE $table $condition")) {
            return true;
        }else{
			return false;
		}
    }
 
    /**
	 * ��ȡ���������ݵ�id
	 * @param  null
	 * @return null
	 */
    public function insert_id() {
        return mysql_insert_id();
    }
 
    /*
	 * ��ȡȷ����һ�����ݼ�¼
	 * @param  $id int ��¼id
	 * @return $row array ���ݶ�Ӧ������ 
	 */
    public function db_data_seek($id) {
		//�������ݱ��
        if ($id > 0) {
            $id = $id -1;
        }
		//��ѯ�����Ƿ����
        if (!mysql_data_seek($this->result, $id)) {
            $this->show_error("SQL�������", "ָ��������Ϊ��");
        }
        return $row = mysql_fetch_array($id,$this->result);
    }
 
    /**
	 * ����������Ŀ
	 * @param null
	 * @return int ������еļ�¼����
	 */
    public function db_num_rows(){
		//�жϽ�����Ƿ�Ϊ��
        if($this->result == null){
            if($this->show_error){
                $this->show_error("SQL������", "��ʱΪ�գ�û���κ����ݣ�");
            }
        }else{
			//���ؽ�����м�¼��Ŀ
            return mysql_num_rows($this->result);
        }
    }
 
    /*
	 * ��ȡ��Ӱ������
	 * @param  null
	 * @return null
	 */
    public function db_affected_rows() {
        return mysql_affected_rows();
    }
 
    /**
	 * ���������ʾ��Ϣ
	 * @param  $message string ������Ϣ $sql string �����sql���
	 * @return ��ʾ������Ϣ��������ѡ��д����־�ļ�
	 */
    public function show_error($message = "", $sql = "") {
        //��ʾ��sql��ѯ����
		if (!$sql) {
            echo "<font color='red'>" . $message . "</font>";
            echo "<br />";
        } else {
			//��ʾsql��ѯ����
            echo "<fieldset>";
            echo "<legend>������Ϣ��ʾ:</legend><br/>";
            echo "<div style='font-size:14px; clear:both; font-family:Verdana, Arial, Helvetica, sans-serif;'>";
            echo "<div style='height:20px; background:#000000; border:1px #000000 solid'>";
            echo "<font color='white'>����ţ�". mysql_errno() ."</font>";
            echo "</div><br/>";
            echo "����ԭ��" . mysql_error() . "<br /><br />";
            echo "<div style='height:20px; background:#FF0000; border:1px #FF0000 solid'>";
            echo "<font color='white'>" . $message . "</font>";
            echo "</div>";
            echo "<font color='red'><pre>" . $sql . "</pre></font>";
			//��ȡ�ͻ���ip��ַ
            $ip = $this->getip();
			//�Ƿ��¼������־
            if ($this->bulletin) {
                $time = date("Y-m-d H:i:s");
				//ƴ�Ӵ�����Ϣ
                $message = $message . "\r\n$this->sql" . "\r\n�ͻ�IP:$ip" . "\r\nʱ�� :$time" . "\r\n\r\n";
                $server_date = date("Y-m-d");
				//���ô�����־��
                $filename = $server_date . ".txt";
				//���ô�����־·��
                $file_path = "error/" . $filename;
                //���ô�����Ϣ��ʾ
				$error_content = $message;
                //������־����Ŀ¼
                $file = "error"; 
 
                //�����ļ���
                if (!file_exists($file)) {
					 //Ĭ�ϵ� mode �� 0777����ζ�������ܵķ���Ȩ
                    if (!mkdir($file, 0777)) {
                        die("upload files directory does not exist and creation failed");
                    }
                }
 
                //����txt�����ļ�
                if (!file_exists($file_path)) {
                    //����������־��д��ʽ��
                    fopen($file_path, "w+");
 
                    //����Ҫȷ���ļ����ڲ��ҿ�д
                    if (is_writable($file_path)) {
                        //ʹ�����ģʽ��$filename���ļ�ָ�뽫�����ļ��Ŀ�ͷ
                        if (!$handle = fopen($file_path, 'a')) {
                            echo "���ܴ��ļ� $filename";
                            exit;
                        }
 
                        //��$somecontentд�뵽���Ǵ򿪵��ļ��С�
                        if (!fwrite($handle, $error_content)) {
                            echo "����д�뵽�ļ� $filename";
                            exit;
                        }
                        echo "���������¼������!";
                        //�ر��ļ�
                        fclose($handle);
                    }else{
                        echo "�ļ� $filename ����д";
                    }
 
                }else{
                    //����Ҫȷ���ļ����ڲ��ҿ�д
                    if (is_writable($file_path)) {
                        //ʹ�����ģʽ��$filename���ļ�ָ�뽫�����ļ��Ŀ�ͷ
                        if (!$handle = fopen($file_path, 'a')) {
                            echo "���ܴ��ļ� $filename";
                            exit;
                        }
 
                        //��$somecontentд�뵽���Ǵ򿪵��ļ��С�
                        if (!fwrite($handle, $error_content)) {
                            echo "����д�뵽�ļ� $filename";
                            exit;
                        }
                        echo "���������¼������!";
                        //�ر��ļ�
                        fclose($handle);
                    } else {
                        echo "�ļ� $filename ����д";
                    }
                }
 
            }
            echo "<br />";
			//���ִ����Ƿ���ֹ
            if ($this->is_error) {
                exit;
            }
        }
        echo "</div>";
        echo "</fieldset>";
        echo "<br />";
    }
 
    /**
	 * �ͷŽ����
	 * @param  null
	 * @return bool �ͷųɹ����
	 */
    public function free() {
        return mysql_free_result($this->result);
    }
 
    /**
	 * �л����ݿ�
	 * @param  $db_database string ���ݿ�
	 * @return �л��ɹ����
	 */
    public function select_db($db_database){
		$this->db_database = $db_database;
        return mysql_select_db($db_database);
    }
 
    /*
	 * ��ȡ�ֶ�����
	 * @param $table_name ��
	 * @return ����ֶ��������ֶ���
	 */
    public function num_fields($table_name) {	
        $this->query("select * from $table_name");
        echo "<br/>";
        echo "�ֶ�����" . $total = mysql_num_fields($this->result);
        echo "<pre>";
        for ($i = 0; $i < $total; $i++) {
            print_r(mysql_fetch_field($this->result, $i));
        }
        echo "</pre>";
        echo "<br />";
    }
 
    /**
	 * ��ȡMySQL���ݿ���Ϣ
	 * @param  $num int ��Ϣ���
	 * @return ��������Ϣ
	 */
    public function mysql_server($num = '') {
        switch ($num) {
            case 1 :
                return mysql_get_server_info();       //ȡ�� MySQL ��������Ϣ
                break;
 
            case 2 :
                return mysql_get_host_info();         //ȡ�� MySQL ������Ϣ
                break;
 
            case 3 :
                return mysql_get_client_info();       //ȡ�� MySQL �ͻ�����Ϣ
                break;
 
            case 4 :
                return mysql_get_proto_info();        //ȡ�� MySQL Э����Ϣ
                break;
 
            default :
                return mysql_get_client_info();       //Ĭ��ȡ�� MySQL �汾��Ϣ
        }
    }
 
    /**
	 * ���������ͷŽ�������ر�����
	 * @param  null
	 * @return null
	 */
    public function __destruct() {
		//��ȡ���������
		$type = gettype($this->result);
        //�������Դ�������ͷ���Դ
		if (!empty ($this->result) && $type == "resource") {
            $this->free();
        }
        mysql_close($this->conn);
    }
 
    /**
	 * ��ȡ�ͻ���ip��ַ
	 * @param   null 
	 * @return  $ip  string �ͻ���ip��ַ
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
	 * ��SQLע��
	 * @param   $sql_str string Ҫִ�е�SQL���
	 * @return  $sql_str string ���˺��SQL���
	 */
    function inject_check($sql_str) { 
        $check = eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
        if ($check) {
            echo "����Ƿ�ע�����ݣ�";
            exit ();
        }else{
            return $sql_str;
        }
    }
	/**
	 * �����·
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