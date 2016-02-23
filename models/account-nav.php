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
if(isUserLoggedIn()) { ?>
    <table class="table">
        <td>
            <a href="account.php"><i class="fa fa-fw fa-dashboard"></i> Account Home</a>
        </td>
        <td>
            <a href="user_settings.php"><i class="fa fa-fw fa-pencil-square-o"></i> User Settings</a>
        </td>
        <!-- <td>
            <a href="logout.php"><i class="fa fa-fw fa-angellist"></i> Logout</a>
        </li> -->


<?php
  //tdnks for permission level 2 (default admin)
  if ($loggedInUser->checkPermission(array(2))){
?>

        <td>
            <a href="admin_configuration.php"><i class="fa fa-fw fa-wrench"></i> Admin Configuration</a>
        </td>
        <td>
            <a href="admin_users.php"><i class="fa fa-fw fa-users"></i> Admin Users</a>
        </td>
        <td>
            <a href="admin_permissions.php"><i class="fa fa-fw fa-code"></i> Admin Permissions</a>
        </td>
        <td>
            <a href="admin_pages.php"><i class="fa fa-fw fa-newspaper-o"></i> Admin Pages</a>
        </td>
        <?php
          }

        //tdnks for users not logged in
        else {
        ?>

        <!-- <td>
            <a href="login.php"><i class="fa fa-fw fa-wrench"></i> Login</a>
        </td>
        <td>
            <a href="register.php"><i class="fa fa-fw fa-desktop"></i> Register</a>
        </td> -->
        <td>
            <a href="forgot-password.php"><i class="fa fa-fw fa-wrench"></i> Forgot Password</a>
        </td>
    <?php
    if ($emailActivation)
    {
    echo '
    <td class="divider"></td>
    <td><a href="resend-activation.php">Resend Activation Email</a></td>';
    }
    echo '</ul>';
  }
}
  ?>
</ul>
</table>
