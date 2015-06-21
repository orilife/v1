<?PHP

/**
 * 全局配置文件(config.php)
 *
 * @package op
 * @subpackage common
 */

/**
 * CONFIG全局配置 - template var
 * 设置模板的参数
 * @global config.php $sTemplateUnknown
 * @name $sTempLateUnknown
 */
$sTemplateUnknown	= "keep";

/**
 * CONFIG全局配置 - template system path
 * 设置模板的路径 
 * @global config.php $template_path_sys
 * @name $templatepath
 */

$CONFIG["template_path_sys"] = "template";

/**
 * CONFIG全局配置 - template path
 * 设置模板的路径 , 注意结尾处没有"/";
 * @global config.php $templatepath
 * @name $templatepath
 */
$CONFIG["template_path"] = $CONFIG["template_path_sys"]; 



/**
 * CONFIG全局配置 - base_url_sys
 * 页面之间的连接采用绝对连接，这个变量用来标示路径,采用完整的系统路径
 * @global config.php $CONFIG["base_url_sys"]
 * @name $CONFIG["base_url_sys"]
 */

$CONFIG["base_url_sys"] = "https://orilife.azurewebsites.net/";


/**
 * CONFIG全局配置 - base_path_sys
 * 完整系统的安装路径，服务器安装位置的绝对路径
 * @global config.php $CONFIG["base_path_sys"]
 * @name $CONFIG["base_path_sys"]
 */

$CONFIG["base_path_sys"] = "";


/**
 * CONFIG全局配置 - base_url
 * 页面之间的连接采用绝对连接，这个变量用来标示路径
 * @global config.php $CONFIG["base_url"]
 * @name $CONFIG["base_url"]
 */
$CONFIG["base_url"] = $CONFIG["base_url_sys"];

/**
 * CONFIG全局配置 - base_path
 * 系统的安装路径，服务器安装位置的绝对路径
 * @global config.php $CONFIG["base_path"]
 * @name $CONFIG["base_path"]
 */
$CONFIG["base_path"] = $CONFIG["base_path_sys"];

/**
 * CONFIG全局配置 - max_list
 * 页面之间的连接采用绝对连接，这个变量用来标示路径
 * @global config.php $CONFIG["max_list"]
 * @name $CONFIG["max_list"]
 */
$CONFIG["max_list"] = 10;

/**
 * CONFIG全局配置 - mysql_host
 * 数据库主机名
 * @global config.php $CONFIG["mysql_host"]
 * @name $CONFIG["mysql_host"]
 */
$CONFIG["mysql_host"] = "ap-cdbr-azure-east-c.cloudapp.net";

/**
 * CONFIG全局配置 - mysql_user
 * 登陆数据库所用的用户名
 * @global config.php $CONFIG["mysql_user"]
 * @name $CONFIG["mysql_user"]
 */
$CONFIG["mysql_user"] = "beae7c7ab23373";

/**
 * CONFIG全局配置 - mysql_pass
 * 登陆数据库所用的密码
 * @global config.php $CONFIG["mysql_pass"]
 * @name $CONFIG["mysql_pass"]
 */
$CONFIG["mysql_pass"] = "0fcec91d";

/**
 * CONFIG全局配置 - mysql_db
 * 所调用的数据库的名字
 * @global config.php $CONFIG["mysql_db"]
 * @name $CONFIG["mysql_db"]
 */
$CONFIG["mysql_db"] = "orilifemysql";


?>