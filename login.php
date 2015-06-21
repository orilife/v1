<?php

require_once "fun.php";

$r		  = 0;
$input	  = $_POST["input"];
$pwd      = md5(md5($_POST["password"]));

//Login validation
$r        = userLogin($input,$pwd);
if($r){
	$result=array("token"=>$r);
	json(1,"success",$result);
}else{
	json(0,"fail","");
}



?>