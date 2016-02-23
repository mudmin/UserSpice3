<?php
//Your Application Details
$app_name = "UserSpice 3";
$app_ver = ""; //Feel free to leave this as an empty string.

//The name of your configuration file
$config_file = "../settings.php";

$sqlfile = "install/includes/sql.sql";

//Navigation Settings
$step1 = "Welcome";
$step2 = "Database Settings";
$step3 = "ReCAPTCHA Settings";
$step4 = "Cleanup";


//System Requirements
$php_ver = "5.5.0";

//Cleanup Files
$files = array (
"index.php",
"recovery.php",
"step2.php",
"step3.php",
"step4.php"
);

//Where do you want to redirect after cleanup?
$redirect = "../index.php";


 ?>
