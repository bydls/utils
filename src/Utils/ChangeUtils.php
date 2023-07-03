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
     * @param array $arr
     * @param bool $json
     * @return object
     * @author: hbh
     * @Time: 2020/6/3   11:37
     */
    public static function arrayToObject(array $arr, $json = false): object
    {
        if ($json) {
            return json_decode(json_encode($arr));
        }
        if (!is_array($arr)) {
            return null;
        }
        foreach ($arr as $k => $v) {
            if (is_array($arr) || is_object($arr)) {
                $arr[$k] = (object)self::array_to_object($v);
            }
        }

        return (object)$arr;
    }

    /**对象转数组
     * @param object $obj
     * @return array
     * @author: hbh
     * @Time: 2020/6/3   11:34
     */
    public static function objectToArray($obj): array
    {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (is_resource($v)) {
                return [];
            }
            if (is_object($v) || is_array($v)) {
                $obj[$k] = (array)self::objectToArray($v);
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
    public static function strCompress(string $str, int $level = 9): string
    {
        return base64_encode(gzcompress($str, $level));
    }

    /**字符串解压 被 str_compress 压缩过的
     * @param string $str
     * @return string
     * @author: hbh
     * @Time: 2020/11/11   14:31
     */
    public static function strUnCompress(string $str): string
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
    public static function format(array &$array, $child_key = 'child'): array
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

    /**对字符串中间部分打码
     * @param string $string
     * @param string $mask 掩码
     * @param int $start_len 起始长度
     * @param int $end_len 结束长度
     * @return string
     */
    public static function protectString(string $string, string $mask = '*', int $start_len = 2, int $end_len = 2): string
    {
        $str = '';
        $temp = strlen($string) - $start_len - $end_len;
        if ($temp < 1) return $string;
        while ($temp) {
            $str .= $mask;
            $temp--;
        }
        return substr($string, 0, $start_len) . $str . substr($string, -$end_len);
    }

    /**
     * 将数值金额转换为中文大写金额
     * @param $amount float 金额(支持到分)
     * @param $type   int   补整类型,0:到角补整;1:到元补整
     * @return mixed 中文大写金额
     */
    public static function convertAmountToCn($amount, $type = 1): string
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
    public static function floatNumber(float $number, int $n = 2): string
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

    /**
     * @Desc:金额格式化
     * @param float $amount
     * @return string
     * @Time: 2023/7/3 9:43
     */
    public static function formatAmount(float $amount): string
    {

        return number_format($amount, 2, '.', ',');
    }

    /**
     * @Desc:解析身份证信息,转化成数组,例如:所在地区\出生日期\性别等
     * @param string $ID_number
     * @return array
     * @Time: 2022/5/17 14:29
     */
    public static function getIdNumberInfo(string $ID_number): array
    {
        $personal = array();
        $ID_number = trim($ID_number . '');
        $lenth = strlen($ID_number);

        if ($lenth <> 15 && $lenth <> 18) {
            return $personal;
        }

        $birthday = ($lenth == 15) ? ('19' . substr($ID_number, 6, 6)) : substr($ID_number, 6, 8);
        $personal['birthday'] = date("Y-m-d", strtotime($birthday));

        // 性别(0:未知;1:男;2:女)
        $personal['sex'] = substr($ID_number, ($lenth == 18 ? -2 : -1), 1) % 2 ? '1' : '2';
        return $personal;
    }

    /**方法/接口调用失败返回
     * @param string $msg
     * @param array $data
     * @return array
     */
    public static function apiReturnError($msg = '', $data = []): array
    {
        return ['status' => -200, 'msg' => $msg, 'data' => $data];
    }

    /**方法/接口调用成功返回
     * @param string $msg
     * @param array $data
     * @return array
     */
    public static function apiReturnSuccess($data = [], $msg = ''): array
    {
        return ['status' => 200, 'msg' => $msg, 'data' => $data];
    }

    /**生成签名
     * @param array $params
     * @return string
     */
    public static function sign(array $params): string
    {
        ksort($params);
        $mergeStr = implode("", $params);
        $encodedStr = urlencode($mergeStr);

        return md5($encodedStr);
    }


    /**获取数组/对象中某字段的值，如果没有则赋予默认值
     * @param  $data
     * @param string $element
     * @param  $default
     * @return string
     */
    public static function fetch($data, string $element, $default = '')
    {
        $return = $default;
        if (true === is_object($data) && true === isset($data->$element)) {
            $return = $data->$element;
        } elseif (true === is_array($data) && true === isset($data[$element])) {
            $return = $data[$element];
        }

        return $return;
    }


    /**
     * @Desc:数组元素转成字符串格式
     * @param array $array
     * @return array
     * @author: hbh
     * @Time: 2022/10/8 9:51
     */
    public static function arrayItemToString(array &$array): array
    {
        if (empty($array)) {
            return $array;
        }

        foreach ($array as &$item) {
            if (is_array($item)) {
                $item = self::arrayItemToString($item);
            } elseif ($item === false) {
                $item = '0';
            } else {
                $item = strval($item);
            }

        }
        return $array;
    }

    /**
     * @Desc:银行账号脱敏
     * @param $account_number
     * @return string
     * @Time: 2023/3/24 11:43
     */
    public static function protectAccountNumber($account_number)
    {
        if ($account_number !== null && $account_number !== '') {
            if (stripos($account_number, '-') !== false) {
                //有-说明是子账号
                return self::protectString($account_number, '*', 4, 9);
            }
            return self::protectString($account_number, '*', 4, 4);
        }
        return $account_number;
    }

    /**
     * @Desc:证件号码脱敏
     * @param $certificate_no
     * @return string
     * @Time: 2023/3/24 11:43
     */
    public static function protectCertificateNo($certificate_no)
    {
        return self::protectString($certificate_no, '*', 3, 4);
    }
}