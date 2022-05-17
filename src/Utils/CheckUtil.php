<?php
/**
 * @Desc:验证
 * @author: hbh
 * @Time: 2020/4/10   11:21
 */

namespace bydls\Utils;


trait CheckUtil
{
    /**验证用户名是否合法
     * @param $username
     * @return bool
     * @author: hbh
     * @Time: 2020/4/10   11:12
     */

    public static function checkUserName($username): bool
    {
        return preg_match('/^[A-Za-z]{1}[A-Za-z0-9_-]{5,17}$/', $username);
    }

    /**验证密码是否合法
     * @param $password
     * @return bool
     * @author: hbh
     * @Time: 2020/4/10   11:19
     */
    public static function checkPassword($password): bool
    {
        return preg_match('/(?=^.{6,16}$)(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/', $password);
    }

    public static function checkNickname($nickname): bool
    {
        return (mb_strlen($nickname) < 20);
    }


    /**检查手机号格式是否正确
     * @param $string
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:26
     */
    public static function isMobile($string):bool
    {
        return preg_match('/^1[23456789]\d{9}$/', $string);
    }

    /**检测是否是身份证号
     * @param $string
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:26
     */
    /**
     *  检测是否是身份证号
     * @param $id_number
     * @return bool
     */
    public static function isIdNumber($id_number)
    {
        //老身份证长度15位，新身份证长度18位
        $length = strlen($id_number);
        if ($length == 15) { //如果是15位身份证

            //15位身份证没有字母
            if (!is_numeric($id_number)) {
                return false;
            }
            // 省市县（6位）
            $areaNum = substr($id_number, 0, 6);
            // 出生年月（6位）
            $dateNum = substr($id_number, 6, 6);
        } else if ($length == 18) { //如果是18位身份证
            //基本格式校验
            if (!preg_match('/^\d{17}[0-9xX]$/', $id_number)) {
                return false;
            }
            // 省市县（6位）
            $areaNum = substr($id_number, 0, 6);
            $provinceCode = substr($areaNum, 0, 2);
            // 根据GB/T2260—999，省市代码11到65
            if ($provinceCode < 11 || $provinceCode > 65) {
                return false;
            }
            // 出生年月日（8位）
            $date = substr($id_number, 6, 8);
            if (strlen($date) == 6) { //15位身份证号没有年份，这里拼上年份
                $date = '19' . $date;
            }
            $year = intval(substr($date, 0, 4));
            $month = intval(substr($date, 4, 2));
            $day = intval(substr($date, 6, 2));

            //日期基本格式校验
            if (!checkdate($month, $day, $year)) {
                return false;
            }

            //日期格式正确，但是逻辑存在问题(如:年份大于当前年)
            $currYear = date('Y');
            if ($year > $currYear) {
                return false;
            }
            //验证最后一位
            $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
            $tokens = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

            $checkSum = 0;
            for ($i = 0; $i < 17; $i++) {
                $checkSum += substr($id_number, $i, 1) * $factor[$i];
            }

            $mod = $checkSum % 11;
            $token = $tokens[$mod];

            $lastChar = strtoupper(substr($id_number, 17, 1));
            if ($lastChar != $token) {
                return false;
            }
        } else { //假身份证
            return false;
        }


        return true;
    }

    /**检测是否是姓名
     * @param $string
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:27
     */
    public static function isName($string):bool
    {
        return preg_match('/^[\x{4e00}-\x{9fa5}]+[·•]?[\x{4e00}-\x{9fa5}]+$|^[a-zA-Z\s]*[a-zA-Z\s]{2,20}$/isu', $string);
    }

    /**检测是否是金额
     * @param  $money
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:27
     */
    public static function isMoney($money):bool
    {
        if (bccomp(floatval($money), 0, 2) < 1) return false;
        return preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money);
    }

    /**
     *  判断是否是有效的银行卡
     * @param $card
     * @return bool
     */
    public static function isBankCard($card):bool
    {
        // step1 判断是否16到19位
        $pattern = '/^\d{11,24}$/';
        if (!preg_match($pattern, $card)) {
            return false;
        }
        $flag = false;
        $needles = ['62', '638888', '685800'];
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && substr($card, 0, strlen($needle)) === (string)$needle) {
                $flag = true;
            }
        }
        //如果是标准银联卡，检测卡号是否正确
        if ($flag) {
            // step2 LUHN 算法校验
            $len = strlen($card);

            $sum = 0;
            for ($i = 0; $i < $len; $i++) {
                if (($i + $len) & 1) { // 奇数
                    $sum += ord($card[$i]) - ord('0');
                } else { // 偶数
                    $tmp = (ord($card[$i]) - ord('0')) * 2;
                    $sum += floor($tmp / 10) + $tmp % 10;
                }
            }
            return $sum % 10 === 0;
        }

        return true;
    }

    /**判断是否为纯中文
     * @param string $string
     * @return bool
     */
    public static function isAllChineseLanguage(string $string): bool
    {
        if (preg_match('/^[\x7f-\xff]+$/', $string)) {
            return true;
        }
        return false;
    }

    /**
     *  验证一般字符串（仅支持数字和字母）
     * @param $string
     * @return bool
     */
    public static function isNormalString($string): bool
    {
        return !preg_match("/^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i", $string);
    }
}
