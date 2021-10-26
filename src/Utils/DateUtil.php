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
    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**统一addtime字段的格式
     * @author: hbh
     * @Time: 2020/4/10   14:39
     */
    public static function addtime(): string
    {
        return time();
    }

    /**统一返回时间格式化
     * @param $time_temp
     * @return string
     * @author: hbh
     * @Time: 2020/6/3   15:17
     */
    public static function callbackTime(int $time_temp): string
    {
        return date('Y-m-d H:i:s', $time_temp ?: time());
    }

    /**获取N个月之前或者之后的日期(php语言的坑)
     * @param int $num 1,往前1个月  -1往后一个月
     * @param int $timestamp 需要转换的时间戳
     * @return int
     * @author: hbh
     * @Time: 2020/4/10   11:59
     */
    public static function geTodayAgoMonth(int $num,int $timestamp):int
    {
        $now = $timestamp ?: time();
        $now_day = date('d', $now);
        if ($num === 0) return $now;
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
        $first_day = $year . '-' . $month . '-01';
        $temp_day_num = date('t', strtotime($first_day));
        if ($temp_day_num < $now_day) {          //date('t')，获取当月的总天数
            $result = $year . '-' . $month . '-' . $temp_day_num;
        } else {
            $result = $year . '-' . $month . '-' . $now_day;
        }
        return strtotime($result . ' ' . $arr['hours'] . ':' . $arr['minutes'] . ':' . $arr['seconds']);
    }
}
