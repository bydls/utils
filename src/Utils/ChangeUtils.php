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
    public static function str_compress(string $str, int $level = 9)
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

    /**多维关联数组转枚举数组
     * @param array $array
     * @param string $child_key
     * @return array
     * @author: hbh
     * @Time: 2021/3/9   16:10
     */
    public static function format(array &$array, $child_key = 'child')
    {
        if ($array) {
            $array = array_values($array);
            foreach ($array as &$item) {
                if (isset($item[$child_key])) {
                    $item[$child_key] = self::format($item[$child_key]);
                }
            }
        }
        return $array;
    }

    /**
     * 将数值金额转换为中文大写金额
     * @param $amount float 金额(支持到分)
     * @param $type   int   补整类型,0:到角补整;1:到元补整
     * @return mixed 中文大写金额
     */
    function convertAmountToCn($amount, $type = 1): string
    {
        // 判断输出的金额是否为数字或数字字符串
        if (!is_numeric($amount)) {
            return "要转换的金额只能为数字!";
        }

        // 金额为0,则直接输出"零元整"
        if ($amount == 0) {
            return "人民币零元整";
        }

        // 金额不能为负数
        if ($amount < 0) {
            return "要转换的金额不能为负数!";
        }

        // 金额不能超过万亿,即12位
        if (strlen($amount) > 12) {
            return "要转换的金额不能为万亿及更高金额!";
        }

        // 预定义中文转换的数组
        $digital = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        // 预定义单位转换的数组
        $position = array('仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '元');

        // 将金额的数值字符串拆分成数组
        $amountArr = explode('.', $amount);

        // 将整数位的数值字符串拆分成数组
        $integerArr = str_split($amountArr[0], 1);

        // 将整数部分替换成大写汉字
        $result = '人民币';
        $integerArrLength = count($integerArr);     // 整数位数组的长度
        $positionLength = count($position);         // 单位数组的长度
        for ($i = 0; $i < $integerArrLength; $i++) {
            // 如果数值不为0,则正常转换
            if ($integerArr[$i] !== 0) {
                $result .= $digital[$integerArr[$i]] . $position[$positionLength - $integerArrLength + $i];
            } else {
                // 如果数值为0, 且单位是亿,万,元这三个的时候,则直接显示单位
                if (($positionLength - $integerArrLength + $i + 1) % 4 === 0) {
                    $result .= $position[$positionLength - $integerArrLength + $i];
                }
            }
        }

        // 如果小数位也要转换
        if ($type === 0) {
            // 将小数位的数值字符串拆分成数组
            $decimalArr = str_split($amountArr[1], 1);
            // 将角替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[0] !== 0) {
                $result .= $digital[$decimalArr[0]] . '角';
            }
            // 将分替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[1] !== 0) {
                $result .= $digital[$decimalArr[1]] . '分';
            }
        } else {
            $result .= '整';
        }
        return $result;
    }


    /**格式化数字
     * @param float $number
     * @param int $n 保留几位小数
     * @return string
     */
    public function floatNumber(float $number, int $n = 2): string
    {
        if ($n < 0 || $n > 4) {
            return $number;

        }
        $number = sprintf("%." . $n . "f", $number);
        $length = strlen($number);  //数字长度
        $temp = ($n ? $n + 1 : 0);
        if ($length > (8 + $temp)) { //亿单位

            $str = ($n ? strstr($number, substr($number, -(8 + $temp)), ' ') . '.' . substr($number, -(8 + $temp), -(8 - $n + $temp)) : strstr($number, substr($number, -(8 + $temp)), ' ')) . "亿";
        } elseif ($length > (4 + $temp)) { //万单位
            //截取前俩为
            $str = ($n ? strstr($number, substr($number, -(4 + $temp)), ' ') . '.' . substr($number, -(4 + $temp), -(4 - $n + $temp)) : strstr($number, substr($number, -(8 + $temp)), ' ')) . "万";
        } else {
            return $number;
        }
        return $str;
    }
}