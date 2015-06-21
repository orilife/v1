<?php 
date_default_timezone_set('PRC'); 
$oldtime=$_POST['time'];
$time=time();
$diff=$time-$oldtime;
echo $oldtime;
echo "<hr>";
echo $time;
echo "<hr>";
echo "$diff";
