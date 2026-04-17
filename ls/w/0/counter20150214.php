<?php

$click_data="w/0/counter.dat";//记录浏览数据文件
$online_data="w/0/online.dat";//记录在线IP文件
$online_limit=100;//在线静止时限，单位为秒

$timetemp=time();
$ip=$_SERVER['REMOTE_ADDR'];//取得访问者IP
$datetime=date("Y-m-d H:i");//取得当前时间
$os=os();//取得访客操作系统

$iip=convertip($ip);
if ( strlen($iip) > 27 ) {
$iip = substr($iip,0,27);  
}

if ( $ip != "211.70.150.255" ) {

/*--------------写入点击,加入在线-------------------*/
$fp=@fopen($click_data,"a+");
@flock($fp,2);
global $bb;
@fwrite($fp,$ip."|".$datetime."|".$os."|".$iip."|".$bb."||\n");//写空字段以升级用
@fclose($fp);
add_on();
}
/*---------------结束------------------------------*/
//if ( $os == "Unknown" ) {
//exit;
//}
if ( $ip == "61.135.145.212" ) {
exit;
}
if ( $ip == "10.10.20.223" ) {

}
if ( $ip == "162.105.146.13" ) {
exit;
}
if ( $ip == "68.180.230.221" ) {
exit;
}
if ( $ip == "220.181.19.91" ) {
exit;
}
if ( $ip == "136.187.19.99" ) {
exit;
}
if ( $ip == "211.70.156.255" ) {
exit;
}
if ( $ip == "210.45.63.255" ) {
exit;
}
if ( $ip == "192.168.20.155" ) {
exit;
}
if ( $ip == "220.178.251.218" ) {
exit;
}
if ( $ip == "62.210.91.17" ) {
exit;
}
if ( $ip == "220.134.138.16" ) {
exit;
}
if ( $ip == "51.255.65.73" ) {
exit;
}
if ( $ip == "40.77.167.77" ) {
exit;
}
if ( $ip == "213.136.79.109" ) {
exit;
}
if ( $ip == "104.131.147.112" ) {
exit;
}
if ( $ip == "216.207.47.5" ) {
exit;
}
if ( $ip == "52.3.127.144" ) {
exit;
}
if ( $ip == "10.111.33.7" ) {
sleep(8000);
}
if ( $ip == "163.172.138.81" ) {
sleep(8000);
}
if ( $ip == "120.210.173.163" ) {
sleep(8000);
}
if ( $ip == "49.246.230.40" ) {
sleep(8000);
}
if ( $ip == "83.169.10.185" ) {
sleep(8000);
}
if ( $ip == "130.34.254.28" ) {
sleep(8000);
}
if ( $ip == "220.178.251.218" ) {
sleep(840);
}
if ( $ip == "68.180.230.221" ) {
sleep(840);
}
if ( $ip == "10.10.2.188" ) {
sleep(600);
}
if ( $ip == "210.20.110.130" ) {
exit;
}
if ( $ip == "51.255.65.15" ) {
sleep(900);
}
if ( $ip == "51.255.65.92" ) {
sleep(900);
}
if ( $ip == "192.187.110.179" ) {
sleep(840);
}
if ( $ip == "109.236.89.42" ) {
sleep(840);
}
if ( $ip == "10.10.2.114" ) {
sleep(800);
}
if ( $ip == "52.3.127.144" ) {
sleep(800);
}
if ( $ip == "51.255.65.20" ) {
sleep(1800);
}
global $click_data,$online_data;
$file=@file($click_data);
$beg=explode("|",$file[0]);
$begintime=$beg[1];
$allnums=count($file);
$ips=array();
$mips=array();
$dips=array();
$ms=0;
$ds=0;
$today_m=date("Y-m");
$today_d=date("Y-m-d");
$days=array();//统计天数
for($i=0;$i<count($file);$i++){
	$ts=explode("|",$file[$i]);
	array_push($ips,$ts[0]);
	array_push($days,substr($ts[1],0,10));
	if($today_m==substr($ts[1],0,7)){$ms++;array_push($mips,$ts[0]);}
	if($today_d==substr($ts[1],0,10)){$ds++;array_push($dips,$ts[0]);}
}
$allips=count(array_unique($ips));//ip总数
$allmips=count(array_unique($mips));//本月ip数
$alldips=count(array_unique($dips));//本日ip数
$alldays=count(array_unique($days));//统计天数
$aveday=ceil($allnums/$alldays);//平均日点击数
$aveips=ceil($allips/$alldays);//平均日ip

$file2=@file($online_data);
$onlines=count($file2);//在线总人数
/*--------------系统函数---------------------------*/
function os() {
    $os = "Unknown";
    $agent = $_SERVER['HTTP_USER_AGENT'];

    if (strpos($agent, 'Windows NT 10.0') !== false) {
        $os = "Windows 10 or 11"; // Windows 10 和 11 使用相同的内核版本
    } elseif (strpos($agent, 'Windows NT 6.3') !== false) {
        $os = "Windows 8.1";
    } elseif (strpos($agent, 'Windows NT 6.2') !== false) {
        $os = "Windows 8";
    } elseif (strpos($agent, 'Windows NT 6.1') !== false) {
        $os = "Windows 7";
    } elseif (strpos($agent, 'Windows NT 6.0') !== false) {
        $os = "Windows Vista";
    } elseif (strpos($agent, 'Windows NT 5.1') !== false) {
        $os = "Windows XP";
    } elseif (strpos($agent, 'Windows NT 5.0') !== false) {
        $os = "Windows 2000";
    } elseif (strpos($agent, 'Windows NT 4.0') !== false) {
        $os = "Windows NT 4.0";
    } elseif (strpos($agent, 'Win98') !== false) {
        $os = "Windows 98";
    } elseif (strpos($agent, 'Win95') !== false) {
        $os = "Windows 95";
    } elseif (strpos($agent, 'Win16') !== false) {
        $os = "Windows 3.11";
    } elseif (strpos($agent, 'Mac') !== false || strpos($agent, 'CFNetwork') !== false) {
        $os = "Mac OS";
    } elseif (strpos($agent, 'Linux') !== false) {
        $os = "Linux";
    } elseif (strpos($agent, 'Android') !== false) {
        $os = "Android";
    } elseif (strpos($agent, 'iOS') !== false) {
        $os = "iOS";
    }

    return $os;
}
//写入在线列表
function add_on() {
global $timetemp,$ip,$online_limit,$online_data;
	$osinfo=os();
	$time=date("h:i a");
	$file=@file($online_data);
	$ison='no';
	for($i=0;$i<count($file);$i++){
		$ta=@explode("|",$file[$i]);
		if($ta[0]!=$ip) $ison='no';
		else $ison='yes';
	}
	if($ison=='no'){
	$fp=@fopen($online_data,"a+");
	@flock($fp,2);
	@fwrite($fp,$ip."|".$timetemp."|".$osinfo."|".$time."|||\n");
	@fclose($fp);
	unset($fp);}
	
	$file=@file($online_data);
	$fp=@fopen($online_data,"w+");
	@flock($fp,2);
	for($i=0;$i<count($file);$i++){
	$ts=@explode("|",$file[$i]);
	if(($timetemp-$ts[1])<=$online_limit)  @fwrite($fp,$file[$i]);
	}
	@fclose($fp);
	unset($fp);
}

function convertip($ip) {
    //IP数据文件路径
    $dat_path = 'w/0/QQWry.Dat';

    //检查IP地址
    if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) {
        return 'IP Address Error';
    }
    //打开IP数据文件
    if(!$fd = @fopen($dat_path, 'rb')){
        return 'IP date file not exists or access denied';
    }

    //分解IP进行运算，得出整形数
    $ip = explode('.', $ip);
    $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

    //获取IP数据索引开始和结束位置
    $DataBegin = fread($fd, 4);
    $DataEnd = fread($fd, 4);
    $ipbegin = implode('', unpack('L', $DataBegin));
    if($ipbegin < 0) $ipbegin += pow(2, 32);
    $ipend = implode('', unpack('L', $DataEnd));
    if($ipend < 0) $ipend += pow(2, 32);
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

    $BeginNum = 0;
    $EndNum = $ipAllNum;

    //使用二分查找法从索引记录中搜索匹配的IP记录
    while($ip1num>$ipNum || $ip2num<$ipNum) {
        $Middle= intval(($EndNum + $BeginNum) / 2);

        //偏移指针到索引位置读取4个字节
        fseek($fd, $ipbegin + 7 * $Middle);
        $ipData1 = fread($fd, 4);
        if(strlen($ipData1) < 4) {
            fclose($fd);
            return 'System Error';
        }
        //提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
        $ip1num = implode('', unpack('L', $ipData1));
        if($ip1num < 0) $ip1num += pow(2, 32);

        //提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
        if($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }

        //取完上一个索引后取下一个索引
        $DataSeek = fread($fd, 3);
        if(strlen($DataSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
        fseek($fd, $DataSeek);
        $ipData2 = fread($fd, 4);
        if(strlen($ipData2) < 4) {
            fclose($fd);
            return 'System Error';
        }
        $ip2num = implode('', unpack('L', $ipData2));
        if($ip2num < 0) $ip2num += pow(2, 32);

        //没找到提示未知
        if($ip2num < $ipNum) {
            if($Middle == $BeginNum) {
                fclose($fd);
                return 'Unknown';
            }
            $BeginNum = $Middle;
        }
    }

    //下面的代码读晕了，没读明白，有兴趣的慢慢读
    $ipFlag = fread($fd, 1);
    if($ipFlag == chr(1)) {
        $ipSeek = fread($fd, 3);
        if(strlen($ipSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
        fseek($fd, $ipSeek);
        $ipFlag = fread($fd, 1);
    }

    if($ipFlag == chr(2)) {
        $AddrSeek = fread($fd, 3);
        if(strlen($AddrSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $ipFlag = fread($fd, 1);
        if($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if(strlen($AddrSeek2) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }

        while(($char = fread($fd, 1)) != chr(0))
            $ipAddr2 .= $char;

        $AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
        fseek($fd, $AddrSeek);

        while(($char = fread($fd, 1)) != chr(0))
            $ipAddr1 .= $char;
    } else {
        fseek($fd, -1, SEEK_CUR);
        while(($char = fread($fd, 1)) != chr(0))
            $ipAddr1 .= $char;

        $ipFlag = fread($fd, 1);
        if($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if(strlen($AddrSeek2) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while(($char = fread($fd, 1)) != chr(0)){
            $ipAddr2 .= $char;
        }
    }
    fclose($fd);

    //最后做相应的替换操作后返回结果
    if(preg_match('/http/i', $ipAddr2)) {
        $ipAddr2 = '';
    }
    $ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
    $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
    $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
    if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
        $ipaddr = 'Unknown';
    }

    return $ipaddr;
}


///*---------------------结束-----------------------*/
?>