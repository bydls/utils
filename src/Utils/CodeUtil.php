<?php
/**
 * @Desc:代码
 * @author: hbh
 * @Time: 2020/4/10   19:36
 */

namespace bydls\Utils;


trait CodeUtil
{
    /**生成8位随机扰码
     * @return false|string
     * @author: hbh
     * @Time: 2020/4/10   19:49
     */
    public static function getSalt()
    {
        $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        return substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 9), 8);
    }

    /**对密码进行加密
     * @param $password
     * @param $salt
     * @return string
     * @author: hbh
     * @Time: 2020/4/9   19:14
     */
    public static function hashMixed($password)
    {
        $pwd_md5 = md5($password);
        $salt_md5 = md5(config('cipher.password_key')) ?: $pwd_md5;
        $mixed = [];
        for ($i = 0; $i < 32; $i++) $mixed[$i] = $pwd_md5[$i] . $salt_md5[$i];
        $strMixed = implode("", $mixed);
        return md5($strMixed);
    }
    /**
     *  生成一个随机的小数
     * @param int $min
     * @param int $max
     * @param int $precision
     * @return float|int
     */
    /**生成一个随机小数
     * @param int $min 最小边界
     * @param int $max  最大边界
     * @param int $precision    小数点精度
     * @return false|float
     * @author: hbh
     * @Time: 2020/6/3   10:47
     */
    public static function randomFloat($min = 0, $max = 1, $precision = 2)
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $precision);
    }


    /**生成一个唯一的 ID
     * @return string
     * @author: hbh
     * @Time: 2020/6/3   10:48
     */
    public static function getUniqueStr()
    {
        return uniqid(mt_rand(100, 999), true);
    }
}
