<?php
	header('content-type:text/html;charset=utf-8');
	require_once "../fun.php";
	
	$note=$_POST['note'];
	$token=$_POST['token'];
	$who_mod= $_SERVER['REMOTE_ADDR'];

	$id=token_check($token);
	if(!$id){
		json(0,"token_false","");
		exit;
	}

	$time=getNowTime();
	$sql="update user set note=? ,last_mod_time=? ,	last_mod_who=? where u_id=?";
	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$note,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(2,$time,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(3,$who_mod,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(4,$id,PDO::PARAM_STR);//设置sql参数
	$smt->execute(); 
	if($smt->rowCount()==1){
		json(1,"success","");//修改成功
	}
	else{
		json(0,"fail","");
	}