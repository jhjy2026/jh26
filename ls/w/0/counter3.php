<?php

$click_data="p/pb/uy/counter.dat";//记录浏览数据文件
$online_data="p/pb/uy/online.dat";//记录在线IP文件
$online_limit=100;//在线静止时限，单位为秒

$timetemp=time();
$ip=getenv('REMOTE_ADDR');//取得访问者IP
$datetime=date("Y-m-d H:i");//取得当前时间
$os=os();//取得访客操作系统

$iip=convertip($ip);
if ( strlen($iip) > 27 ) {
$iip = substr($iip,0,27);  
}

if ( $ip != "211.70.150.18" ) {

/*--------------写入点击,加入在线-------------------*/
$fp=@fopen($click_data,"a+");
@flock($fp,2);
@fwrite($fp,$ip."|".$datetime."|".$os."|".$iip."|||\n");//写空字段以升级用
@fclose($fp);
add_on();
}
/*---------------结束------------------------------*/
if ( $os == "Unknown" ) {
exit;
}
if ( $ip == "61.135.145.212" ) {
exit;
}
if ( $ip == "162.105.146.13" ) {
exit;
}
if ( $ip == "72.30.132.107" ) {
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
$os="";
$Agent = $GLOBALS["HTTP_USER_AGENT"];
if (eregi('win',$Agent) && strpos($Agent, '95')) {
$os="Windows 95";
}
elseif (eregi('win 9x',$Agent) && strpos($Agent, '4.90')) {
$os="Windows ME";
}
elseif (eregi('win',$Agent) && ereg('98',$Agent)) {
$os="Windows 98";
}
elseif (eregi('win',$Agent) && eregi('nt 5\.0',$Agent)) {
$os="Windows 2000";
}
elseif (eregi('win',$Agent) && eregi('nt 5\.1',$Agent)) {
$os="Windows xp";
}
elseif (eregi('win',$Agent) && eregi('nt 5.1',$Agent)) {
$os="Windows xp";
}
elseif (eregi('win',$Agent) && eregi('nt',$Agent)) {
$os="Windows NT";
}
elseif (eregi('win',$Agent) && ereg('32',$Agent)) {
$os="Windows 32";
}
elseif (eregi('linux',$Agent)) {
$os="Linux";
}
elseif (eregi('unix',$Agent)) {
$os="Unix";
}
elseif (eregi('sun',$Agent) && eregi('os',$Agent)) {
$os="SunOS";
}
elseif (eregi('ibm',$Agent) && eregi('os',$Agent)) {
$os="IBM OS/2";
}
elseif (eregi('Mac',$Agent) && eregi('PC',$Agent)) {
$os="Macintosh";
}
elseif (eregi('PowerPC',$Agent)) {
$os="PowerPC";
}
elseif (eregi('AIX',$Agent)) {
$os="AIX";
}
elseif (eregi('HPUX',$Agent)) {
$os="HPUX";
}
elseif (eregi('NetBSD',$Agent)) {
$os="NetBSD";
}
elseif (eregi('BSD',$Agent)) {
$os="BSD";
}
elseif (ereg('OSF1',$Agent)) {
$os="OSF1";
}
elseif (ereg('IRIX',$Agent)) {
$os="IRIX";
}
elseif (eregi('FreeBSD',$Agent)) {
$os="FreeBSD";
}
 if ($os=='') $os = "Unknown";
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
        if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
                return '';
        }

        if($fd = @fopen('p/pb/uy/QQWry.Dat', 'rb')) {

                $ip = explode('.', $ip);
                $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

                $DataBegin = fread($fd, 4);
                $DataEnd = fread($fd, 4);
                $ipbegin = implode('', unpack('L', $DataBegin));
                if($ipbegin < 0) $ipbegin += pow(2, 32);
                $ipend = implode('', unpack('L', $DataEnd));
                if($ipend < 0) $ipend += pow(2, 32);
                $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

                $BeginNum = 0;
                $EndNum = $ipAllNum;

                while($ip1num > $ipNum || $ip2num < $ipNum) {
                        $Middle= intval(($EndNum + $BeginNum) / 2);

                        fseek($fd, $ipbegin + 7 * $Middle);
                        $ipData1 = fread($fd, 4);
                        if(strlen($ipData1) < 4) {
                                fclose($fd);
                                return 'System Error';
                        }
                        $ip1num = implode('', unpack('L', $ipData1));
                        if($ip1num < 0) $ip1num += pow(2, 32);

                        if($ip1num > $ipNum) {
                                $EndNum = $Middle;
                                continue;
                        }

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

                        if($ip2num < $ipNum) {
                                if($Middle == $BeginNum) {
                                        fclose($fd);
                                        return 'Unknown';
                                }
                                $BeginNum = $Middle;
                        }
                }

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
                        while(($char = fread($fd, 1)) != chr(0))
                                $ipAddr2 .= $char;
                }
                fclose($fd);

                if(preg_match('/http/i', $ipAddr2)) {
                        $ipAddr2 = '';
                }
                $ipaddr = "$ipAddr1 $ipAddr2";
                $ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
                $ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
                $ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
                if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
                        $ipaddr = 'Unknown';
                }

                return $ipaddr;

        } else {

                $datadir = './ipdata/';
                $ip_detail = explode('.', $ip);
                if(file_exists($datadir.$ip_detail[0].'.txt')) {
                        $ip_fdata = @fopen($datadir.$ip_detail[0].'.txt', 'r');
                } else {
                        if(!($ip_fdata = @fopen($datadir.'0.txt', 'r'))) {
                                return 'Invalid IP data file';
                        }
                }
                for ($i = 0; $i <= 3; $i++) {
                        $ip_detail[$i] = sprintf('%03d', $ip_detail[$i]);
                }
                $ip = join('.', $ip_detail);
                do {
                        $ip_data = fgets($ip_fdata, 200);
                        $ip_data_detail = explode('|', $ip_data);
                        if($ip >= $ip_data_detail[0] && $ip <= $ip_data_detail[1]) {
                                fclose($ip_fdata);
                                return $ip_data_detail[2].$ip_data_detail[3];
                        }
                } while(!feof($ip_fdata));
                fclose($ip_fdata);
                return 'UNKNOWN';

        }

}


/*---------------------结束-----------------------*/
?>