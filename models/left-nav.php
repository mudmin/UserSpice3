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
if(isUserLoggedIn())
	{ ?>

	<ul class="list-group">
		<li class="list-group-item"><a href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>
	</ul>

	<ul class="list-group">
		<li class="list-group-item"><a href="account.php"><i class="fa fa-fw fa-user"></i> User Account</a></li>
		<li class="list-group-item"><a href="user_settings.php"><i class="fa fa-fw fa-pencil-square-o"></i> User Settings</a></li>
		<li class="list-group-item"><a href="logout.php"><i class="fa fa-fw fa-sign-out"></i> Log Out</a></li>
	</ul>


<?php
  //tdnks for permission level 2 (default admin)
  if ($loggedInUser->checkPermission(array(2))){
?>
	<ul class="list-group">
		<li class="list-group-item"><a href="admin_users.php"><i class="fa fa-fw fa-users"></i> Manage Users</a></li>
	</ul>

	<ul class="list-group">
		<li class="list-group-item"><a href="admin_configuration.php"><i class="fa fa-fw fa-wrench"></i> Admin Configuration</a></li>
		<li class="list-group-item"><a href="admin_permissions.php"><i class="fa fa-fw fa-code"></i> Admin Permissions</a></li>
		<li class="list-group-item"> <a href="admin_pages.php"><i class="fa fa-fw fa-newspaper-o"></i> Admin Pages</a></li>
	</ul>

        <?php
          }
  }

        //liks for users not logged in
        else {
        ?>

	 <ul class="list-group">
		<li class="list-group-item"><a href="forgot-password.php"><i class="fa fa-fw fa-wrench"></i> Forgot Password</a></li>
	</ul>
			<?php
    if ($emailActivation)
    {
	?>
	 <ul class="list-group">
		<li class="list-group-item"><a href="resend-activation.php"> Resend Activation Email</a></li>
	</ul>
     <?php
    }

  }
  ?>
