<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com

a modern version of
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels




*/
?>
<!-- This needs to be secured -->
<?php //if (!securePage($_SERVER['PHP_SELF'])){die();} ?>

<div class='collapse navbar-collapse navbar-ex1-collapse'>
    <ul class='nav navbar-nav side-nav'>
<?php

//Links for logged in user
if(isUserLoggedIn()) {
?>

        <li>
            <a href='account.php'><i class='fa fa-fw fa-dashboard'></i> Account Home</a>
        </li>
        <li>
            <a href='user_settings.php'><i class='fa fa-fw fa-pencil-square-o'></i> User Settings</a>
        </li>
        <li>
            <a href='logout.php'><i class='fa fa-fw fa-angellist'></i> Logout</a>
        </li>


<?php
	//Links for permission level 2 (default admin)
	if ($loggedInUser->checkPermission(array(2))){
?>

        <li>
            <a href='admin_configuration.php'><i class='fa fa-fw fa-wrench'></i> Admin Configuration</a>
        </li>
        <li>
            <a href='admin_users.php'><i class='fa fa-fw fa-users'></i> Admin Users</a>
        </li>
        <li>
            <a href='admin_permissions.php'><i class='fa fa-fw fa-code'></i> Admin Permissions</a>
        </li>
        <li>
            <a href='admin_pages.php'><i class='fa fa-fw fa-newspaper-o'></i> Admin Pages</a>
        </li>
        <?php
        	}
}
        //Links for users not logged in
        else {
        ?>
        <li>
            <a href='index.php'><i class='fa fa-fw fa-desktop'></i> Home</a>
        </li>
        <li>
            <a href='login.php'><i class='fa fa-fw fa-wrench'></i> Login</a>
        </li>
        <li>
            <a href='register.php'><i class='fa fa-fw fa-desktop'></i> Register</a>
        </li>
        <li>
            <a href='forgot-password.php'><i class='fa fa-fw fa-wrench'></i> Forgot Password</a>
        </li>
    <?php
  	if ($emailActivation)
  	{
  	echo "<li><a href='resend-activation.php'>Resend Activation Email</a></li>";
  	}
  	echo "</ul>";
  }

  ?>
