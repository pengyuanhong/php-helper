<?php
/**
 * Created by PhpStorm.
 * User: Kyle
 * Date: 2020/6/2
 * Time: 11:53 AM
 */

namespace PhpHelper\Validate;


class Validate
{
	/**
	 * 手机号验证
	 * @param $phone
	 * @return bool/string
	 */
	public static function phone($phone){
		return self::regexMethod('/^(0|86|17951)?1[3,4,5,6,7,8,9]{1}[0-9]{1}[0-9]{8}$/', $phone);
	}
	
	/**
	 * 电话号码验证
	 * @param $tel
	 * @return bool
	 */
	public static function tel($tel){
		return self::regexMethod('^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$', $tel);
	}
	
	/**
	 * 邮箱验证
	 * @param $email
	 * @return bool/string
	 */
	public static function email($email){
		return self::regexMethod('/^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/i', $email);
	}
	
	/**
	 * 身份证验证 IDCard
	 * @param $IDCard
	 * @return bool
	 */
	public static function idCard($IDCard){
		return self::regexMethod('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}|[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/', $IDCard);
	}
	
	/**
	 * 年份验证 格式：yyyy
	 * @param $year
	 * @return bool
	 */
	public static function year($year) {
		return self::regexMethod('/^(\d{4})$/', $year);
	}
	
	/**
	 * 月份验证 格式:mm
	 * @param $month
	 * @return bool
	 */
	public static function month($month) {
		return self::regexMethod('/^0?([1-9])$|^(1[0-2])$/', $month);
	}
	
	/**
	 * 日期验证 格式：yyyy-mm-dd
	 * @param $date
	 * @return bool
	 */
	public static function date($date) {
		return self::regexMethod('/^(\d{4})-(0?\d{1}|1[0-2])-(0?\d{1}|[12]\d{1}|3[01])$/', $date);
	}
	
	/**
	 * 日期时间验证 格式：yyyy-mm-dd hh:ii:ss
	 * @param $datetime
	 * @return bool
	 */
	public static function dateTime($datetime) {
		return self::regexMethod('/^(\d{4})-(0?\d{1}|1[0-2])-(0?\d{1}|[12]\d{1}|3[01])\s(0\d{1}|1\d{1}|2[0-3]):[0-5]\d{1}:([0-5]\d{1})$/', $datetime);
	}
	
	/**
	 * 邮编验证
	 * @param $zipCode
	 * @return bool
	 */
	public static function zipCode($zipCode) {
		return static::regexMethod('/[1-9]\d{5}(?!\d)/', $zipCode);
	}
	
	/**
	 * URL地址验证
	 * @param $url
	 * @return bool
	 */
	public static function url($url) {
		return self::regexMethod('/\b(([\w-]+:\/\/?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/', $url);
	}
	
	/**
	 * IPv4验证
	 * @param $ipv4
	 * @return bool
	 */
	public static function ipv4($ipv4) {
		return self::regexMethod('/^(((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))\.){3}((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/', $ipv4);
	}
	
	/**
	 * IPv6验证
	 * @param $ipv6
	 * @return bool
	 */
	public static function ipv6($ipv6) {
		return self::regexMethod('/^([\da-fA-F]{1,4}:){7}[\da-fA-F]{1,4}$/', $ipv6);
	}
	
	/**
	 * 匹配正则公共方法
	 * @param string $pattern  正则表达式
	 * @param string $subject  需要匹配检索的对象
	 * @param bool|false $isMatches 是否返回存储匹配结果的数组
	 * @return bool
	 */
	public static function regexMethod($pattern, $subject, $isMatches = false){
		if($isMatches){
			return preg_match($pattern, $subject, $matches) ? $matches : false;
		}
		return preg_match($pattern, $subject) ? true : false;
	}
}