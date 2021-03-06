<?php
/**
 * @Desc:代码
 * @author: hbh
 * @Time: 2020/4/10   19:36
 */

namespace bydls\Utils;


trait CodeUtil
{

    /**生成指定位数的随机数
     * @param int $length
     * @return string
     * @throws \Exception
     * @author: hbh
     * @Time: 2020/7/11   10:27
     */
    public static function random(int $length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = function_exists('random_bytes') ? random_bytes($size) : mt_rand();

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
    /**对密码进行加密
     * @param $password
     * @param $salt //建议自己保存一个固定的扰码
     * @return string
     * @author: hbh
     * @Time: 2020/4/9   19:14
     */
    public static function hashMixed(string $password,string  $salt='bydls')
    {
        $pwd_md5 = md5($password);
        $salt_md5 = isset($salt) ?md5($salt):$pwd_md5;
        $mixed = [];
        for ($i = 0; $i < 32; $i++) $mixed[$i] = $pwd_md5[$i] . $salt_md5[$i];
        $strMixed = implode("", $mixed);
        return md5($strMixed);
    }

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
