<?php
include_once "include/common.php";
include_once "include/template.php";
$theme_path = "template";


$tpl = new Template($CONFIG["template_path_sys"], $sTemplateUnknown);
$tpl -> set_file("listing", "list.html");
$tpl -> set_var("template_src", $CONFIG["base_url"]);
$tpl -> set_var("template_folder",$theme_path);


$q = mysql_escape("SELECT * FROM user ");

$tpl -> set_block("listing","list","lists");
if (mysql_num_rows($q) > 0){	
	while($r = mysql_fetch_assoc($q)){
		$tpl -> set_var("id",$r["u_id"]);
		$tpl -> set_var("name",$r["username"]);
		$tpl -> set_var("gender",$r["gender"]);
		$tpl -> set_var("avatar",$r["avatar"]);
		$tpl -> set_var("role",$r["role"]);
		$tpl -> set_var("status",$r["status"]);
		$tpl -> set_var("email",$r["email"]);
		$tpl -> parse("lists","list","listing");
	}
}else{
	$tpl -> set_var("lists","");
}
$tpl -> set_var("msg",$msg);


$tpl -> pparse("out", "listing");
?>