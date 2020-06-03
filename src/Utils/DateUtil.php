<?php
/**
 * @Desc:日期
 * @author: hbh
 * @Time: 2020/4/10   11:22
 */

namespace bydls\Utils;


trait DateUtil
{
    /**当前日期
     * @return false|string
     * @author: hbh
     * @Time: 2020/4/9   16:24
     */
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    /**统一addtime字段的格式
     * @author: hbh
     * @Time: 2020/4/10   14:39
     */
    public static function addtime(){
        return time();
    }

    /**统一返回时间格式化
     * @param $timetemp
     * @return false|string
     * @author: hbh
     * @Time: 2020/6/3   15:17
     */
    public static function callbackTime($timetemp){
        return  date('Y-m-d H:i:s', $timetemp?:time());
    }

    /**获取N个月之前或者之后的日期(php语言的坑)
     * @param $num
     * @param $timestamp
     * @return false|int
     * @author: hbh
     * @Time: 2020/4/10   11:59
     */
    public static function geTodayAgoMonth($num, $timestamp) //$num  1,往前1个月  -1往后一个月      $timestamp  需要转换的时间戳
    {
        $now = $timestamp ?: time();
        $now_day = date('d', $now);
        if ($num == 0) return $now;
        $arr = getdate($now);
        $temp_month = $arr['mon'] + $num;
        if ($num > 0) {             //先转换年月
            if ($temp_month > 12) {
                $year = $arr['year'] + 1;
                $month = $temp_month - 12;
            } else {
                $year = $arr['year'];
                $month = $temp_month;
            }
        } else {
            if ($temp_month < 1) {
                $year = $arr['year'] - 1;
                $month = $temp_month + 12;
            } else {
                $year = $arr['year'];
                $month = $temp_month;
            }
        }
        $firstday = $year . '-' . $month . '-01';
        $temo_day_num = date('t', strtotime($firstday));
        if ($temo_day_num < $now_day) {          //date('t')，获取当月的总天数
            $resutl = $year . '-' . $month . '-' . $temo_day_num;
        } else {
            $resutl = $year . '-' . $month . '-' . $now_day;
        }
        return strtotime($resutl . ' ' . $arr['hours'] . ':' . $arr['minutes'] . ':' . $arr['seconds']);
    }
}
