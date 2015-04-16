<?php
include_once "include/common.php";

// there is authentication can be done here, not in V1 yet ----------

$r		  = 0;
$input	  = $_REQUEST["input"];
$password = $_REQUEST["password"];
$type     = $_REQUEST["type"];

//Login validation
$r        = userLogin($login_type,$input,$pwd);
return $r;




?>