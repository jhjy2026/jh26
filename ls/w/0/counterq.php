<?php
$countfile="w/0/counter.txt";
$fp=fopen($countfile,"r");
$num=fgets($fp,10);
$num=$num+1;
printf("%06d",$num);
fclose($fp);
$fp=fopen($countfile,"w");
fputs($fp,$num);
fclose($fp);

?>