<?php
	include "../fun.php";
	header('content-type:text/html;charset=utf-8');
	$name=$_POST['name'];
	$password=$_POST['password'];
	$gender=$_POST['gender'];
	$mobile=$_POST['input'];
	$who= $_SERVER['REMOTE_ADDR'];
	$status=1;	
	$timestamp=$_POST['timestamp'];
	$code=$_POST['code'];


	$str="orilife".$mobile;
	check_code($timestamp,$str,$code);


	if(!isset($name)||!isset($password)||!isset($gender)||!isset($mobile)){
			json(0,"illegal","");
			exit;
	}
	if(check_input($mobile)!=2){
			json(0,"moblie_false","");
			exit;
	}
	date_default_timezone_set('PRC'); 
	$time=date("Y-m-d H:i:s") ;



	


	if(ckeck_duplicate($mobile))
	{
		$sql="insert into user(username,password,create_time,gender,mobile,create_who,status) values(?,?,'{$time}',?,?,?,?)";
		$pdo=pdo_con();//接收pdo连接对象
		
		$smt=$pdo->prepare($sql);
		$smt->bindParam(1,$name,PDO::PARAM_STR);//设置sql参数
		$smt->bindParam(2,$password,PDO::PARAM_STR);//设置sql参数
		$smt->bindParam(3,$gender,PDO::PARAM_STR);//设置sql参数
		$smt->bindParam(4,$mobile,PDO::PARAM_STR);//设置sql参数
		$smt->bindParam(5,$who,PDO::PARAM_STR);//设置sql参数
		$smt->bindParam(6,$status,PDO::PARAM_STR);//设置sql参数
		$smt->execute();
				if($smt->rowCount()==1){
					$r=token_registration($pdo->lastInsertId());
					//注册成功返回token
					$reult=array("token"=>$r);
					json(1,"success",$result);
				}
				else{
					json(0,"fail","");//注册失败返回0
				}		
	}
	else
	{
		json(0,"duplicate","");	//该手机号已注册	
	}

function ckeck_duplicate($mobile){
	$check=true;
	$sql='select mobile from user where mobile=?';
	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$mobile,PDO::PARAM_STR);//设置sql参数
	$smt->execute(); 
	$row=$smt->fetch();
	if ($smt->rowCount()==1){
		$check =false;
	}
	return $check;
}