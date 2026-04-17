<?php

$click_data="counter.dat";//记录浏览数据文件
$online_data="online.dat";//记录在线IP文件
$online_limit=100;//在线静止时限，单位为秒

$timetemp=time();

$ip=getenv('REMOTE_ADDR');//取得访问者IP
$datetime=date("Y-m-d H:s");//取得当前时间
$os=os();//取得访客操作系统

/*--------------写入点击,加入在线-------------------*/
/*
$fp=@fopen($click_data,"a+");
@flock($fp,2);
@fwrite($fp,$ip."|".$datetime."|".$os."|||\n");//写空字段以升级用
@fclose($fp);
add_on();
*/
/*---------------结束------------------------------*/
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
    $os = "";
    $agent = $_SERVER['HTTP_USER_AGENT'];
    
    if (strpos($agent, 'win') !== false && strpos($agent, '95') !== false) {
        $os = "Windows 95";
    } elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false) {
        $os = "Windows ME";
    } elseif (strpos($agent, 'win') !== false && strpos($agent, '98') !== false) {
        $os = "Windows 98";
    } elseif (strpos($agent, 'win') !== false && preg_match('/nt 5\.0/', $agent)) {
        $os = "Windows 2000";
    } elseif (strpos($agent, 'win') !== false && preg_match('/nt 5\.1/', $agent)) {
        $os = "Windows XP";
    } elseif (strpos($agent, 'win') !== false && strpos($agent, 'nt') !== false) {
        $os = "Windows NT";
    } // ...其他条件保持不变
    
    if ($os == '') {
        $os = "Unknown";
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
/*---------------------结束-----------------------*/

?>