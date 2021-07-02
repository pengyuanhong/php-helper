<?php
/**
 * Created by PhpStorm.
 * User: Kyle
 * Date: 2020/6/2
 * Time: 12:18 PM
 */

namespace PhpHelper\Functions;


class Functions
{
	/**
	 * 过滤掉emoji表情符号(遍历并检查字符串中的每个字符，如果该字符的长度大于等于4个字节，就将其删除)
	 * @param $str
	 * @return mixed
	 * @date 2017-04-25 11:43
	 */
	public static function filterEmoji($str)
	{
		$str = preg_replace_callback(
			'/./u',
			function (array $match) {
				return strlen($match[0]) >= 4 ? '' : $match[0];
			},
			$str);
		return $str;
	}
	
	/**
	 * 多维数组转化为一维数组(立体转为平面)
	 * @param $array
	 * @return array
	 * @date 2017-04-22 13:38
	 */
	public static function array_multi2single($array){
		static $result = []; //定义一个静态数组常量用于保存结果
		foreach($array as $k => $v){
			if(is_array($v)){
				//如果是数组则重复调用此方法
				static::array_multi2single($array);
			}else{
				$result[] = $v;
			}
		}
		return $result;
	}
	
	/**
	 * 获取http协议类 http(s)
	 * @return string
	 */
	public static function getHttpType(){
		return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';
	}
	
	/**
	 * 判断http协议类 http(s)
	 * @return bool
	 */
	public static function isHttps(){
		if(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
			return true;
		}elseif(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
			return true;
		}elseif(! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'){
			return true;
		}
		return false;
	}
	
	/**
	 * 判断是否是移动设备
	 * @return bool
	 */
	public static function isMobile(){
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
		$mobile_browser = '0';
		if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
			$mobile_browser++;
		if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
			$mobile_browser++;
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
			$mobile_browser++;
		if(isset($_SERVER['HTTP_PROFILE']))
			$mobile_browser++;
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
		$mobile_agents = [
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda','xda-'
		];
		if(in_array($mobile_ua, $mobile_agents))
			$mobile_browser++;
		if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
			$mobile_browser++;
		// Pre-final check to reset everything if the user is on Windows
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
			$mobile_browser=0;
		// But WP7 is also Windows, with a slightly different characteristic
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
			$mobile_browser++;
		if($mobile_browser>0)
			return true;
		else
			return false;
	}
	
	/**
	 * 将数值三位分割，加千分位的分隔符(多用于钱数,金额)
	 * @param $count
	 * @return mixed
	 */
	public static function formatCount($count){
		if($count/1000 >= 1){
			$count = preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', $count);
		}
		return $count;
	}
	
	/**
	 * 生成随机码
	 * @param int $len
	 * @param int $lvl
	 * @return string
	 */
	public static function makeRandCode($len = 2, $lvl = 0){
		if($len < 2) die('The LEN can not be less than 2.');
		if($lvl < 0) die('The LVL can not be less than 0.');
		$lvl = ($lvl > 3) ? 3 : $lvl;
		$str = '9876543210';
		switch($lvl){
			case 1:
				$arr = ['zyxwvutsrqponmkjihgfedcba', 'ZYXWVUTSRQPONMKJIHGFEDCBA'];
				$str = $arr[array_rand($arr, 1)];
				break;
			case 2:
				$arr = ['z9yx4wvu1tsrq7pon5mk2ji6h3gfe0dcb8a', 'Z0YXW1VU8TS7RQPO4NM9KJ6IH2GFE3DCB5A'];
				$str = $arr[array_rand($arr, 1)];
				break;
			case 3:
				$str = 'WzTuVUtoGFE54DCba9xwv8kjgfedc63XS0ZYRsrON21MKJqpQP7nmIHihBAy';
				break;
		}
		$len = ($len > strlen($str)) ? strlen($str) : $len;
		$code = '';
		for($i = 0; $i < $len; $i++){
			$code .= $str[mt_rand(0, strlen($str)-1)];
		}
		//第一位不为0
		if($code{0} == '0'){
			$code = rand(1, 9).substr($code, 1, strlen($code));
		}
		return $code;
	}
	
	/**
	 * 阿拉伯数字转中文大写金额
	 * @param $num
	 * @param bool|true $mode   模式(true 元 / false 点)
	 * @param bool|true $sim    中文大小写(true 一 / false 壹)
	 * @return string
	 */
	public static function numToCNMoney($num, $mode = true, $sim = true){
		if(!is_numeric($num)) return '含有非数字非小数点字符！';
		$char = $sim ? ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'] :
			['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
		$unit = $sim ? ['', '十', '百', '千', '', '万', '亿', '兆'] :
			['', '拾', '佰', '仟', '', '萬', '億', '兆'];
		$retval = $mode ? '元' : '点';
		//小数部分
		if(strpos($num, '.')){
			list($num,$dec) = explode('.', $num);
			$dec = strval(round($dec,2));
			if($mode){
				$retval .= "{$char[$dec['0']]}角{$char[$dec['1']]}分";
			}else{
				for($i = 0,$c = strlen($dec);$i < $c;$i++) {
					$retval .= $char[$dec[$i]];
				}
			}
		}
		//整数部分
		$str = $mode ? strrev(intval($num)) : strrev($num);
		for($i = 0,$c = strlen($str);$i < $c;$i++) {
			$out[$i] = $char[$str[$i]];
			if($mode){
				$out[$i] .= $str[$i] != '0'? $unit[$i%4] : '';
				if($i>1 and $str[$i]+$str[$i-1] == 0){
					$out[$i] = '';
				}
				if($i%4 == 0){
					$out[$i] .= $unit[4+floor($i/4)];
				}
			}
		}
		$retval = join('', array_reverse($out)) . $retval;
		return $retval;
	}
	
	/**
	 * 获取在线IP
	 * @return string
	 */
	public static function getIP(){
		$ip="0.0.0.0";
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$ip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $ip, $onlineipmatches);
		$ip = $onlineipmatches[0] ? $onlineipmatches[0] : '0.0.0.0';
		unset($onlineipmatches);
		return $ip;
	}
	
	/**
	 * 手机号隐藏（指定位置用星号'*'或特殊字符代替）
	 * @param string $mobile    手机号
	 * @param string $symbol    替代符
	 * @param int $start        开始位置
	 * @param int $length       替换长度
	 * @return mixed
	 * @example    hideMobile('18161295668', '*', 3, 5);
	 */
	public static function hideMobile($mobile, $symbol = '*', $start = 1, $length = 1){
		$nsymbol = $symbol;
		$len = strlen($mobile);
		if(($len > $length) && (($start + $length) <= $len)){
			for($i = 0; $i < $length - 1; $i++){
				$nsymbol .= '*';
			}
			$mobile = substr_replace($mobile, $nsymbol, $start, $length);
		}
		return $mobile;
	}
	
	/**
	 * 判断url是否带参数并返回连接符
	 * @param  string   $url 网址字串
	 * @return string    返回参数连接符 ? 或 &
	 */
	public static function getSymbol($url){
		return ((strpos($url, '?') !== false) ? '&' : '?');
	}
	
	/**
	 * 判断url是否带有井号(#)
	 * @param $url  网址字串
	 * @return bool
	 */
	public static function hasHash($url){
		return ((strpos($url, '#') !== false) ? true : false);
	}
	
	/**
	 * 从网址字串中获取参数值
	 * @param $url      网址字串
	 * @return array    参数及参数值数组
	 */
	public static function getUrlStrParam($url){
		$data = array();
		$param = explode('?', $url);
		$parameter = explode('&', end($param));
		foreach($parameter as $val){
			$tmp = explode('=', $val);
			if(isset($tmp[0]) && isset($tmp[1])){
				$data[$tmp[0]] = $tmp[1];
			}
		}
		return $data;
	}
	
	/**
	 * 从网址字串中获取文件名
	 * @param  string $url    网址字串
	 * @return  string
	 */
	public static function getUrlStrFileName($url){
		$expSprit = explode('/', $url);
		$fileName = end($expSprit); //将数组内部指针指向最后一个元素，并返回该元素的值（如果成功）
		if(strpos($url, '?') !== false){
			//带参数
			$expQue   = explode('?', $fileName);
			$fileName = reset($expQue); //将数组内部指针指向第一个元素，并返回该元素的值（如果成功）
		}else{
			if(self::hasHash($fileName)){
				//带井号
				$expHash  = explode('#', $fileName);
				$fileName = reset($expHash);
			}
		}
		return $fileName;
	}
	
	/**
	 * 获取文件扩展名
	 * @param  string $fileName 文件全名
	 * @return  string
	 */
	public static function getFileExt($fileName){
		$expDot  = explode('.', $fileName);
		$fileExt = array_pop($expDot); //删除数组中的最后一个元素
		//$fileExt = end($expDot); //将数组内部指针指向最后一个元素 array_pop 、 end 返回结果一样
		$fileExt = trim($fileExt);
		$fileExt = '.' . strtolower($fileExt);
		return $fileExt;
	}
	
	/**
	 * 获取文件大小
	 * @param $fileName
	 * @return array|bool
	 */
	public static function getFileSize($fileName) {
		if(!file_exists($fileName)) return false;
		$size = filesize($fileName);
		$units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		$format = $original = ['size' => $size, 'unit' => $units[0]];
		if($size > 0) {
			$fzise = round($size/pow(1024, ($i = floor(log($size, 1024)))), 2); //保留两位小数点
			$format = ['size' => $fzise, 'unit' => $units[$i]];
		}
		return ['original' => $original, 'format' => $format];
	}
	
	/**
	 * 铭感词过滤 加空格
	 * @param $dirtyFile    铭感词文件路径
	 * @param $string       原字串
	 * @return mixed|string
	 */
	public static function filterBadWords($dirtyFile, $string){
		$dirtyWord = file_get_contents($dirtyFile); //说明:铭感词之间以英文逗号,分隔并且是base64字串
		$keyArr = explode(',', base64_decode($dirtyWord)); //base64解码并将字符分割为数组
		preg_match_all("/./us", $string, $match);
		$strLen = count($match[0]); //原字符长度(字符个数)
		$strNum = 0;
		$newStr = '';
		foreach($keyArr as $key){
			if(strpos($string, $key) !== false){
				$num = substr_count($string, $key); //计算字串出现的次数
				preg_match_all("/./us", $key, $_match);
				$keyLen = count($_match[0]);
				$nkey = mb_substr($key, 0, $keyLen-1, 'utf-8') . ' '. mb_substr($key, $keyLen-1, $keyLen, 'utf-8');
				$newStr .= str_replace($key, $nkey, $string); //替换字符
				$newStr = str_replace($key, $nkey, $newStr);
				$strNum += $num;
			}
		}
		$newStr = mb_substr($newStr, 0, $strLen+$strNum, 'utf-8');
		return empty($newStr) ? $string : $newStr;
	}
	
	/**
	 * 检测字串中是否存在某些字符
	 * @param $str      字符串
	 * @param $fstr     要查找的字符(多个用,隔开)
	 * @return bool
	 */
	public static function strExists($str, $fstr){
		if(!is_string($str)){
			die('The first parameter is not a string.');
		}
		$keyArr = explode(',', $fstr); //将字符分割为数组
		$i = 0;
		foreach($keyArr as $key){
			if(strpos($str, $key) !== false){
				$i++;
			}
		}
		return ($i > 0) ? true : false;
	}
	
	/**
	 * 计算中文字符串长度  字符个数(非字节数)
	 * @param null $string
	 * @return int
	 */
	public static function utf8StrLen($string = null) {
		preg_match_all("/./us", $string, $match); //将字符串分解为单元
		return count($match[0]); //返回单元个数
	}
	
	/**
	 * 检测是否有连续的字符
	 * @param $str      字符串
	 * @param $len      连续长度
	 * @return bool
	 */
	public static function seriesExists($str, $len){
		return (preg_match("/([\x{4e00}-\x{9fa5}])\\1{".($len-1).",}/u", $str) == 1) ? true : false;
	}
	
	/**
	 * 创建多级目录
	 * @param string $path      路径
	 * @param int $mode         权限
	 * @param bool $recursive   是否设置递归模式
	 * @return bool
	 */
	public static function mkDirs($path, $mode = 0777, $recursive = true){
		return file_exists($path) || @mkdir($path, $mode, $recursive) ? true : false;
	}
	
	/**
	 * 删除所有空目录
	 * 使用shell则简单很多：find 目标文件夹 -mindepth 1 -depth -empty -type d -exec rm -r {} \;
	 * @param $path
	 */
	public static function rmEmptyDir($path){
		if(is_dir($path) && ($handle = opendir($path))!==false){
			while(($file=readdir($handle))!==false){// 遍历文件夹
				if($file!='.' && $file!='..'){
					$curfile = $path.'/'.$file;// 当前目录
					if(is_dir($curfile)){// 目录
						self::rmEmptyDir($curfile);// 如果是目录则继续遍历
						if(count(scandir($curfile))==2){//目录为空,=2是因为.和..存在
							rmdir($curfile);// 删除空目录
						}
					}
				}
			}
			closedir($handle);
		}
	}
	
	/**
	 * 文件或目录权限检查函数
	 * @param $filePath     文件路径
	 * @return bool|int     返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
	 *                      返回值在二进制计数法中，四位由高到低分别代表
	 *                      可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
	 */
	public static function fileModeInfo($filePath){
		//如果不存在，则不可读、不可写、不可改
		if(!file_exists($filePath)){
			return false;
		}
		$mark = 0;
		if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN'){
			//测试文件
			$testFile = $filePath . '/cf_test.txt';
			//如果是目录
			if(is_dir($filePath)){
				//检查目录是否可读
				$dir = @opendir($filePath);
				if($dir === false){
					return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
				}
				if(@readdir($dir) !== false){
					$mark ^= 1; //目录可读 001，目录不可读 000
				}
				@closedir($dir);
				//检查目录是否可写
				$fp = @fopen($testFile, 'wb');
				if($fp === false){
					return $mark; //如果目录中的文件创建失败，返回不可写。
				}
				if(@fwrite($fp, 'directory access testing.') !== false){
					$mark ^= 2; //目录可写可读011，目录可写不可读 010
				}
				@fclose($fp);
				@unlink($testFile);
				//检查目录是否可修改
				$fp = @fopen($testFile, 'ab+');
				if($fp === false){
					return $mark;
				}
				if(@fwrite($fp, "modify test.\r\n") !== false){
					$mark ^= 4;
				}
				@fclose($fp);
				//检查目录下是否有执行rename()函数的权限
				if(@rename($testFile, $testFile) !== false){
					$mark ^= 8;
				}
				@unlink($testFile);
			}
			//如果是文件
			elseif (is_file($filePath)){
				//以读方式打开
				$fp = @fopen($filePath, 'rb');
				if($fp){
					$mark ^= 1; //可读 001
				}
				@fclose($fp);
				//试着修改文件
				$fp = @fopen($filePath, 'ab+');
				if($fp && @fwrite($fp, '') !== false){
					$mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
				}
				@fclose($fp);
				//检查目录下是否有执行rename()函数的权限
				if(@rename($testFile, $testFile) !== false){
					$mark ^= 8;
				}
			}
		}else{
			if(@is_readable($filePath)){
				$mark ^= 1;
			}
			if(@is_writable($filePath)){
				$mark ^= 14;
			}
		}
		return $mark;
	}
	
	/**
	 * 读取CSV文件中的某几行数据
	 * @param $csvFile      csv文件路径
	 * @param $lines        读取行数
	 * @param int $offset   起始行数
	 * @return array|bool
	 */
	public static function csvGetLines($csvFile, $lines, $offset = 0) {
		if(!$fp = fopen($csvFile, 'r')) {
			return false;
		}
		$i = $j = 0;
		while (false !== ($line = fgets($fp))) {
			if($i++ < $offset) {
				continue;
			}
			break;
		}
		$data = array();
		while(($j++ < $lines) && !feof($fp)) {
			$data[] = fgetcsv($fp);
		}
		fclose($fp);
		return $data;
	}
	
	/**
	 * 二维数组数据分组聚合
	 * @param $arr      数据数组
	 * @param $gkey     分组键值
	 * @return array
	 */
	public static function arrayGroup($arr, $gkey){
		$kArr = array();
		$gArr = array();
		$nArr = array();
		foreach($arr as $key => $val){
			if(!in_array($val[$gkey], $kArr)){
				$ukey = count($kArr);
				$kArr[] = $val[$gkey];
				$gArr[$ukey][] = $val;
			}else{
				$ukey = array_search($val[$gkey], $kArr);
				$gArr[$ukey][] = $val;
			}
		}
		foreach($gArr as $key => $val){
			if(is_array($val)){
				foreach($val as $k => $v){
					array_push($nArr, $v);
				}
			}
		}
		return $nArr;
	}
	
	/**
	 * 字符加密、解密(解密必须知道加密秘钥,否则无法解密)
	 * @param $string         字符串
	 * @param string $type    类型(E加密 / D解密)
	 * @param string $key     秘钥
	 * @return mixed|string
	 */
	public static function encrypt($string, $type = 'E', $key = '') {
		$key = md5($key);
		$key_length = strlen($key);
		$string = $type == 'D' ? base64_decode($string) : substr(md5($string.$key),0,8).$string;
		$string_length = strlen($string);
		$rndkey = $box = array();
		$result = '';
		for($i=0; $i<=255; $i++){
			$rndkey[$i] = ord($key[$i%$key_length]);
			$box[$i] = $i;
		}
		for($j=$i=0; $i<256; $i++){
			$j = ($j+$box[$i]+$rndkey[$i])%256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for($a=$j=$i=0; $i<$string_length; $i++){
			$a = ($a+1)%256;
			$j = ($j+$box[$a])%256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
		}
		if($type=='D'){
			if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
				return substr($result,8);
			}else{
				return '';
			}
		}else{
			return str_replace('=','',base64_encode($result));
		}
	}
	
	/**
	 * 获取指定日期的时间戳
	 * @param string $datetimes     要转换的时间(2016-04-26 18:25:43)
	 * @param bool|false $digits    位数
	 * @return int|string
	 * @author pengyuanhong
	 */
	public static function getTimestamp($datetimes = '', $digits = false){
		$digits = $digits > 10 ? $digits : 10;
		if($datetimes == ''){
			$digits = $digits - 10;
			if ((!$digits) || ($digits == 10)) {
				return time();
			}else {
				return number_format(microtime(true), $digits, '', '');
			}
		}else{
			$times = strtotime($datetimes);
			//str_pad(待补, 填补后位数, 补位符, 补位方式); LEFT / RIGHT / BOTH
			return str_pad($times, $digits, "0", STR_PAD_RIGHT); //自动补位
		}
	}
	
	/**
	 * 指定时间戳(可大于10位)转换成日期格式(10位)
	 * @param bool|false $times
	 * @return string
	 * @author pengyuanhong
	 */
	public static function getTimeDate($times = false){
		if($times === false){
			return date('Y-m-d H:i:s');
		}else{
			if(strlen($times) > 10){
				return date('Y-m-d H:i:s', substr($times, 0, 10));
			}else{
				return date('Y-m-d H:i:s', $times);
			}
		}
	}
	
	/**
	 * x位时间戳转10位时间戳
	 * @param $time
	 * @return float|int
	 */
	public static function timeLenToTen($time){
		$length = strlen($time);
		if($length > 10){
			$per = (int) str_pad(1, $length-10+1, '0', STR_PAD_RIGHT);
			return round($time/$per);
		}else if($length < 10){
			return (int) str_pad($time, 10, '0', STR_PAD_RIGHT);
		}
		return $time;
	}
	
	/**
	 * 文件下载并保存
	 * @param $url                  文件下载地址
	 * @param string $filename      保存文件名
	 * @param string $path          保存路径
	 * @param int $timeout          设置超时时间(s)
	 * @return bool|mixed|string
	 */
	public static function httpCopy($url, $filename = '', $path = '', $timeout = 60) {
		$file = empty($filename) ? pathinfo($url, PATHINFO_BASENAME) : $filename; //文件名
		$dir  = empty($path) ? pathinfo($file, PATHINFO_DIRNAME) : $path; //保存目录
		!is_dir($dir) && @mkdir($dir, 0755, true); //创建目录
		$save = empty($path) ? $file : $dir . '/' . $file;
		$url  = str_replace(" ", "%20", $url); //替换url
		//下载并保存
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$temp = curl_exec($ch);
			if(@file_put_contents($save, $temp) && !curl_error($ch)){
				return $save;
			}else{
				return false;
			}
		}else{
			$opts = [
				'http' => [
					'method' => 'GET',
					'header' => '',
					'timeout' => $timeout
				]
			];
			$context = stream_context_create($opts);
			if(@copy($url, $save, $context)) {
				//$http_response_header
				return $save;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * curl 请求方法
	 * @param string $url       请求地址
	 * @param array $body       请求主体(主体参数部分)
	 * @param array $header     头部参数
	 * @param string $method    请求方法(GET / POST / PUT / DELETE)
	 * @param int $timeOut      请求超时时间(s)
	 * @return mixed
	 */
	public static function curlRequest($url, $body = [], $header = [], $method = "POST", $timeOut = 60){
		//1.创建一个curl资源
		$ch = curl_init();
		//2.设置URL和相应的选项
		curl_setopt($ch,CURLOPT_URL,$url);//设置url
		//1)设置请求头
		//array_push($header, 'Accept:application/json');
		//array_push($header,'Content-Type:application/json');
		//array_push($header, 'http:multipart/form-data');
		//设置为false,只会获得响应的正文(true的话会连响应头一并获取到)
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt ( $ch, CURLOPT_TIMEOUT,$timeOut); // 设置超时限制防止死循环
		//设置发起连接前的等待时间，如果设置为0，则无限等待。
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeOut);
		//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//2)设备请求体
		if (count($body)>0) {
			//$b=json_encode($body,true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);//全部数据使用HTTP协议中的"POST"操作来发送。
		}
		//设置请求头
		if(count($header)>0){
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		}
		//上传文件相关设置
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 对认证证书来源的检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);// 从证书中检查SSL加密算
		//3)设置提交方式
		switch($method){
			case "GET":
				curl_setopt($ch,CURLOPT_HTTPGET,true);
				break;
			case "POST":
				curl_setopt($ch,CURLOPT_POST,true);
				break;
			case "PUT"://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请求。这对于执行"DELETE" 或者其他更隐蔽的HTTP
				curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
				break;
			case "DELETE":
				curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
				break;
		}
		//4)在HTTP请求中包含一个"User-Agent: "头的字符串。-----必设
		curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)' ); // 模拟用户使用的浏览器
		//5)
		//3.抓取URL并把它传递给浏览器
		$res=curl_exec($ch);
		if($res === false){
			echo 'Curl error: ' . curl_error($ch);
			exit;
		}
		$result=json_decode($res, true);
		//4.关闭curl资源，并且释放系统资源
		curl_close($ch);
		if(empty($result))
			return $res;
		else
			return $result;
		
	}
	
	/**
	 * yield生成器（大数据计算内存节省利器）
	 * yield生成器会把数据一行一行的读取并且同时清理掉你调用的那一行的内存（即是读一行清理一行内存）
	 * @param $data
	 * @return \Generator
	 */
	public static function yieldData($data){
		foreach ($data as $datum){
			yield $datum;
		}
	}
	
	/**
	 * 根据类型获取时间范围
	 * @param string $time_type  类型：yesterday 昨日、today 今日、current_week 本周、current_month 本月、last_month 近一月
	 * @return array
	 */
	public static function getRangeDatetime(string $time_type){
		$today_date = date('Y-m-d');
		$start_tag = ' 00:00:00';
		$end_tag = ' 23:59:59';
		switch ($time_type){
			case 'yesterday':
				// 昨日
				$begin_date = date('Y-m-d', strtotime('-1 day'));
				$end_date = $begin_date;
				break;
			case 'today':
				// 今日
				$begin_date = $today_date;
				$end_date = $begin_date;
				break;
			case 'current_week':
				// 本周
				$begin_date = date('Y-m-d', strtotime('-1 week Monday'));
				$end_date = $today_date;
				break;
			case 'current_month':
				// 本月
				$begin_date = date('Y-m-01');
				$end_date = $today_date;
				break;
			case 'last_month':
				// 近一月（一个月内）
				$begin_date = date('Y-m-d', strtotime('-1 month'));
				$end_date = $today_date;
				break;
			default:
				$begin_date = '1970-01-01';
				$end_date = $today_date;
				break;
		}
		$begin = $begin_date . $start_tag;
		$end = $end_date . $end_tag;
		return [
			'begin_time' => $begin,
			'end_time' => $end,
			'begin_at' => strtotime($begin),
			'end_at' => strtotime($end),
		];
	}
	
	/**
	 * 数组 转 对象
	 *
	 * @param array $arr 数组
	 * @return object
	 */
	public static function array_to_object($arr) {
		if (gettype($arr) != 'array') {
			return (object)array();
		}
		foreach ($arr as $k => $v) {
			if (gettype($v) == 'array' || getType($v) == 'object') {
				$arr[$k] = (object) self::array_to_object($v);
			}
		}
		
		return (object)$arr;
	}
	
	/**
	 * 对象 转 数组
	 *
	 * @param object $obj 对象
	 * @return array
	 */
	public static function object_to_array($obj) {
		$obj = (array)$obj;
		foreach ($obj as $k => $v) {
			if (gettype($v) == 'resource') {
				return array();
			}
			if (gettype($v) == 'object' || gettype($v) == 'array') {
				$obj[$k] = (array) self::object_to_array($v);
			}
		}
		
		return $obj;
	}
	
	/**
	 * 数据类型强制转换 string -> int
	 * @param $data
	 * @param array $integer_fields
	 * @return array
	 */
	public static function string_to_integer($data, array $integer_fields){
		if(is_object($data)) $data = self::object_to_array($data);
		foreach ($data as $k => &$v){
			if(in_array($k, $integer_fields)){
				$data[$k] = intval($v);
			}
			continue;
		}
		return $data;
	}
	
	/**
	 * 字符串ip转整型
	 * @param $ip
	 * @return float|int
	 */
	public static function ip2long($ip){
		return $ip ? bindec(decbin(ip2long($ip))) : 0;
	}
	
	/**
	 * 时间格式化
	 * @param $time
	 * @param string $format
	 * @return false|string
	 */
	public static function formatDate($time, $format = 'd'){
		$rtime = date ("Y-m-d H:i", $time);
		$htime = date ("H:i", $time);
		$dtime = date ("Y-m-d", $time);
		$time  = time () - $time;
		if ($time < 60) {
			$str = '刚刚';
		} elseif ($time < 60 * 60) {
			$min = floor ( $time / 60 );
			$str = $min . '分钟前';
		} elseif ($time < 60 * 60 * 24) {
			$h = floor ( $time / (60 * 60) );
			$str = $h . '小时前';
		} elseif ($time < 60 * 60 * 24 * 3) {
			if (date("Y-m-d", strtotime("-1 day")) == $dtime)
				$str = '昨天 ' . $htime;
			else if(date("Y-m-d", strtotime("-2 day")) == $dtime)
				$str = '前天 ' . $htime;
			else
				$str = $format == 'd' ? $dtime : $rtime;
		} else {
			$str = $format == 'd' ? $dtime : $rtime;
		}
		return $str;
	}
	
	/**
	 * 获取周几
	 * @param $date
	 * @param string $zh
	 * @return array
	 */
	public static function formatWeek($date, $zh = '周'){
		$arr = ['日', '一', '二', '三', '四', '五', '六'];
		$time = strtotime($date);
		$wk = date('w', $time);
		$z = $arr[$wk] ?? $arr[0];
		$fzh = $zh . $z;
		return [
			'date' => $date,
			'time' => $time,
			'week' => $wk,
			'week_zh' => $fzh,
		];
	}
	
	/**
	 * 写日志
	 * @param $file
	 * @param $msg
	 * @param bool $first
	 */
	public static function writeLog($file, $msg, $first = false){
		$msg = is_string($msg) ? $msg : json_encode($msg);
		$prefix = $first ? "\r\n" : "";
		$msg = $prefix . "[".date('Y-m-d H:i:s')."] " . $msg . "\r\n";
		file_put_contents($file, $msg, FILE_APPEND);
	}
	
	/**
	 * 生成随机流水号
	 * @param string $flag  标识（通常为大写字母）
	 * @param int $sLen 流水号长度
	 * @param bool $flagFirst   标识是否在第一位
	 * @return string
	 */
	public static function makeSerial($flag = 'F', $sLen = 24, $flagFirst = true){
		$max = $sLen > 14 ? $sLen - 14 : 14;
		$len = strlen($flag);
		$prefix = $flagFirst ? strtoupper($flag) . date('YmdHis') : date('YmdHis') . strtoupper($flag);
		if($len > $max) {
			$diff = $sLen - 15;
		}else{
			$diff = $max - $len;
		}
		$left = str_pad(1, $diff, '0', STR_PAD_RIGHT);
		$right = str_pad(9, $diff, '9', STR_PAD_RIGHT);
		// 随机数补位
		$rand = mt_rand($left, $right);
		return $prefix . $rand;
	}
}