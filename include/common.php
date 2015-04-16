<?PHP
/**
 * 全局配置文件(config)
 *
 * @package op
 * @subpackage common
 * @author schumann
 */

include_once "config.php";

$_mysql_connected = false;
@session_start();
mysql_start();
//encodeVar();




function fatal($prompt) {
	$s = "Click <a href='javascript:history.back(-1)' title='back'>Here</a> Back";
	die($prompt.$s);
}

function encodeVar() {
	foreach ($_REQUEST as $k => $v) {
		if(is_array($v)) {
/*
			foreach($v as $key => $value) {
				$_REQUEST[$key] = stripcslashes(htmlspecialchars($value));
			}
*/
		} elseif(is_string($v)) {
			$_REQUEST[$k] = stripcslashes(htmlspecialchars($v));
		} else {
			$_REQUEST[$k] = stripcslashes($v);
		}
	}
}

function mysql_start() {
	global $CONFIG, $_mysql_connected;
	if ($_mysql_connected) return;
	mysql_pconnect($CONFIG["mysql_host"], $CONFIG["mysql_user"],
		$CONFIG["mysql_pass"]) || fatal("mysql connect");
	mysql_select_db($CONFIG["mysql_db"]) || fatal("mysql select db");
	$_mysql_connected = true;
}

function mysql_execute($statement) {
	$q = mysql_query($statement);

	//if (!$q) fatal("mysql query error on $statement<BR>\n" . mysql_error());
	return $q;
}

function mysql_escape_str($statement) {
	$arg = array();
	for ($i = 1;  $i < func_num_args();  ++ $i) {
		$s = func_get_arg($i);
		array_push($arg, addslashes($s));
	}

	$sql = "";
	$offset = 0;
	$count = count($arg);
	while ($pos = strpos($statement, "?", $offset)) {
		$sql .= substr($statement, $offset, $pos-$offset);
		$sql .= "'" . array_shift($arg) . "'";
		$offset = $pos + 1;
		$count --;
	}

	$sql .= substr($statement, $offset);

	if ($count != 0)
		fatal("mysql escape parameter count mismatch: $statement");
		
	return $sql;
}

function mysql_escape($statement) {
	$arg = array();
	for ($i = 1;  $i < func_num_args();  ++ $i) {
		$s = func_get_arg($i);
		array_push($arg, addslashes($s));
	}

	$sql = "";
	$offset = 0;
	$count = count($arg);
	while ($pos = strpos($statement, "?", $offset)) {
		$sql .= substr($statement, $offset, $pos-$offset);
		$sql .= "'" . array_shift($arg) . "'";
		$offset = $pos + 1;
		$count --;
	}
	$sql .= substr($statement, $offset);

	if ($count != 0)
		fatal("mysql escape parameter count mismatch: $statement");
	return mysql_execute($sql);
}

//

$_input_calendar = "../cal/";
$_input_calendar_count = 1;

function input_calendar($name, $attr = "", $default = false) {
//$_input_calendar
	global $_input_data, $_input_calendar, $_input_calendar_count;

	$value = $default;
	if (isset($_input_data[$name]))
		$value = $_input_data[$name];

    if ($_input_calendar_count == 1) {
        $s .= "<script type=\"text/javascript\" src=\"".$_input_calendar."calendar.js\"></script>\n";
        $s .= "<script type=\"text/javascript\" src=\"".$_input_calendar."jscalendar-setup.js\"></script>\n";
        $s .= "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"".$_input_calendar."calendar-win2k.css\" title=\"win2k-cold-1\" />\n";
    }
	$s .= "<INPUT TYPE='text' ID=\"".$name.$_input_calendar_count."\" NAME='$name' $attr size='10' maxlength='10' VALUE='$value'>\n";
    $s .= "<SCRIPT TYPE=\"TEXT/JAVASCRIPT\">\n";
    $s .= "document.writeln('<img id=\"".$name.$_input_calendar_count."p\" src=\"".$_input_calendar."cal.png\" title=\"select date\" style=\"cursor:pointer; cursor:hand;\"/>');\n";
    $s .= "Calendar.setup(\n";
    $s .= "{\n";
    $s .= "    inputField  : \"".$name.$_input_calendar_count."\",\n"; 
    $s .= "    button  : \"".$name.$_input_calendar_count."p\"\n";
    $s .= "}\n"; 
    $s .= ");\n";
    $s .= "</SCRIPT>";

    $_input_calendar_count ++;

	return $s;
}

function template_init($dir, $name, $file) {
	global $CONFIG, $template_path, $sTemplateUnknown;
	
	$tpl = new Template($dir. $template_path, $sTemplateUnknown);

	$tpl -> set_file($name, $file);
	
	//如果在新的窗口中打开iframe中的页面，那么跳转到错误页面
	$url = $CONFIG["base_url"] . "common/new_win_err";	
	$sNewWinJs = "
		<script language=\"JavaScript\">
			if(self == top) self.location.href = \"$url\";
		</script>";
	$tpl -> set_var("new_win_js", $sNewWinJs);

	//设置 屏蔽一些按键的js
	if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE")) $escape_src = "<script src=\"" . $CONFIG["base_url"] . "common/key_escape\" language=\"javascript\"></script>";
	else $escape_src = "";
	$tpl -> set_var("key_escape_js_src", $escape_src);

	//设置css
	$tpl -> set_var("theme_src", $CONFIG["base_url_sys"] . "templates/" . $template_path);
	
	return $tpl;
}
?>