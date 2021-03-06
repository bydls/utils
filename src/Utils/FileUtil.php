<?php
/**
 * @Desc:文件
 * @author: hbh
 * @Time: 2020/4/10   19:52
 */

namespace bydls\Utils;


trait FileUtil
{
    /**解决pathinfo()中文乱码 (php语言的坑)
     * @param $filepath
     * @return array
     * @author: hbh
     * @Time: 2020/4/9   16:35
     */
    public static function path_info($filepath)
    {
        $path_parts = array();
        $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')), "/") . "/";
        $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')), "/");
        $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
        $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')), "/");
        return $path_parts;
    }

    /**字节转换成其他单位
     * @param $bytes
     * @param int $precision 小数点精度
     * @return string
     * @author: hbh
     * @Time: 2020/6/3   10:55
     */
    public function bytesToOther($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**其他单位 转成成字节
     * @param $p_sFormatted
     * @return bool|float|int
     * @author: hbh
     * @Time: 2020/6/3   11:01
     */
    function otherToByte($p_sFormatted)
    {
        $aUnits = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
        $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
        if (intval($sUnit) !== 0) {
            $sUnit = 'B';
        }
        if (!in_array($sUnit, array_keys($aUnits))) {
            return false;
        }
        $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
        if (!intval($iUnits) == $iUnits) {
            return false;
        }
        return $iUnits * pow(1024, $aUnits[$sUnit]);
    }
}
