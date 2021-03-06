<?php

use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

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
     * @param array $array
     * @param array $pid
     * @return array
     */
    function get_tree_id(array $array, $pids = [0])
    {
        $list = [];
        foreach ($array as $key => $value) {
            if (in_array($value['pid'], $pids) || in_array($value['id'], $pids)) {
                $list[] = $value['id'];
                unset($array[$key]);
            }
        }
        if ($list == []) return [];
        $children = get_tree_id($array, $list);
        return array_merge($list, $children);
    }
}

if (!function_exists('list_go_tree')) {
    /**
     * 列表转树状格式
     * @param array $list
     * @param string $pk
     * @param string $pid
     * @param string $children
     * @return array
     */
    function list_go_tree(array $list = [], $pk = 'id', $pid = 'parent_id', $children = 'children')
    {
        $tree = $refer = [];
        // 创建基于主键的数组引用
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId = $data[$pid];
            // 判断是否存在parent
            if (isset($refer[$parentId])) {
                $parent = &$refer[$parentId];
                $parent[$children][] = &$list[$key];
            } else {
                $tree[] = &$list[$key];
            }
        }
        return $tree;
    }
}

if (!function_exists('flushAnnotationCache')) {
    /**
     * 刷新注解缓存，清楚注解缓存
     * @param string $listener
     * @param mixed $keys
     * @return bool
     */
    function flushAnnotationCache($listener, $keys)
    {
        $keys = is_array($keys) ? $keys : [$keys];
        $dispatcher = ApplicationContext::getContainer()->get(EventDispatcherInterface::class);
        foreach ($keys as $key) {
            $dispatcher->dispatch(new DeleteListenerEvent($listener, [$key]));
        }
        return true;
    }
}

if (!function_exists('num_2_file_size')) {
    /**
     * 数字转文件大小
     * @param $num
     * @return string
     */
    function num_2_file_size($num)
    {
        $p = 0;
        $format = 'B';
        if ($num > 0 && $num < 1024) {
            return number_format($num) . ' ' . $format;
        } else if ($num >= 1024 && $num < pow(1024, 2)) {
            $p = 1;
            $format = 'KB';
        } else if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        } else if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        } else if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
            $p = 4;
            $format = 'TB';
        }
        $num /= pow(1024, $p);
        return number_format($num, 2) . ' ' . $format;
    }
}

if (!function_exists('select_id_name')) {
    function select_id_name($columns = [])
    {
        $columns = array_merge(['id', 'name'], $columns);
        return function ($q) use ($columns) {
            $q->select($columns);
        };
    }
}

if (!function_exists('get_week_start_and_end')) {
    function get_week_start_and_end($time = '', $first = 1)
    {
        //当前日期
        if (!$time) $time = time();
        $sdefaultDate = date("Y-m-d", $time);
        //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        return array("week_start" => $week_start, "week_end" => $week_end);
    }
}

if (!function_exists('empty_string_2_null')) {
    /**
     * 空字符串转NULL
     * @param array $arr
     * @return array
     */
    function empty_string_2_null(array $arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    $arr[$key] = empty_string_2_null($value);
                } else {
                    if ($value === '') {
                        $arr[$key] = null;
                    }
                }
            }
        }
        return $arr;
    }
}

