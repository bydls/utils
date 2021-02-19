<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/11   11:19
 */

namespace bydls;


class Exception extends  \Exception
{
    const UNKNOWN_ERROR = 9999;

    const INVALID_GATEWAY = 1;

    const INVALID_CONFIG = 2;

    const INVALID_ARGUMENT = 3;

    const ERROR_GATEWAY = 4;

    const INVALID_SIGN = 5;

    const RESULT_ERROR = 6;

    public $raw;

    public function __construct($message = '',$code = self::UNKNOWN_ERROR,$raw = [])
    {
        $message = '' === $message ? 'Unknown Error' : $message;
        $this->raw = is_array($raw) ? $raw : [$raw];
        parent::__construct($message, intval($code));
    }
}