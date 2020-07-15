<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/11   8:49
 */

namespace bydls\Utils;


class Str
{

    protected static $studlyCache = [];

    protected static $snakeCache = [];

    /**字符串转换成驼峰大小写
     * @param string $value
     * @return string
     * @author: hbh
     * @Time: 2020/7/11   8:53
     */
    public static function studlyCap(string $value): string
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**驼峰字符串转蛇格
     * @param string $value
     * @param string $delimiter
     * @return string
     * @author: hbh
     * @Time: 2020/7/11   9:11
     */
    public static function studlyCapToSnake(string $value, string $delimiter = '_'): string
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }

    /**判断数组或对象中是否含有某字符串 （一维）
     * @param string $haystack
     * @param array|object $needles
     * @return bool
     * @author: hbh
     * @Time: 2020/7/11   9:01
     */
    public static function contains(string $haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ('' !== $needle && false !== mb_strpos($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**全小写
     * @param string $value
     * @return string
     * @author: hbh
     * @Time: 2020/7/11   9:12
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**返回字符串指定的中文占位长度
     * @param string $value
     * @param int $limit
     * @param string $end
     * @return string
     * @author: hbh
     * @Time: 2020/7/11   9:15
     */
    public static function limit(string $value, int $limit = 100, string $end = '...'): string
    {
        $limit*=2;
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }

    /**处理标题格式  奖每个单词的首字母转换成大写
     * @param string $value
     * @return string
     * @author: hbh
     * @Time: 2020/7/11   10:36
     */
    public static function title(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }


    /**判断以特定字符结尾
     * @param String $haystack
     * @param String $needles
     * @return bool
     */
    public static function endsWith(String $haystack,String $needles):bool
    {
        return substr($haystack, -strlen($needles)) === (string) $needles?true:false;
    }
}