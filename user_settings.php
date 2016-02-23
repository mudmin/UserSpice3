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
//Prevent the user visiting the logged in page if he is not logged in
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

if(!empty($_POST))
{
  $token = $_POST['csrf'];
  if(!Token::check($token)){
    die('Token doesn\'t match!');
  }
  $errors = array();
  $successes = array();
  $password = $_POST["password"];
  $password_new = $_POST["passwordc"];
  $password_confirm = $_POST["passwordcheck"];

  $errors = array();
  $email = $_POST["email"];

  //PLEASE NOTE: Even though the code uses words like "hash" we are not doing
  //standard hashing. The entire codebase has been upgraded to bcrypt. The variables
  //have remained the same for backwards compatibility with some UserCake mods.
  //Perform some validation
  //Feel free to edit / change as required

  //Confirm the hashes match before updating a users password
  $entered_pass = password_verify($password,$loggedInUser->hash_pw);

  if (trim($password) == ""){
    $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
  }
  else if($entered_pass != $loggedInUser->hash_pw)
  {
    //No match
    $errors[] = lang("ACCOUNT_PASSWORD_INVALID");
  }
  if($email != $loggedInUser->email)
  {
    if(trim($email) == "")
    {
      $errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
    }
    else if(!isValidEmail($email))
    {
      $errors[] = lang("ACCOUNT_INVALID_EMAIL");
    }
    else if(emailExists($email))
    {
      $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
    }

    //End data validation
    if(count($errors) == 0)
    {
      $loggedInUser->updateEmail($email);
      $successes[] = lang("ACCOUNT_EMAIL_UPDATED");
    }
  }

  if ($password_new != "" OR $password_confirm != "")
  {
    if(trim($password_new) == "")
    {
      $errors[] = lang("ACCOUNT_SPECIFY_NEW_PASSWORD");
    }
    else if(trim($password_confirm) == "")
    {
      $errors[] = lang("ACCOUNT_SPECIFY_CONFIRM_PASSWORD");
    }
    else if(minMaxRange(8,50,$password_new))
    {
      $errors[] = lang("ACCOUNT_NEW_PASSWORD_LENGTH",array(8,50));
    }
    else if($password_new != $password_confirm)
    {
      $errors[] = lang("ACCOUNT_PASS_MISMATCH");
    }

    //End data validation
    if(count($errors) == 0)
    {
      //Also prevent updating if someone attempts to update with the same password
      $entered_pass_new = password_verify($password_new,$loggedInUser->hash_pw);

      if($entered_pass_new == $loggedInUser->hash_pw)
      {
        //Don't update, this fool is trying to update with the same password hahaha
        $errors[] = lang("ACCOUNT_PASSWORD_NOTHING_TO_UPDATE");
      }
      else
      {
        //This function will create the new hash and update the hash_pw property.
        $loggedInUser->updatePassword($password_new);
        $successes[] = lang("ACCOUNT_PASSWORD_UPDATED");
      }
    }
  }
  if(count($errors) == 0 AND count($successes) == 0){
    $errors[] = lang("NOTHING_TO_UPDATE");
  }
}
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
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
      <h1 class="page-header">
        User Settings
        <!-- <small>Subheading</small> -->
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo resultBlock($errors,$successes);

      echo "
      <div id='regbox'>
      <form name='updateAccount' action='".$_SERVER['PHP_SELF']."' method='post'>
      <p>
      <label>Password:</label>
      <input class='form-control' type='password' name='password' />
      </p>
      <p>
      <label>Email:</label>
      <input class='form-control' type='text' name='email' value='".$loggedInUser->email."' />
      </p>
      <p>
      <label>New Password (8 character minimum):</label>
      <input class='form-control' type='password' name='passwordc' />
      </p>
      <p>
      <label>Confirm Password:</label>
      <input class='form-control' type='password' name='passwordcheck' />
      </p>
      ";
      ?>
      <input type="hidden" name="csrf" value="<?=Token::generate();?>" >
      <?php echo "
      <p>
      <label>&nbsp;</label>
      <input class='btn btn-primary' type='submit' value='Update' class='submit' />
      </p>
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
