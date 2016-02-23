<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com

a modern version of
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels

*/
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>

<!-- If you are going to include the sidebar, do it here -->

</div>
<!-- /.navbar-collapse -->
</nav>
<!-- PHP GOES HERE -->
<?php
$userId = $_GET['id'];

//Check if selected user exists
if(!userIdExists($userId)){
  header("Location: admin_users.php"); die();
}

$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

//Forms posted
if(!empty($_POST))
{
  //Delete selected account
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deleteUsers($deletions)) {
      $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }
  else
  {
    //Update display name
    if ($userdetails['display_name'] != $_POST['display']){
      $displayname = trim($_POST['display']);

      //Validate display name
      if(displayNameExists($displayname))
      {
        $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
      }
      elseif(minMaxRange(5,25,$displayname))
      {
        $errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
      }
      elseif(!ctype_alnum($displayname)){
        $errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
      }
      else {
        if (updateDisplayName($userId, $displayname)){
          $successes[] = lang("ACCOUNT_DISPLAYNAME_UPDATED", array($displayname));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }

    }
    else {
      $displayname = $userdetails['display_name'];
    }

    //Activate account
    if(isset($_POST['activate']) && $_POST['activate'] == "activate"){
      if (setUserActive($userdetails['activation_token'])){
        $successes[] = lang("ACCOUNT_MANUALLY_ACTIVATED", array($displayname));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Update email
    if ($userdetails['email'] != $_POST['email']){
      $email = trim($_POST["email"]);

      //Validate email
      if(!isValidEmail($email))
      {
        $errors[] = lang("ACCOUNT_INVALID_EMAIL");
      }
      elseif(emailExists($email))
      {
        $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));
      }
      else {
        if (updateEmail($userId, $email)){
          $successes[] = lang("ACCOUNT_EMAIL_UPDATED");
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }

    //Update title
    if ($userdetails['title'] != $_POST['title']){
      $title = trim($_POST['title']);

      //Validate title
      if(minMaxRange(1,50,$title))
      {
        $errors[] = lang("ACCOUNT_TITLE_CHAR_LIMIT",array(1,50));
      }
      else {
        if (updateTitle($userId, $title)){
          $successes[] = lang("ACCOUNT_TITLE_UPDATED", array ($displayname, $title));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }

    //Remove permission level
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($remove, $userId)){
        $successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array ($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($add, $userId)){
        $successes[] = lang("ACCOUNT_PERMISSION_ADDED", array ($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    $userdetails = fetchUserDetails(NULL, NULL, $userId);
  }
}

$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();

?>


<div id="page-wrapper">
  <!-- Main jumbotron for a primary marketing message or call to action -->

  <!-- <div class="jumbotron">
  <div class="container">
  <h1>Jumbotron!!!</h1>
  <p>This is a great area to highlight something.</p>
  <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
</div> -->

<div class="container-fluid">
  <?php require_once("models/account-nav.php"); ?>
  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
      <h1 >
        Edit User
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo resultBlock($errors,$successes);

      echo "
      <form name='adminUser' action='".$_SERVER['PHP_SELF']."?id=".$userId."' method='post'>
      <table class='table'><tr><td>
      <h3>User Information</h3>
      <div id='regbox'>
      <p>
      <label>ID:</label>
      ".$userdetails['id']."
      </p>
      <p>
      <label>Username:</label>
      ".$userdetails['user_name']."
      </p>
      <p>
      <label>Display Name:
      <input  class='form-control' type='text' name='display' value='".$userdetails['display_name']."' /></label>
      </p>
      <p>
      <label>Email:
      <input class='form-control' type='text' name='email' value='".$userdetails['email']."' /></label>
      </p>
      <p>
      <label>Active:</label>";

      //Display activation link, if account inactive
      if ($userdetails['active'] == '1'){
        echo "Yes";
      }
      else{
        echo "No
        </p>
        <p>
        <label>Activate:</label>
        <input type='checkbox' name='activate' id='activate' value='activate'>
        ";
      }

      echo "
      </p>
      <p>
      <label>Title:
      <input  class='form-control' type='text' name='title' value='".$userdetails['title']."' /></label>
      </p>
      <p>
      <label>Sign Up:  </label>
      ".date("j M, Y", $userdetails['sign_up_stamp'])."
      </p>
      <p>
      <label>Last Sign In:  </label>  ";

      //Last sign in, interpretation
      if ($userdetails['last_sign_in_stamp'] == '0'){
        echo "Never";
      }
      else {
        echo date("j M, Y", $userdetails['last_sign_in_stamp']);
      }

      echo "
      </p>
      <p>
      <label>Delete:</label>
      <input type='checkbox' name='delete[".$userdetails['id']."]' id='delete[".$userdetails['id']."]' value='".$userdetails['id']."'>
      </p>
      <p>
      <label>&nbsp;</label>
      <input class='btn btn-primary' type='submit' value='Update' class='submit' />
      </p>
      </div>
      </td>
      <td>
      <h3>Permission Membership</h3>
      <div id='regbox'>
      <p>Remove Permission:";

      //List of permission levels user is apart of
      foreach ($permissionData as $v1) {
        if(isset($userPermission[$v1['id']])){
          echo "<br><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
        }
      }

      //List of permission levels user is not apart of
      echo "</p><p>Add Permission:";
      foreach ($permissionData as $v1) {
        if(!isset($userPermission[$v1['id']])){
          echo "<br><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
        }
      }

      echo"
      </p>
      </div>
      </td>
      </tr>
      </table>
      ";
      ?>
      <input type="hidden" name="csrf" value="<?=Token::generate();?>" >
      <?php echo "
      </form>
      ";
      ?>






    </div>
  </div>
  <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
