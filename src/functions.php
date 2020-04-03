<?php

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Redis\Redis;

/**
 * 容器实例
 */
if (!function_exists('container')) {
    function container($key = null)
    {
        if (is_null($key)) {
            return ApplicationContext::getContainer();
        } else {
            return ApplicationContext::getContainer()->get($key);
        }
    }
}

if (!function_exists('redis')) {
    /**
     * 获取redis客户端实例
     * @return Redis|mixed
     */
    function redis()
    {
        return container(Redis::class);
    }
}

/**
 * token
 */
if (!function_exists('token')) {
    function token()
    {
        $token = request()->getHeader('Authorization')[0] ?? '';
        if (strlen($token) > 0) {
            $token = ucfirst($token);
            $arr = explode('Bearer ', $token);
            $token = $arr[1] ?? '';
            if (strlen($token) > 0) {
                return $token;
            }
        }
        return false;
    }
}

if (!function_exists('request')) {
    /**
     * 请求实例
     * @param array|string|null $key
     * @param mixed $default
     * @return RequestInterface|string|array|mixed
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return container(RequestInterface::class);
        }

        if (is_array($key)) {
            return container(RequestInterface::class)->inputs($key);
        }

        $value = container(RequestInterface::class)->input($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('response')) {
    /**
     * 响应实例
     * @return mixed|ResponseInterface
     */
    function response()
    {
        return container(ResponseInterface::class);
    }
}

if (!function_exists('success')) {
    /**
     * 成功响应实例
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return mixed
     */
    function success($msg = '', $data = null, $code = 200)
    {
        return response()->json([
            'msg' => $msg,
            'data' => $data,
            'code' => $code
        ]);
    }
}

if (!function_exists('fail')) {
    /**
     * 失败响应实例
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return mixed
     */
    function fail($msg = '', $data = null, $code = 400)
    {
        return response()->json([
            'msg' => $msg,
            'data' => $data,
            'code' => $code
        ]);
    }
}

if (!function_exists('decimal_to_abc')) {
    /**
     * 数字转换对应26个字母
     * @param $num
     * @return string
     */
    function decimal_to_abc($num)
    {
        $str = "";
        $ten = $num;
        if ($ten == 0) return "A";
        while ($ten != 0) {
            $x = $ten % 26;
            $str .= chr(65 + $x);
            $ten = intval($ten / 26);
        }
        return strrev($str);
    }
}

if (!function_exists('diff_between_two_days')) {
    /**
     * 计算两个日期之间相差的天数
     * @param $day1
     * @param $day2
     * @return float|int
     */
    function diff_between_two_days($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
        return round((abs($second1 - $second2) / 86400), 0);
    }
}

if (!function_exists('decimals_to_percentage')) {
    /**
     * 将小数转换百分数
     * @param float $decimals 小数
     * @param int $num 保留小数位
     * @return string
     */
    function decimals_to_percentage($decimals, $num = 2)
    {
        return sprintf("%01." . $num . "f", $decimals * 100) . '%';
    }
}

if (!function_exists('calculate_grade')) {
    /**
     *
     * 计算一个数的区间范围等级
     * @param array $range 区间范围（从大到小排列）
     * @param $num
     * @return mixed|void
     */
    function calculate_grade($range, $num)
    {
        $max = max($range);
        if ($num >= $max) {
            return count($range);
        }
        foreach ($range as $k => $v) {
            if ($num < $v) {
                return $k;
            }
        }
    }
}

if (!function_exists('convertAmountToCn')) {
    /**
     * 2  * 将数值金额转换为中文大写金额
     * 3  * @param $amount float 金额(支持到分)
     * 4  * @param $type   int   补整类型,0:到角补整;1:到元补整
     * 5  * @return mixed 中文大写金额
     * 6  */
    function convertAmountToCn($amount, $type = 1)
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
            if ($integerArr[$i] != 0) {
                $result = $result . $digital[$integerArr[$i]] . $position[$positionLength - $integerArrLength + $i];
            } else {
                // 如果数值为0, 且单位是亿,万,元这三个的时候,则直接显示单位
                if (($positionLength - $integerArrLength + $i + 1) % 4 == 0) {
                    $result = $result . $position[$positionLength - $integerArrLength + $i];
                }
            }
        }

        // 如果小数位也要转换
        if ($type == 0) {
            // 将小数位的数值字符串拆分成数组
            $decimalArr = str_split($amountArr[1], 1);
            // 将角替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[0] != 0) {
                $result = $result . $digital[$decimalArr[0]] . '角';
            }
            // 将分替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[1] != 0) {
                $result = $result . $digital[$decimalArr[1]] . '分';
            }
        } else {
            $result = $result . '整';
        }
        return $result;
    }
}

if (!function_exists('today')) {
    /**
     * Create a new Carbon instance for the current time.
     * @return false|string
     */
    function today()
    {
        return date('Y-m-d', time());
    }
}

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     * @return false|string
     */
    function now()
    {
        return date('Y-m-d h:i:s', time());
    }
}

if (!function_exists('get_tree_id')) {
    /**
     * @param $array
     * @param int $pid
     * @param int $level
     * @return array
     */
    function get_tree_id($array, $pid = 0, $level = 0)
    {
        static $list = [];
        foreach ($array as $key => $value) {
            if ($value['pid'] == $pid || $value['id'] == $pid) {
                $value['level'] = $level;
                $list[] = $value['id'];
                unset($array[$key]);
                get_tree_id($array, $value['id'], $level + 1);
            }
        }
        return $list;
    }
}


