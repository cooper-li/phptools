<?php
class Pagination {
	
	public $pagesize;
	
	public $maxrows;
	
	public $maxpages;
	
	public $currpage;
	
	public $url;
	
	public function __construct($pagesize,$maxrows,$url){
		$this->pagesize = $pagesize;
		$this->maxrows = $maxrows;
		$this->url = $url;
		$this->maxpages = ceil($this->maxrows/$this->pagesize);
		$this->currpage = isset($_GET['page']) ? $_GET['page'] : 1; 
	}
	
	public function show_pages(){
		if($this->currpage > $this->maxpages){
			$this->currpage = $this->maxpages;
		}
		if($this->currpage < 1){
			$this->currpage = 1;
		}
		$str = "<br>";
		$str .= "当前".$this->currpage."/".$this->maxpages."页&nbsp;&nbsp;&nbsp;共计".$this->maxrows."条&nbsp;&nbsp;&nbsp;";
		if($this->currpage - 1 > 2){
			$str .= "<a href='".$this->url.".php?page=1'>首页</a>&nbsp;&nbsp;&nbsp;";
		}
		if($this->currpage != 1){
			$str .= "<a href='".$this->url.".php?page=".($this->currpage-1)."'>上一页</a>&nbsp;&nbsp;&nbsp;";
		}
		if($this->currpage != $this->maxpages){
			$str .=	"<a href='".$this->url.".php?page=".($this->currpage+1)."'>下一页</a>&nbsp;&nbsp;&nbsp;";
		}
		if($this->maxpages - $this->currpage > 2){
			$str .= "<a href='".$this->url.".php?page=".$this->maxpages."'>尾页</a>";
		}
		return $str;
	}
	
}