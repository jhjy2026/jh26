
<?php
include 'w/0/qqwry.php';

$ip = $_SERVER['REMOTE_ADDR'];
$qqwry = new QQwry('w/0/QQWry.Dat');
$location = $qqwry->getLocation($ip);
$country_utf8 = iconv('GB2312', 'UTF-8', $country);
$area_utf8 = iconv('GB2312', 'UTF-8', $area);
$userAgent = $_SERVER['HTTP_USER_AGENT'];
preg_match('/(Windows|Macintosh|Linux)/', $userAgent, $os);
$os = $os[1] ?? 'δ֪';

$time = date("Y-m-d H:i:s");
$id = uniqid();

$filename = 'w/0/visitors_data.txt';
$file = fopen($filename, "a");
fwrite($file, "ID: $id, IP: $ip, Location: $location, OS: $os, Time: $time\n");
fclose($file);

header('Location: w/0/display_visitors.php');
exit;
?>
