<?php
/**
 * @Desc:请求
 * @author: hbh
 * @Time: 2020/4/10   11:25
 */

namespace bydls\Utils;


trait RequestUtil
{

    /**获取用户真是IP地址
     * @return mixed|string
     * @author: hbh
     * @Time: 2020/4/9   16:21
     */
    public static function getIPAddress()
    {
        $ipAddress = '';

        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ipAddress = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $ipAddress = $_SERVER["REMOTE_ADDR"];
            }
        }

        return $ipAddress;
    }

    /**根据当前ip获取所在地
     * @return string|mixed
     * @author: hbh
     * @Time: 2020/4/14   18:26
     */
    public static function getIpLocation()
    {
        $clientIp = self::getIPAddress();
        $url='http://ip-api.com/json/' . $clientIp . '?lang=zh-CN';
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'timeout'=>2,//单位秒
            )
        );
        $ip = file_get_contents('http://ip-api.com/json/' . $clientIp . '?lang=zh-CN',false,stream_context_create($opts));
        $ipInfo = '';
        if (!empty($ip)) {
            $ipInfo = json_decode($ip, true);
            if ($ipInfo['status'] == 'success') {
                return [
                    'ip' => $clientIp,
                    'address' => $ipInfo['country'] . ' ' . $ipInfo['regionName'] . ' ' . $ipInfo['city']
                ];
            } else {
                return [
                    'ip' => $clientIp,
                    'address' => ''
                ];
            }

        }
        return $ipInfo;
    }
    /**
     * @retu将 IPV4 的字符串互联网协议转换成长整型数字rn string
     * @author: hbh
     * @Time: 2020/4/9   16:22
     */
    public static function getLongIPAddress()
    {
        return sprintf("%u", ip2long(self::getIPAddress()));
    }
    /**获取请求来源的url
     * @return string
     * @author: hbh
     * @Time: 2020/4/9   17:26
     */
    public static function get_url(){
        return $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
    }

    /**获取请求来源url中的path部分
     * @return array|false|int|string|null
     * @author: hbh
     * @Time: 2020/4/9   17:28
     */
    public static function get_url_path(){
        $url=self::get_url();
        return trim(parse_url($url, PHP_URL_PATH));
    }

    /**CURL  POST 请求
     * @param $url
     * @param $data
     * @param int $timeout
     * @param array $headers
     * @return bool|string
     * @author: hbh
     * @Time: 2020/5/21   17:07
     */
    public static function curl_post($url, $data, $timeout = 10, $headers = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, "http://" . explode('/', $url)[2] . "/");
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($headers) {
            $headerArr = array();
            foreach ($headers as $n => $v) {
                $headerArr[] = $n . ':' . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLOPT_HEADER, 1);
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        if (stripos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }

    public static function curl_get($url,$timeout = 10,$headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($headers) {
            $headerArr = array();
            foreach ($headers as $n => $v) {
                $headerArr[] = $n . ':' . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLOPT_HEADER, 1);
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_close($ch);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}
