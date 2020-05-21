<?php
// 应用公共文件
function get_ip()
{
    static $realip;
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}

/**
 * 根据ip地址获取城市信息
 * @Author   yangshuiping
 * @DateTime 2019-08-09T17:21:40+0800
 * @param    string                   $ip [description]
 * @return   [type]                       [description]
 */
function get_city($ip = '')
{
    if (!filter_var($ip, FILTER_SANITIZE_URL)) {
        return;
    }
    if ($ip == '') {
        $url  = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";
        $ip   = json_decode(file_get_contents($url), true);
        $data = $ip;
    } else {
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        $ip  = json_decode(file_get_contents($url));
        if ((string) $ip->code == '1') {
            return false;
        }
        $data = (array) $ip->data;
    }

    return $data;
}

/**
 * 用户名、邮箱、手机号码,银行账号中间字符串以*隐藏
 * @Author   yangshuiping
 * @DateTime 2019-08-09T17:14:55+0800
 * @param    [type]                   $str [description]
 * @return   [type]                        [description]
 */
function hide_star($str)
{
    if (strpos($str, '@')) {
        $email_array = explode("@", $str);
        $prevfix     = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
        $count       = 0;
        $str         = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
        $rs          = $prevfix . $str;
    } else {
        $pattern = '/(1[34578]{1}[0-9])[0-9]{4}([0-9]{4})/i';
        if (preg_match($pattern, $str)) {
            $rs = preg_replace($pattern, '$1****$2', $str);
        } else {
            $rs = substr($str, 0, 3) . "***" . substr($str, -1);
        }
    }
    return $rs;
}

/**
 * 模拟post请求
 * @Author   yangshuiping
 * @DateTime 2019-08-09T17:10:04+0800
 * @param    [type]                   $url  [description]
 * @param    [type]                   $data [description]
 * @return   [type]                         [description]
 */
function http_post_query($url, $data)
{
    $postdata = http_build_query($data);
    $opts     = array('http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata,
    ),
    );
    $context = stream_context_create($opts);
    $result  = file_get_contents($url, false, $context);
    return $result;
}

/**
 * 检测手机号是否合法
 * @Author   yangshuiping
 * @DateTime 2019-08-09T17:09:32+0800
 * @param    [type]                   $mobile [description]
 * @return   [type]                           [description]
 */
function check_mobile($mobile)
{
    $pattern = '/^1[3456789]\d{9}$/';
    if (preg_match($pattern, $mobile)) {
        return true;
    }
    return false;
}

/**
 * 打印链路信息
 * @Author   yangshuiping
 * @DateTime 2019-08-09T17:05:51+0800
 * @return   [type]                   [description]
 */
function debug()
{
    prs(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
}
/**
 * 打印多个数组
 * @Author   yangshuiping
 * @DateTime 2019-08-09T16:58:06+0800
 * @param    [type]                   $args [description]
 * @return   [type]                         [description]
 */
function prs(...$args)
{
    echo '<pre>';
    foreach ($args as $x) {
        print_r($x);
        echo PHP_EOL;
    }
    echo '</pre>';
}

/**
 * 打印多个数组
 * @Author   yangshuiping
 * @DateTime 2019-08-09T16:58:06+0800
 * @param    [type]                   $args [description]
 * @return   [type]                         [description]
 */
function dumps(...$args)
{
    echo '<pre>';
    foreach ($args as $x) {
        var_dump($x);
        echo PHP_EOL;
    }
    echo '</pre>';
}

/**
 * 获取用户浏览器
 * @Author   yangshuiping
 * @DateTime 2019-08-09T16:49:40+0800
 * @return   [type]                   [description]
 */
function get_user_browser()
{
    $agent       = mb_strtolower($_SERVER["HTTP_USER_AGENT"]);
    $browser     = '未知操作系统';
    $browser_ver = '';

    if (preg_match('/omniweb\/(v*)([^\s|;]+)/', $agent, $regs)) {
        $browser     = 'omniweb';
        $browser_ver = $regs[2];
    }

    if (preg_match('/netscape([\d]*)\/([^\s]+)/', $agent, $regs)) {
        $browser     = 'netscape';
        $browser_ver = $regs[2];
    }

    if (preg_match('/safari\/([^\s]+)/', $agent, $regs)) {
        $browser     = 'safari';
        $browser_ver = $regs[1];
    }

    if (preg_match('/msie\s([^\s|;]+)/', $agent, $regs)) {
        $browser     = 'internet explorer';
        $browser_ver = $regs[1];
    }

    if (preg_match('/rv:([^\s|)]+)/', $agent, $regs)) {
        $browser     = 'internet explorer';
        $browser_ver = $regs[1];
    }

    if (preg_match('/opera[\s|\/]([^\s]+)/', $agent, $regs)) {
        $browser     = 'opera';
        $browser_ver = $regs[1];
    }

    if (preg_match('/netcaptor\s([^\s|;]+)/', $agent, $regs)) {
        $browser     = '(internet explorer ' . $browser_ver . ') netcaptor';
        $browser_ver = $regs[1];
    }

    if (preg_match('/maxthon/', $agent, $regs)) {
        $browser     = '(internet explorer ' . $browser_ver . ') maxthon';
        $browser_ver = '';
    }
    if (preg_match('/360se/', $agent, $regs)) {
        $browser     = '(internet explorer ' . $browser_ver . ') 360se';
        $browser_ver = '';
    }
    if (preg_match('/se 2\.x/', $agent, $regs)) {
        $browser     = '(internet explorer ' . $browser_ver . ') 搜狗';
        $browser_ver = '';
    }

    if (preg_match('/firefox\/([^\s]+)/', $agent, $regs)) {
        $browser     = 'firefox';
        $browser_ver = $regs[1];
    }

    if (preg_match('/lynx\/([^\s]+)/', $agent, $regs)) {
        $browser     = 'lynx';
        $browser_ver = $regs[1];
    }

    if (preg_match('/chrome\/([^\s]+)/', $agent, $regs)) {
        $browser     = 'chrome';
        $browser_ver = $regs[1];

    }

    if ($browser != '') {
        return $browser . ' ' . $browser_ver;
    }
}

/**
 * 获取用户操作系统
 * @Author   yangshuiping
 * @DateTime 2019-08-09T16:49:49+0800
 * @return   [type]                   [description]
 */
function get_user_os()
{
    $agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    $os    = '未知操作系统';

    if (preg_match('/win/', $agent) && preg_match('/nt 6\.0/', $agent)) {
        $os = 'windows vista';
    } else if (preg_match('/win/', $agent) && preg_match('/nt 6\.1/', $agent)) {
        $os = 'windows 7';
    } else if (preg_match('/win/', $agent) && preg_match('/nt 6\.2/', $agent)) {
        $os = 'windows 8';
    } else if (preg_match('/win/', $agent) && preg_match('/nt 10\.0/', $agent)) {
        $os = 'windows 10'; #添加win10判断
    } else if (preg_match('/win/', $agent) && preg_match('/nt 5\.1/', $agent)) {
        $os = 'windows xp';
    } else if (preg_match('/win/', $agent) && preg_match('/nt 5/', $agent)) {
        $os = 'windows 2000';
    } else if (preg_match('/win/', $agent) && preg_match('/nt/', $agent)) {
        $os = 'windows nt';
    } else if (preg_match('/win/', $agent) && preg_match('/32/', $agent)) {
        $os = 'windows 32';
    } else if (preg_match('/linux/', $agent)) {
        $os = 'linux';
    } else if (preg_match('/unix/', $agent)) {
        $os = 'unix';
    } else if (preg_match('/sun/', $agent) && preg_match('/os/', $agent)) {
        $os = 'sunos';
    } else if (preg_match('/ibm/', $agent) && preg_match('/os/', $agent)) {
        $os = 'ibm os/2';
    } else if (preg_match('/mac/', $agent) && preg_match('/pc/', $agent)) {
        $os = 'macintosh';
    } else if (preg_match('/powerpc/', $agent)) {
        $os = 'powerpc';
    } else if (preg_match('/aix/', $agent)) {
        $os = 'aix';
    } else if (preg_match('/hpux/', $agent)) {
        $os = 'hpux';
    } else if (preg_match('/netbsd/', $agent)) {
        $os = 'netbsd';
    } else if (preg_match('/bsd/', $agent)) {
        $os = 'bsd';
    } else if (preg_match('/osf1/', $agent)) {
        $os = 'osf1';
    } else if (preg_match('/irix/', $agent)) {
        $os = 'irix';
    } else if (preg_match('/freebsd/', $agent)) {
        $os = 'freebsd';
    } else if (preg_match('/teleport/', $agent)) {
        $os = 'teleport';
    } else if (preg_match('/flashget/', $agent)) {
        $os = 'flashget';
    } else if (preg_match('/webzip/', $agent)) {
        $os = 'webzip';
    } else if (preg_match('/offline/', $agent)) {
        $os = 'offline';
    }
    return $os;
}

/**
 * httpPost请求
 * @param url 请求地址
 * @param data 请求参数（json）
 */
function post_json_response($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($data),
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $response;
}

/**
 * 获取数组中一批keys的值
 * @Author   yangshuiping
 * @DateTime 2019-07-26T11:10:13+0800
 * @param    Array                    $array [description]
 * @param    Array                    $keys  [description]
 * @return   [type]                          [description]
 */
function array_only(array $array, array $keys)
{
    foreach ($keys as $v_key) {
        $need[$v_key] = isset($array[$v_key]) ? $array[$v_key] : '';
    }
    return $need;
}

/**
 * 接口统一输出json格式
 * @Author   yangshuiping
 * @DateTime 2019-07-02T16:05:17+0800
 * @param    string                   $data    [数据]
 * @param    integer                  $status    [返回码]
 * @param    string                   $message [提示消息]
 * @return   [type]                            [json格式]
 */
function response_json($data = '', $status = 200, $message = 'successful')
{
    header('Content-Type:application/json; charset=utf-8');
    return json_encode(result_info($data, $status, $message),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * 检测字符串是否是json
 * @Author   yangshuiping
 * @DateTime 2019-08-26T20:55:53+0800
 * @param    [type]                   $json_str [description]
 * @return   boolean                            [description]
 */
function is_json($json_str)
{
    $res   = json_decode($value, true);
    $error = json_last_error();
    return $error ? false : true;
}

/**
 * 去除字符串中重复的字符,例如名字去重
 * @Author   yangshuiping
 * @DateTime 2019-09-10T14:38:47+0800
 * @param    string                   $string    [要去重的字符串]
 * @param    string                   $separator [分隔符]
 * @return   [type]                              [description]
 */
function unique_string($string = '', $separator = ',')
{
    return join($separator, array_unique((explode($separator, $string))));
}

function result_info($data = '', $status = 200, $message = 'successful')
{
    return [
        'status'  => in_array($status, [200, 201, 401]) ? $status : 201,
        'message' => is_array($message) ? join(' ', $message) : $message,
        'data'    => $data,
    ];
}

/**
 * 给多维数组降维
 * @Author   yangshuiping
 * @DateTime 2019-11-28T20:51:30+0800
 * @param    [type]                   $array [description]
 * @return   [type]                          [description]
 */
function reduce_array($array)
{
    $return = [];
    array_walk_recursive($array, function ($x) use (&$return) {
        $return[] = $x;
    });
    return $return;
}

/**
 * 批量删除一些不需要的keys
 * @Author   yangshuiping
 * @DateTime 2019-11-08T10:52:04+0800
 * @param    [type]                   $array    [description]
 * @param    [type]                   $del_keys [description]
 * @return   [type]                             [description]
 */
function del_array_values($array, $del_keys)
{
    if (empty($array) || empty($del_keys)) {
        return;
    }
    foreach ($del_keys as $key) {
        if (isset($array[$key])) {
            unset($array[$key]);
        }
    }
    return $array;
}

/**
 * 判断数组维度
 * @Author   yangshuiping
 * @DateTime 2019-11-28T21:00:17+0800
 * @param    [type]                   $array [description]
 * @return   [type]                          [description]
 */
function number_of_dimensions(array $array)
{
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
    $d  = 0;
    foreach ($it as $v) {
        $it->getDepth() >= $d and $d = $it->getDepth();
    }
    return ++$d;
}

/**
 * 清洗数据，替换制表符换行符等
 * @Author   yangshuiping
 * @DateTime 2020-03-26T18:18:49+0800
 * @param    [type]                   &$str [description]
 * @return   [type]                         [description]
 */
function clean_data(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

/**
 * 快速导出excel文件
 * @Author   yangshuiping
 * @DateTime 2020-03-26T18:16:03+0800
 * @param    array                    $data     [数据]
 * @param    string                   $filename [导出的文件名，不指定的话就是当前日期时分秒.xls]
 * @param    array                    $title    [excel表头]
 * @return   [type]                             [description]
 */
function download_excel(array $data, string $filename = '', array $title = [])
{
    // 下载的文件名xls或者xlsx都可以
    $filename = $filename ?: "website_data_" . date('Ymd-His') . ".xls";
    // 指定输出类型
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");
    $title = $title ?: array_keys($data[0]);
    echo implode("\t", $title) . "\n";
    foreach ($data as $row) {
        array_walk($row, 'clean_data');
        echo implode("\t", array_values($row)) . "\n";
    }
    exit;
}

/**
 * 从多维数组中取出指定的key值
 * @Author   yangshuiping
 * @DateTime 2019-12-06T10:45:07+0800
 * @param    array                    $array [description]
 * @param    array                    $keys  [description]
 * @return   [type]                          [description]
 */
function get_values_from_multiarray(array $array, array $keys)
{
    $return = [];
    array_walk_recursive($array, function ($x, $key) use (&$return, $keys) {
        if (in_array($key, $keys)) {
            $return[$key][] = $x;
        }
    });
    if (!$return) {
        return;
    }
    // 注意array_filter会把==false的值过滤掉 by yangshuiping 2019-12-06
    return count($keys) == 1 ? array_filter($return[reset($keys)]) : $return;
}

/*
 * 给array_filter回调
 * @Author   yangshuiping
 * @DateTime 2019-12-16T14:40:52+0800
 * @param    [type]                   $value [description]
 * @return   [type]                          [description]
 */
function filter_null_string($value)
{
    // 使用严格比较 by yangshuiping 2019-12-16
    return in_array($value, [' ', '', null], true) ? false : true;
}

/**
 * 检查邮箱
 * @Author   yangshuiping
 * @DateTime 2019-12-23T15:20:59+0800
 * @param    [type]                   $email [description]
 * @return   [type]                          [description]
 */
function check_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $str = explode('@', $email);
    return checkdnsrr(idn_to_ascii($str[1]), 'MX');
}

/**
 * 将各种连接符替换成英文逗号
 * @Author   yangshuiping
 * @DateTime 2019-12-31T14:54:07+0800
 * @param    string                   $string [description]
 * @return   [type]                           [description]
 */
function replace_dots(string $string = '')
{
    if (empty($string)) {
        return;
    }
    return str_replace([';', '，', '；', '.'], ',', $string);
}

/**
 * 校验密码强度
 *   // use just cracklib-check, return FALSE or message
$retval = Tools::password_is_vulnerable($pw);
// as above, but also use pwscore to measure password strength
$retval = Tools::password_is_vulnerable($pw, TRUE);
 * @Author   yangshuiping
 * @DateTime 2020-03-26T19:14:10+0800
 * @param    [type]                   $pw    [description]
 * @param    boolean                  $score [description]
 * @return   [type]                          [description]
 */
function password_is_vulnerable($pw, $score = false)
{
    $CRACKLIB = "/path/to/cracklib-check";
    $PWSCORE  = "/path/to/pwscore";

    // prevent UTF-8 characters being stripped by escapeshellarg
    setlocale(LC_ALL, 'en_US.utf-8');

    $out     = [];
    $ret     = null;
    $command = "echo " . escapeshellarg($pw) . " | {$CRACKLIB}";
    exec($command, $out, $ret);
    if (($ret == 0) && preg_match("/: ([^:]+)$/", $out[0], $regs)) {
        list(, $msg) = $regs;
        switch ($msg) {
            case "OK":
                if ($score) {
                    $command = "echo " . escapeshellarg($pw) . " | {$PWSCORE}";
                    exec($command, $out, $ret);
                    if (($ret == 0) && is_numeric($out[1])) {
                        return (int) $out[1]; // return score
                    } else {
                        return false; // probably OK, but may be too short, or a palindrome
                    }
                } else {
                    return false; // OK
                }
                break;

            default:
                $msg = str_replace("dictionary word", "common word, name or pattern", $msg);
                return $msg; // not OK - return cracklib message
        }
    }

    return false; // possibly OK
}

function wantJson()
{
    $header = join(array_only($_SERVER, ['HTTP_CONTENT_TYPE', 'HTTP_ACCEPT', 'CONTENT_TYPE']), ',');
    return strpos($header, '/json');
}

/**
 * 快捷方法
 * @param  [type] $view_path [description]
 * @param  [type] $tpl_vars  [description]
 * @return [type]            [description]
 */
function view($view_path, $tpl_vars = [])
{
    $blade = new BladeAdapter();
    return $blade->display($view_path, $tpl_vars);
}

function check_input($value)
{
    // 去除斜杠
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // 如果不是数字则加引号
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}

function function_dump($funcname)
{
    try {
        if (is_array($funcname)) {
            $func     = new ReflectionMethod($funcname[0], $funcname[1]);
            $funcname = $funcname[1];
        } else {
            $func = new ReflectionFunction($funcname);
        }
    } catch (ReflectionException $e) {
        echo $e->getMessage();
        return;
    }
    $start    = $func->getStartLine() - 1;
    $end      = $func->getEndLine() - 1;
    $filename = $func->getFileName();
    echo "function $funcname defined by $filename($start - $end)\n";
}

function redirect(string $url, array $params = [])
{
    $url = $params ? $url . '?' . http_build_query($params) : $url;
    header('Location: ' . $url);
}
