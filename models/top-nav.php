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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   <title><?php echo $websiteName;?></title>
    <!-- Bootstrap Core CSS -->
	<?php if (!$template)
		{
		?>
    <link href="css/bootstrap.min.css" rel="stylesheet">
		<?php
		}
	else
		{
		?>
	<link href="<?php echo $template; ?>" rel="stylesheet">
		<?php
		}
		?>
    <link href="css/style.css" rel="stylesheet">
    <!-- Custom Fonts -->
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="navbar navbar-inverse" role="navigation">
<div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header ">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        <a class="navbar-brand" href="./"><img src="images/logo.png" alt="" /></a>
	</div>

    <div class="collapse navbar-collapse navbar-top-menu-collapse navbar-right">
    <ul class="nav navbar-nav ">

	   	<?php if(isUserLoggedIn())
			{ ?>
        <li><a href="account.php"><i class="fa fa-fw fa-user"></i> <?php echo "$loggedInUser->username";?></a></li>
        <li class="hidden-sm hidden-md hidden-lg"><a href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>
        <li class="hidden-sm hidden-md hidden-lg"><a href="account.php"><i class="fa fa-fw fa-user"></i> User Account</a></li>
        <li class="hidden-sm hidden-md hidden-lg"><a href="user_settings.php"><i class="fa fa-fw fa-pencil-square-o"></i> User Settings</a></li>


		<li class="dropdown hidden-xs">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown"> User Menu <b class="caret"></b></a>
            <ul class="dropdown-menu">
			   <li><a href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>
			   <li class="divider"></li>
               <li><a href="account.php"><i class="fa fa-fw fa-user"></i> User Account</a></li>
               <li><a href="user_settings.php"><i class="fa fa-fw fa-pencil-square-o"></i> User Settings</a></li>
			<li><a href="logout.php"><i class="fa fa-fw fa-sign-out"></i> Log Out</a></li>
            </ul>
        </li>
		  <?php //Links for permission level 2 (default admin)
			if ($loggedInUser->checkPermission(array(2))){   ?>

			<li class="hidden-sm hidden-md hidden-lg"><a href="admin_users.php"><i class="fa fa-fw fa-users"></i> Manage Users</a></li>
			<li class="hidden-sm hidden-md hidden-lg"><a href="admin_configuration.php"><i class="fa fa-fw fa-wrench"></i> Admin Configuration</a></li>
			<li class="hidden-sm hidden-md hidden-lg"><a href="admin_permissions.php"><i class="fa fa-fw fa-code"></i> Admin Permissions</a></li>
			<li class="hidden-sm hidden-md hidden-lg"><a href="admin_pages.php"><i class="fa fa-fw fa-newspaper-o"></i> Admin Pages</a></li>

			<li class="dropdown hidden-xs">
				<a class="dropdown-toggle" href="#" data-toggle="dropdown">Admin Menu <b class="caret"></b></a>
				<ul class="dropdown-menu ">
					<li><a href="admin_users.php"><i class="fa fa-fw fa-users"></i> Manage Users</a></li>
					<li class="divider"></li>
					<li><a href="admin_configuration.php"><i class="fa fa-fw fa-wrench"></i> Admin Configuration</a></li>
					<li><a href="admin_permissions.php"><i class="fa fa-fw fa-code"></i> Admin Permissions</a></li>
					<li><a href="admin_pages.php"><i class="fa fa-fw fa-newspaper-o"></i> Admin Pages</a></li>
					<li><a href="logout.php"><i class="fa fa-fw fa-sign-out"></i> Log Out</a></li>
				</ul>
			</li>
			<?php } // is user an admin ?>

		<li class="hidden-sm hidden-md hidden-lg"><a href="logout.php"><i class="fa fa-fw fa-sign-out"></i> Log Out</a></li>

		<?php }
		else	{ // user is not and admin OR logged in
		?>
		<li><a href="login.php" class=""><i class="fa fa-sign-in"></i> Sign In</a></li>
		<li><a href="register.php" class=""><i class="fa fa-plus-square"></i> Sign Up</a></li>
		<li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-question-circle"></i> <?php echo lang("NAVTOP_HELPTEXT","");?></a>
            <ul class="dropdown-menu">
				<li><a href="forgot-password.php"><i class="fa fa-wrench"></i> Forgot Password</a></li>
				<li><a href="resend-activation.php"><i class="fa fa-exclamation-triangle"></i> Resend Activation Email</a></li>
            </ul>
        </li>
		<?php } ?>

    </ul>
    </div>



</div>
</div>
