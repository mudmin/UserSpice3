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

	<div class="list-group">
		<a class="list-group-item" href="index.php"><i class="fa fa-fw fa-home"></i> Home</a>
	</div>

	<div class="list-group">
		<a  class="list-group-item" href="account.php"><i class="fa fa-fw fa-user"></i> User Account</a>
		<a  class="list-group-item" href="user_settings.php"><i class="fa fa-fw fa-pencil-square-o"></i> User Settings</a>
		<a  class="list-group-item" href="logout.php"><i class="fa fa-fw fa-sign-out"></i> <?php echo lang("SIGNOUT_TEXT","");?></a>
	</div>


<?php
  //tdnks for permission level 2 (default admin)
  if ($loggedInUser->checkPermission(array(2))){
?>
	<div class="list-group">
		<a  class="list-group-item" href="admin_users.php"><i class="fa fa-fw fa-users"></i> Manage Users</a>
	</div>

	<div class="list-group">
		<a  class="list-group-item" href="admin_dashboard.php"><i class="fa fa-fw fa-cogs"></i> Admin Dashboard</a>
		<a  class="list-group-item" href="admin_configuration.php"><i class="fa fa-fw fa-wrench"></i> Admin Configuration</a>
		<a  class="list-group-item" href="admin_permissions.php"><i class="fa fa-fw fa-code"></i> Admin Permissions</a>
		 <a  class="list-group-item" href="admin_pages.php"><i class="fa fa-fw fa-newspaper-o"></i> Admin Pages</a>
	</div>

        <?php
          }
  }

        //liks for users not logged in
        else {
        ?>
		

	 <div class="list-group">
	<a class="list-group-item"  href="login.php"> <i class="fa fa-sign-in"></i> <?php echo lang("SIGNIN_TEXT","");?></a>
	<a class="list-group-item"  href="register.php"><i class="fa fa-plus-square"></i> <?php echo lang("SIGNUP_TEXT","");?></a>
	</div>		
		
	 <div class="list-group">
	 <a  class="list-group-item" href="public.php"><i class="fa fa-fw fa-flask"></i> Blank Page</a>
	</div>	
	 <div class="list-group">
	 <a  class="list-group-item" href="forgot-password.php"><i class="fa fa-fw fa-wrench"></i> Forgot Password</a>

			<?php
    if ($emailActivation)
    {
	?>

		<a  class="list-group-item" href="resend-activation.php"><i class="fa fa-exclamation-triangle"></i> Resend Activation Email</a>
	</div>
     <?php
    }

  }
  ?>
