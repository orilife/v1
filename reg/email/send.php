<?php
header("content-type:text/html;charset=utf-8");
//验证码生成函数
require_once "../../fun.php";
$code=code(5);
$md5code=md5($code);
$email=$_POST['mail'];


ini_set("magic_quotes_runtime",0);
require 'class.phpmailer.php';
try {
	$mail = new PHPMailer(true); 
	$mail->IsSMTP();
	$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
	$mail->SMTPAuth   = true;                  //开启认证
	$mail->Port       = 25;                    
	$mail->Host       = "smtp.163.com"; 
	$mail->Username   = "ori_life@163.com";    
	$mail->Password   = "Aa112233";            
	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
	$mail->AddReplyTo("ori_life@163.com","mckee");//回复地址
	$mail->From       = "ori_life@163.com";
	$mail->FromName   = "ori_life";
	$to = $email;
	$mail->AddAddress($to);
	$mail->Subject  = "ori_life邮箱验证";
	$mail->Body = "<h1>欢迎注册ori_life</h1>您的验证码是：".$code;
	$mail->AltBody    = "验证码是:".$code; //当邮件不支持html时备用显示，可以省略
	$mail->WordWrap   = 80; // 设置每行字符串的长度
	//$mail->AddAttachment("f:/test.png");  //可以添加附件
	$mail->IsHTML(true); 
	$mail->Send();
	$data=array("code"=>$md5code);
	json(1,"success",$data);
} catch (phpmailerException $e) {
	 // echo "邮件发送失败：".$e->errorMessage();
	json(0,"fail","");
}
?>