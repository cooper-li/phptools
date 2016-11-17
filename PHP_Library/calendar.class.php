<?php
/**
 * ����:Calendar ����:����һ������
 * ===============================
 * ʹ��ʾ������:
 * ===============================
 * require('calendar.class.php');
 * $calendar = new Calendar($filepath[,$year][,$month]);
 * $calendar->generate();
 */
class Calendar{
	//�������
	public $year = '';
	//�����·�
	public $month = '';
	//���嵱ǰ������
	public $days = '';
	//�����һ������
	public $weekday = '';
	//������һ��
	public $premon = '';
	//������һ��
	public $nexmon = '';
	//������һ��
	public $preyear = '';
	//������һ��
	public $nexyear = '';
	//�����ļ���
	public $filepath = '';
	
	//���캯����ʼ���·�
	public function __construct($filepath,$year = '',$month = ''){
		$this->filepath = $filepath;
		$this->year = empty($year) ? date('Y') : $year;
		$this->month = empty($year) ? date('m') : $month;	
	}
	
	//����������Ϣ������ģ��
	public function generate(){
		//��ȡ����
		if(isset($_GET['y']))
			$this->year = $_GET['y'];
		if(isset($_GET['m']))
			$this->month = $_GET['m'];
		//��ȡ��ǰ������
		$this->days = date("t",mktime(0,0,0,$this->month,1,$this->year));
		//��ȡ��һ�����ڼ�
		$this->weekday = date("w",mktime(0,0,0,$this->month,1,$this->year));
		//�������
		if($this->month == 1){
			$this->premon = 12;
			$this->preyear = $this->year - 1;
			$this->nexyear = $this->year;
			$this->nexmon = $this->month + 1;
		}else if($this->month == 12){
			$this->nexmon = 1;
			$this->nexyear = $this->year + 1;
			$this->premon = $this->month - 1;
			$this->preyear = $this->year - 1;
		}else{
			$this->preyear = $this->nexyear = $this->year;
			$this->premon = $this->month - 1;
			$this->nexmon = $this->month + 1;
		}
		//���ģ��
		echo $this->template();
	}
	
	//ƴ�Ӳ����ģ��
	public function template(){
		$tpl = "<center>";
		$tpl .= "<h4>{$this->year}�� {$this->month}��</h4>";
		$tpl .= "<table width = '600' border='1'>";
		$tpl .= "<tr>";
		$tpl .= "<th style='color:red'>������</th><th>����һ</th><th>���ڶ�</th><th>������</th><th>������</th><th>������</th><th style='color:green'>������</th>";
		$tpl .= "</tr>";
		$dd = 1;
		//���һ������Ϣ
		while($dd <= $this->days){
			$tpl .= "<tr>";
			//���һ����Ϣ
			for($i = 0;$i < 7;$i++){
				//�ж����ʱ��
				if($dd <= $this->days && ($this->weekday <= $i || $dd != 1)){
					$tpl .= "<td>{$dd}</td>";
					$dd++;
				}else{
					$tpl .= "<td>&nbsp;</td>";
				}
			}
			$tpl .= "</tr>";
		}
		$tpl .= "</table>";
		//���������
		$tpl .= "<h4><a href='{$this->filepath}.php?y={$this->preyear}&m={$this->premon}'>��һ��</a>&nbsp;&nbsp;&nbsp;";
		$tpl .= "<a href='{$this->filepath}.php?y={$this->nexyear}&m={$this->nexmon}'>��һ��</a></h4>";
		$tpl .= "</center>";
		return $tpl;
	}

}

