<?php

require_once "fun.php";

$input    = $_POST["input"];        // token from the 3rd party to identify the uniqueness of the user, this value will be stored in our DB column "wechat" or "weibo"
$return   = "0";


$type     = check_input($input);    // type 1 is email, type 2 is mobile  

if (($type != 1)){
	if ($type != 2) die ("invalid request");
} 

$return = check_duplicate_3rd($input,$type);

echo $return ;


function check_duplicate_3rd($input,$type){   // check the email or mobile is registered or not 
	$sql = "";
	$check = false;
	
	if ($type == 1) {$sql = 'select email from user where email = ?';}
	if ($type == 2) {$sql = 'select mobile from user where mobile = ?';}
	
	if (strlen($sql) > 5 ) {

				$pdo=pdo_con();
				$smt=$pdo->prepare($sql);
				$smt->bindParam(1,$input,PDO::PARAM_STR);
				$smt->execute(); 
				$row=$smt->fetch();
				if ($smt->rowCount()==1){
					$check = true;
				}	
		}
	return $check;
}

?>