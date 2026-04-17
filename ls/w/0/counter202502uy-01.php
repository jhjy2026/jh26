<?php
$click_data_url = "http://www.wmdjy.top/wm/w/0/counter.dat"; // 记录浏览数据文件
$online_data_url = "http://www.wmdjy.top/wm/w/0/online.dat"; // 记录在线IP文件
$dat_path_url = 'http://www.wmdjy.top/wm/w/0/QQWry.Dat';     // 数据文件路径
$online_limit = 100; // 在线静止时限，单位为秒

$timetemp = time();
$ip = $_SERVER['REMOTE_ADDR']; // 取得访问者IP
$datetime = date("Y-m-d H:i"); // 取得当前时间
$os = os(); // 取得访客操作系统

$iip = convertip($ip);
if (strlen($iip) > 27) {
    $iip = substr($iip, 0, 27);
}
/*--------------写入点击,加入在线-------------------*/
$click_content = file_get_contents($click_data_url);
if ($click_content === FALSE) {
    die('无法读取点击数据文件。');
}

$click_content .= $ip . "|" . $datetime . "|" . $os . "|" . $iip . "||\n"; // 写空字段以升级用

if (file_put_contents($click_data_url, $click_content) === FALSE) {
    die('无法更新点击数据文件。');
}

add_on();
/*---------------结束------------------------------*/
//if ( $os == "Unknown" ) {
//exit;
//}
if ($ip == "61.135.145.212") {
    exit;
}
/*--------------系统函数---------------------------*/
function os() {
    $os = "Unknown";
    $agent = $_SERVER['HTTP_USER_AGENT'];

    if (strpos($agent, 'Windows NT 10.0') !== false) {
        $os = "Windows 10";
    } elseif (strpos($agent, 'Windows NT 6.1') !== false) {
        $os = "Windows 7";
    } elseif (strpos($agent, 'Mac OS X') !== false) {
        $os = "Mac OS X";
    } elseif (strpos($agent, 'Linux') !== false) {
        $os = "Linux";
    } elseif (strpos($agent, 'iOS') !== false) {
        $os = "iOS";
    }
    return $os;
}
function convertip($ip) {
    global $dat_path_url;

    // 检查IP地址
    if (!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) {
        return 'IP Address Error';
    }

    // 获取IP数据文件内容
    $dat_content = file_get_contents($dat_path_url);
    if ($dat_content === FALSE) {
        return 'IP date file not exists or access denied';
    }

    // 分解IP进行运算，得出整形数
    $ip = explode('.', $ip);
    $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

    // 获取IP数据索引开始和结束位置
    $DataBegin = substr($dat_content, 0, 4);
    $DataEnd = substr($dat_content, 4, 4);
    $ipbegin = unpack('L', $DataBegin)[1];
    if ($ipbegin < 0) $ipbegin += pow(2, 32);
    $ipend = unpack('L', $DataEnd)[1];
    if ($ipend < 0) $ipend += pow(2, 32);
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

    $BeginNum = 0;
    $EndNum = $ipAllNum;

    // 使用二分查找法从索引记录中搜索匹配的IP记录
    while ($BeginNum < $EndNum) {
        $Middle = intval(($EndNum + $BeginNum) / 2);

        // 偏移指针到索引位置读取4个字节
        $ipData1 = substr($dat_content, $ipbegin + 7 * $Middle, 4);
        if (strlen($ipData1) < 4) {
            return 'System Error';
        }
        // 提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
        $ip1num = unpack('L', $ipData1)[1];
        if ($ip1num < 0) $ip1num += pow(2, 32);

        // 提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }

        // 取完上一个索引后取下一个索引
        $DataSeek = substr($dat_content, $ipbegin + 7 * $Middle + 4, 3);
        if (strlen($DataSeek) < 3) {
            return 'System Error';
        }
        $DataSeek = unpack('L', $DataSeek . chr(0))[1];
        $ipData2 = substr($dat_content, $DataSeek, 4);
        if (strlen($ipData2) < 4) {
            return 'System Error';
        }
        $ip2num = unpack('L', $ipData2)[1];
        if ($ip2num < 0) $ip2num += pow(2, 32);

        // 没找到提示未知
        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                return 'Unknown';
            }
            $BeginNum = $Middle;
        } else {
            // 找到匹配的IP记录
            $DataSeek = $ip1num & 0x00FFFFFF;
            $returnStr = substr($dat_content, $DataSeek, 30);
            return $returnStr;
        }
    }

    return 'Unknown';
}
?>