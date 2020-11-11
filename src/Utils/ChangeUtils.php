<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/6/3   10:46
 */

namespace bydls\Utils;


trait ChangeUtils
{
    /**数组转对象
     * @param $arr
     * @param bool $json
     * @return mixed
     * @author: hbh
     * @Time: 2020/6/3   11:37
     */
    public static function array_to_object($arr, $json = false)
    {
        if ($json) {
            return json_decode(json_encode($arr));
        }
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $arr[$k] = (object)array_to_object($v);
            }
        }

        return (object)$arr;
    }

    /**对象转数组
     * @param $obj
     * @return mixed
     * @author: hbh
     * @Time: 2020/6/3   11:34
     */
    public static function object_to_array($obj)
    {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)object_to_array($v);
            }
        }

        return $obj;
    }

    /**字符串压缩
     * @param string $str
     * @param int $level
     * @return string
     * @author: hbh
     * @Time: 2020/11/11   14:31
     */
    public static function str_compress(string $str,int $level = 9)
    {
        return base64_encode(gzcompress($str, $level));
    }
    /**字符串解压 被 str_compress 压缩过的
     * @param string $str
     * @param int $level
     * @return string
     * @author: hbh
     * @Time: 2020/11/11   14:31
     */
    public static function str_uncompress(string $str)
    {
        return gzuncompress(base64_decode($str));
    }
}