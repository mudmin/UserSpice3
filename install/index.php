<?php
/*
UserSpice 2.5.3
by www.UserSpice.com

based on
UserCake Version: 2.0.2
http://usercake.com

UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels

Please note that this version uses technology that some consider
to be outdated. This version is designed as a cosmetic upgrade for
users of 2.0.2 and as a path towards development of version 3.0 and beyond
*/
require_once("../models/db-settings.php");
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <link rel="stylesheet" href="../css/uc.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="../css/uc-theme.min.css">
        <link rel="stylesheet" href="../css/uc-main.css">

        <!--[if lt IE 9]>
            <script src="js/vendor/html5-3.6-respond-1.4.2.min.js"></script>
        <![endif]-->
    </head>
    <body>
			<div class="container">
			  <div class="row">
			    <div align="center" class="col-md-12">
<h1>UserSpice Installer</h1>
<h2>Be sure to update your models/db-settings.php file with</h2>
<h2>Your correct database settings or this install will fail.</h2>
<h2>If install fails, please follow the instructions called _if_install_fails.txt </h2>
<br><br>
<p>PLEASE NOTE: The advanced encryption used in UserSpice requires PHP version 5.5 or later<br> and 5.6 or later is recommended for future-proofing. <br>Your version of php is <font color=red><strong>
  <?php echo phpversion();
if (version_compare(phpversion(), '5.5.0', '<')) { // php version isn't high enough
  echo " </strong><br><font color=red><strong>Your PHP version is out of date. Please update to 5.5.0 or greater to continue.</strong></font>";
  echo "<a href='http://php.net/' target='_blank'>PHP Website</a>"; $server_check_status = "FALSE"; // Can change this to die; or something.
} else {
  echo "<br><h3><font color=green><strong>You are good To go!</strong></font></h3>"; }
  ?>
<h1><a href='?install=true' class='btn btn-primary'>Install UserSpice</a></h1>
<?php
if(isset($_GET["install"]))
{
	$db_issue = false;

	$permissions_sql = "
	CREATE TABLE IF NOT EXISTS `".$db_table_prefix."permissions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(150) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
	";

	$permissions_entry = "
	INSERT INTO `".$db_table_prefix."permissions` (`id`, `name`) VALUES
	(1, 'New Member'),
	(2, 'Administrator');
	";

	$users_sql = "
	CREATE TABLE IF NOT EXISTS `".$db_table_prefix."users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_name` varchar(50) NOT NULL,
	`display_name` varchar(50) NOT NULL,
	`password` varchar(225) NOT NULL,
	`email` varchar(150) NOT NULL,
	`activation_token` varchar(225) NOT NULL,
	`last_activation_request` int(11) NOT NULL,
	`lost_password_request` tinyint(1) NOT NULL,
	`active` tinyint(1) NOT NULL,
	`title` varchar(150) NOT NULL,
	`sign_up_stamp` int(11) NOT NULL,
	`last_sign_in_stamp` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";

	$user_permission_matches_sql = "
	CREATE TABLE IF NOT EXISTS `".$db_table_prefix."user_permission_matches` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`permission_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
	";

	$user_permission_matches_entry = "
	INSERT INTO `".$db_table_prefix."user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
	(1, 1, 2);
	";

	$configuration_sql = "
	CREATE TABLE IF NOT EXISTS `".$db_table_prefix."configuration` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(150) NOT NULL,
	`value` varchar(150) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
	";

	$configuration_entry = "
	INSERT INTO `".$db_table_prefix."configuration` (`id`, `name`, `value`) VALUES
	(1, 'website_name', 'UserSpice'),
	(2, 'website_url', 'localhost/'),
	(3, 'email', 'noreply@UserSpice.com'),
	(4, 'activation', 'false'),
	(5, 'resend_activation_threshold', '0'),
	(6, 'language', 'models/languages/en.php'),
	(7, 'template', 'css/bootstrap.min.css');
	";

	$pages_sql = "CREATE TABLE IF NOT EXISTS `".$db_table_prefix."pages` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`page` varchar(150) NOT NULL,
	`private` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;
	";

	$pages_entry = "INSERT INTO `".$db_table_prefix."pages` (`id`, `page`, `private`) VALUES
	(1, 'account.php', 1),
	(2, 'activate-account.php', 0),
	(3, 'admin_configuration.php', 1),
	(4, 'admin_page.php', 1),
	(5, 'admin_pages.php', 1),
	(6, 'admin_permission.php', 1),
	(7, 'admin_permissions.php', 1),
	(8, 'admin_user.php', 1),
	(9, 'admin_users.php', 1),
	(10, 'forgot-password.php', 0),
	(11, 'index.php', 0),
	(12, 'left-nav.php', 0),
	(13, 'login.php', 0),
	(14, 'logout.php', 1),
	(15, 'register.php', 0),
	(16, 'resend-activation.php', 0),
	(17, 'user_settings.php', 1);
	";

	$permission_page_matches_sql = "CREATE TABLE IF NOT EXISTS `".$db_table_prefix."permission_page_matches` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`permission_id` int(11) NOT NULL,
	`page_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;
	";

	$permission_page_matches_entry = "INSERT INTO `".$db_table_prefix."permission_page_matches` (`id`, `permission_id`, `page_id`) VALUES
	(1, 1, 1),
	(2, 1, 14),
	(3, 1, 17),
	(4, 2, 1),
	(5, 2, 3),
	(6, 2, 4),
	(7, 2, 5),
	(8, 2, 6),
	(9, 2, 7),
	(10, 2, 8),
	(11, 2, 9),
	(12, 2, 14),
	(13, 2, 17);
	";

	$stmt = $mysqli->prepare($configuration_sql);
	if($stmt->execute())
	{
		$cfg_result = "<p>".$db_table_prefix."configuration table created.....</p>";
	}
	else
	{
		$cfg_result = "<p>Error constructing ".$db_table_prefix."configuration table.</p>";
		$db_issue = true;
	}

	echo $cfg_result;
	$stmt = $mysqli->prepare($configuration_entry);
	if($stmt->execute())
	{
		echo "<p>Inserted basic config settings into ".$db_table_prefix."configuration table.....</p>";
	}
	else
	{
		echo "<p>Error inserting config settings access.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($permissions_sql);
	if($stmt->execute())
	{
		echo "<p>".$db_table_prefix."permissions table created.....</p>";
	}
	else
	{
		echo "<p>Error constructing ".$db_table_prefix."permissions table.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($permissions_entry);
	if($stmt->execute())
	{
		echo "<p>Inserted 'New Member' and 'Admin' groups into ".$db_table_prefix."permissions table.....</p>";
	}
	else
	{
		echo "<p>Error inserting permissions.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($user_permission_matches_sql);
	if($stmt->execute())
	{
		echo "<p>".$db_table_prefix."user_permission_matches table created.....</p>";
	}
	else
	{
		echo "<p>Error constructing ".$db_table_prefix."user_permission_matches table.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($user_permission_matches_entry);
	if($stmt->execute())
	{
		echo "<p>Added 'Admin' entry for first user in ".$db_table_prefix."user_permission_matches table.....</p>";
	}
	else
	{
		echo "<p>Error inserting admin into ".$db_table_prefix."user_permission_matches.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($pages_sql);
	if($stmt->execute())
	{
		echo "<p>".$db_table_prefix."pages table created.....</p>";
	}
	else
	{
		echo "<p>Error constructing ".$db_table_prefix."pages table.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($pages_entry);
	if($stmt->execute())
	{
		echo "<p>Added default pages to ".$db_table_prefix."pages table.....</p>";
	}
	else
	{
		echo "<p>Error inserting pages into ".$db_table_prefix."pages.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($permission_page_matches_sql);
	if($stmt->execute())
	{
		echo "<p>".$db_table_prefix."permission_page_matches table created.....</p>";
	}
	else
	{
		echo "<p>Error constructing ".$db_table_prefix."permission_page_matches table.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($permission_page_matches_entry);
	if($stmt->execute())
	{
		echo "<p>Added default access to ".$db_table_prefix."permission_page_matches table.....</p>";
	}
	else
	{
		echo "<p>Error adding default access to ".$db_table_prefix."user_permission_matches.</p>";
		$db_issue = true;
	}

	$stmt = $mysqli->prepare($users_sql);
	if($stmt->execute())
	{
		echo "<p>".$db_table_prefix."users table created.....</p>";
	}
	else
	{
		echo "<p>Error constructing users table.</p>";
		$db_issue = true;
	}


	if(!$db_issue)
		echo "<p><strong>Database setup complete, please delete the install folder.</strong></p>";
	else
	echo "<p><a href=\"?install=true\">Try again</a></p>";
}
else
{
	echo "

	";
}

echo "
</body>
</html>";

?>
