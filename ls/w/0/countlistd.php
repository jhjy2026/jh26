<?php include("counter2.php");?><html>

<head>

<title>WM流量统计</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="../yehuo/css/font.css" rel="stylesheet" type="text/css">

<link href="../yehuo/css/link.css" rel="stylesheet" type="text/css">

</head>



<body>

<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td height="65" bgcolor="#000000"><table width="100%" border="0" cellspacing="1" cellpadding="1">

        <tr bgcolor="#EEEEF6" class="f13"> 

          <td colspan="7" background="../yehuo/images/sys/bodybj.gif"><font color="#FFFFFF">
			＃基本情况</font></td>

        </tr>

        <tr bgcolor="#666699" class="f13"> 

          <td width="100"><div align="center"><font color="#FFCC00">总点击数</font></div></td>

          <td width="100"><div align="center"><font color="#FFCC00">总IP数</font></div></td>

          <td width="100"><div align="center"><font color="#FFCC00">本月点击数</font></div></td>

          <td width="100"><div align="center"><font color="#FFCC00">本月IP数</font></div></td>

          <td width="100"><div align="center"><font color="#FFCC00">今日点击数</font></div></td>

          <td width="100"><div align="center"><font color="#FFCC00">今日IP数</font></div></td>

          <td width="100"><div align="center"><font color="#FFCC00">在线人数</font></div></td>

        </tr>

        <tr bgcolor="#EEEEF6" class="f13"> 

          <td height="15"><div align="center"><font color="#000000"><?php echo $allnums;?></font></div></td>

          <td><div align="center"><font color="#000000"><?php echo $allips;?></font></div></td>

          <td><div align="center"><font color="#000000"><?php echo $ms;?></font></div></td>

          <td><div align="center"><font color="#000000"><?php echo $allmips;?></font></div></td>

          <td><div align="center"><font color="#000000"><?php echo $ds;?></font></div></td>

          <td><div align="center"><font color="#000000"><?php echo $alldips;?></font></div></td>

          <td><div align="center"><font color="#000000"><?php echo $onlines;?></font></div></td>

        </tr>

        <tr bgcolor="#EEEEF6" class="f13"> 

          <td colspan="7"><div align="center">开始时间：<?php echo $begintime;?> ＃ 
			统计天数：<? echo $alldays;?> ＃ 日平均点击：<?php echo $aveday;?> 

              ＃ 日平均IP：<?php echo $aveips;?></div></td>

        </tr>

      </table>

      <table width="100%" border="0" cellspacing="1" cellpadding="1">

        <tr bgcolor="#666699" class="f13"> 

          <td width="100%" colspan="5" background="../yehuo/images/sys/bodybj.gif"><font color="#FFFFFF">
			＃【WM】最近100个点击（管理已经滤除）</font></td>

        </tr>

        <tr bgcolor="#666699" class="f13">

          <td width="8%"><div align="center"><font color="#FFCC00">位数</font></div></td>

          <td width="20%"><div align="center"><font color="#FFCC00">ip</font><font color="#FFFFFF"></font></div></td>
     <td width="30%"><div align="center"><font color="#FFCC00">地理位置</font><font color="#FFFFFF"></font></div></td>

          <td width="16%"><div align="center"><font color="#99CC66"><font color="#99CC66"><font color="#FFCC00">
			操作系统</font></font><font color="#FFFFFF"></font></font></div></td>

          <td width="21%"><div align="center"><font color="#FFCC00">到访时间</font><font color="#FFFFFF"></font></div></td>
          <td width="5%"><div align="center"><font color="#FFCC00">类</font><font color="#FFFFFF"></font></div></td>


        </tr>

        <?php

$fget=@file("counter.dat");

$cnums=count($fget);

$si=$cnums;

for($i=count($fget)-1;$i>=$cnums-100;$i--){

if($i<0) break;

$s=explode("|",$fget[$i]);
$s[3] = iconv('GB2312', 'UTF-8//IGNORE', $s[3]);

print <<<SHOW

        <tr bgcolor="#EEEEF6" class="f13"> 

		  <td><div align="center"><font color="#000000">$si</font></div></td>

          <td><div align="center"><font color="#000000">$s[0]</font></div></td>
          <td><div align="center"><font color="#000000">$s[3]</font></div></td>

          <td><div align="center"><font color="#000000">$s[2]</font></div></td>

          <td><div align="center"><font color="#000000">$s[1]</font></div></td>
          <td><div align="center"><font color="#000000">$s[4]</font></div></td>
        </tr>

SHOW;

$si--;

}

?>

        <tr bgcolor="#666699"> 

          <td colspan="5" class="f13"><div align="center"><font color="#FFFFFF">
			关工委主页访问计数</font></div></td>

        </tr>

      </table></td>

  </tr>

</table>



</body>

</html>

