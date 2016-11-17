<?php
/**
 * 类名：Captcha 功能：生成验证码并存入SESSION中
 * ==============================================
 * 使用示例代码：
 * ==============================================
 * <img src="captcha.php" onclick="this.src='captcha.php?id='+Math.random()"/><br><br>
 * <?php
 * 		session_start();
 *      $code = $_SESSION['captcha'];
 * ?>
 * 说明：本类文件通过captcha.php实例化，输出验证码的位置指向captcha.php，验证码自动存入SESSION中
 * 生成的验证码还依赖于字体文件cambriaz.ttf
 */

class Captcha{
	//验证码位数
	public $number;
	//验证码字符
	public $code;
	//验证码宽度
	public $width;
	//验证码高度
	public $height;
	//验证码类型
	public $type;
	//构造函数
	public function __construct($number = 4,$width = 80,$height = 30,$type = 0){
		$this->number = $number;
		$this->width = $width;
		$this->height = $height;
		$this->type = $type;
	}
	//显示验证码
	public function show_captcha(){
		//获取字符串并放入SESSION
		$this->code = $this->create_string($this->number,$this->type);
		session_start();
		$_SESSION['captcha'] = $this->code;
		//创建画布
		$im = imagecreatetruecolor($this->width,$this->height);
		//指定字体颜色
		$color[] = imagecolorallocate($im,111,0,55);
		$color[] = imagecolorallocate($im,0,77,0);
		$color[] = imagecolorallocate($im,0,0,160);
		$color[] = imagecolorallocate($im,211,111,0);
		$color[] = imagecolorallocate($im,221,0,0);
		//指定背景颜色
		$bg = imagecolorallocate($im,200,200,200);
		//填充背景
		imagefill($im,0,0,$bg);
		//绘制边框
		imagerectangle($im,0,0,$this->width-1,$this->height-1,$color[rand(0,4)]);
		
		//随机添加干扰点
		for($i = 0;$i < 200;$i++){
			$radcolor = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($im,rand(0,$this->width),rand(0,$this->height),$radcolor);
		}

		//随机添加干扰线
		for($i = 0;$i < 5;$i++){
			$radcolor = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imageline($im,rand(0,$this->width),rand(0,$this->height),rand(0,$this->width),rand(0,$this->height),$radcolor);
		}	
		
		//将生成的字符写入图像
		for($i = 0;$i < $this->number;$i++){
			imagettftext($im,18,rand(-40,40),8+(18*$i),24,$color[rand(0,4)],"cambriaz.ttf",$this->code[$i]);
		}
		
		//设置响应头(之前不能有任何输出)
		header('Content-Type:image/png');
		//生成图像
		imagepng($im);
		//销毁图像
		imagedestroy($im);
	}
	
	/**
	* 随机生成一个验证码内容的函数
	* @param $m 验证码位数
	* @param $type 验证码类型 0:纯数字 1:数字+小写字母 2:数字+大小写字母
	*/
	public function create_string($num,$type){
		//指定字符串
		$str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		//定制三种类型字符串
		$tmp = array(9,35,strlen($str)-1);
		//获取字符串
		$char = "";
		for($i = 0;$i < $num;$i++){
			$char .= $str[rand(0,$tmp[$type])]; 
		}
		return $char; 
	}
}

