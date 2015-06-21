<?php

require_once "fun.php";


$token    = $_POST["token"];        // token from the 3rd party to identify the uniqueness of the user, this value will be stored in our DB column "wechat" or "weibo"
$type     = $_POST["type"];         // 1 is wechat , 2 is weibo,3 is qq 
$username = $_POST["username"];     // username validated from the 3rd party
$gender   = $_POST["gender"];       // gender

$status   = 1;
$who	  = $_SERVER['REMOTE_ADDR'];
$return   = "0";



if (check_duplicate($token,$type)){    //if it's registered, it means - user needs to login

	if ($type == "1") 	$sql="select * from user where wechat = ?";
	if ($type == "2") 	$sql="select * from user where weibo = ?" ;
	if ($type == "3") 	$sql="select * from user where qq= ?" ;

	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$token,PDO::PARAM_STR);//设置参数

	$smt->execute(); 
	if ($smt->rowCount() == 1){
		$r = $smt->fetch();//返回结果集
		$return = check_user($r["u_id"],$r["status"]);
				if($return){
					$result=array("token"=>$return);
					json(1,"success",$result);
				}else{
					json(0,"fail","");
				}
	}

}else{   // new user sign_up

	$password = md5(time());

	if ($type == "1") 	$sql="insert into user(username,password,gender,create_who,status,wechat) values(?,?,?,?,?,?)";
	if ($type == "2") 	$sql="insert into user(username,password,gender,create_who,status,weibo) values(?,?,?,?,?,?)";
	if ($type == "3") 	$sql="insert into user(
		username,password,gender,create_who,status,qq) values(?,?,?,?,?,?)";


		$pdo=pdo_con();//接收pdo连接对象
		
		$smt=$pdo->prepare($sql);
		$smt->bindParam(1,$username,PDO::PARAM_STR);
		$smt->bindParam(2,$password,PDO::PARAM_STR);
		$smt->bindParam(3,$gender,PDO::PARAM_STR);
		$smt->bindParam(4,$who,PDO::PARAM_STR);
		$smt->bindParam(5,$status,PDO::PARAM_STR);
		$smt->bindParam(6,$token,PDO::PARAM_STR);
		$smt->execute();
				if($smt->rowCount()==1){
					$r=token_registration($pdo->lastInsertId());
					$result=array("token"=>$r);
					json(1,"success",$result);
				}
				else{
					json(0,"fail_insert","");
				}				
}









function check_duplicate($input,$type){   // check the wechat or weibo is registered or not -- if it's registered, it means , user needs to login , if it's unregistered, it means it's a new user sign_up
	$sql = "";
	$check = false;
	
	if ($type == "1") $sql = 'select wechat from user where wechat = ?';
	if ($type == "2") $sql = 'select weibo from user where weibo = ?';
	if ($type == "3") $sql = 'select qq   from user where qq = ?';
	
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
