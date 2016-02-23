<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com
Major code contributions by Astropos

a modern version of
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels

*/
ob_start();
require_once("db-settings.php"); //Require DB connection
//Retrieve settings
$stmt = $mysqli->prepare("SELECT id, name, value
	FROM ".$db_table_prefix."configuration");
$stmt->execute();
$stmt->bind_result($id, $name, $value);

while ($stmt->fetch()){
	$settings[$name] = array('id' => $id, 'name' => $name, 'value' => $value);
}
$stmt->close();


//Set Settings
$emailActivation = $settings['activation']['value'];
$mail_templates_dir = "models/mail-templates/";
$websiteName = $settings['website_name']['value'];
$websiteUrl = $settings['website_url']['value'];
$emailAddress = $settings['email']['value'];
$resend_activation_threshold = $settings['resend_activation_threshold']['value'];
$emailDate = date('dmy');
$language = $settings['language']['value'];
$template = $settings['template']['value'];

$master_account = -1;

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
$default_replace = array($websiteName,$websiteUrl,$emailDate);

if (!file_exists($language)) {
	$language = "../models/languages/en.php";
}

if(!isset($language)) $language = "../models/languages/en.php";

//Pages to require
require_once($language);
require_once("classes/Config.php");
require_once("classes/Cookie.php");
require_once("classes/DB.php");
require_once("classes/Hash.php");
require_once("classes/Input.php");
require_once("classes/Redirect.php");
require_once("classes/Session.php");
require_once("classes/Token.php");
//User Class will be implemented in the next major rewrite
// require_once("classes/User.php");
require_once("classes/Validate.php");
require_once("class.mail.php");
require_once("class.user.php");
require_once("class.newuser.php");
require_once("funcs.php");
require_once("helpers/helpers.php");




session_start();

//Global User Object Var
//loggedInUser can be used globally if constructed
if(isset($_SESSION["userCakeUser"]) && is_object($_SESSION["userCakeUser"]))
{
	$loggedInUser = $_SESSION["userCakeUser"];
}

// Set config
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => $db_host,
		'username' => $db_user,
		'password' => $db_pass,
		'db' => $db_name,
	),
	'remember' => array(
		'cookie_name' => 'pmqesoxiw318374csa',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token',
	),
	'stripe' => array(
		'mode' => 'TEST',//Change to Live when ready to roll
		'test_secret' => $test_secret,
		'test_public' => $test_public,
		'live_secret' => $live_secret,
		'live_public' => $live_public,
	),
);

function env($type='server'){
  if($type == 'server'){
    if($_SERVER['HTTP_HOST'] == 'localhost'){
      return $_SERVER['DOCUMENT_ROOT'].'/painters/';
    }else{
      return $_SERVER['DOCUMENT_ROOT'].'/';
    }
  }else{
    if($_SERVER['HTTP_HOST'] == 'localhost'){
      return '/ucdev/';
    }else{
      return '/';
    }
  }

}

// Auto Load Classes
// spl_autoload_register(function($class){
// 	if(file_exists(env().'classes/'.$class.'.php') == 1){
// 		require_once env().'classes/' . $class . '.php';
// 	}
// });
?>
