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
    public static function pathInfo($filepath): array
    {
        $path_parts = array();
        $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')), "/") . "/";
        $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')), "/");
        $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
        $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')), "/");
        return $path_parts;
    }

    /**字节转换成其他单位
     * @param int $bytes
     * @param int $precision 小数点精度
     * @return string
     * @author: hbh
     * @Time: 2020/6/3   10:55
     */
    public static function bytesToOther(int $bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**其他单位 转成成字节
     * @param string $p_sFormatted
     * @return string
     * @author: hbh
     * @Time: 2020/6/3   11:01
     */
    public static function otherToByte(string $p_sFormatted): string
    {
        $aUnits = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
        $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
        if (!in_array($sUnit, array_keys($aUnits), true)) {
            return '';
        }
        $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
        if (!is_numeric($iUnits)) {
            return '';
        }
        return $iUnits * pow(1024, $aUnits[$sUnit]) . 'B';
    }


    /**创建目录
     * @param string $dir
     * @param string $mode
     * @return bool
     * @author: hbh
     * @Time: 2022/5/17 17:15
     */
    public static function createDirectory(string $dir, string $mode): bool
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return true;
        }
        if (!mkdir(dirname($dir), $mode)) {
            return false;
        }
        return @mkdir($dir, $mode);
    }
}
