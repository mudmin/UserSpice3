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
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>
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

<div class="container-fluid" style="">
  


    
      <div class="row row-offcanvas row-offcanvas-left">
        
         <div class="col-sm-6 col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
				<p class="visible-xs">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvas"><i class="fa fa-fw fa-caret-square-o-left"></i></button>
              </p>
	
	<?php require_once("models/left-nav.php"); ?>

   </div><!--/span-->
        
        <div class="col-sm-6 col-md-9 col-lg-10 main">
  

 
          <!--toggle sidebar button-->
          <p class="visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="fa fa-fw fa-caret-square-o-left"></i></button>
          </p>
 
	 <?php
//Links for permission level 3
if ($loggedInUser->checkPermission(array(3))){
?>

 <?php } ?>
 	 <?php
//Links for permission level 4
if ($loggedInUser->checkPermission(array(4))){
?>

 <?php } ?>
	 <?php
//Links for permission level 2 (default admin)
if ($loggedInUser->checkPermission(array(1))){
?>

	<h1>User Settings</h1>	
						
	<div class="row ">  
	<div class="col-sm-12 col-md-3">
		
   <?php
echo resultBlock($errors,$successes);
?>
<form name="updateAccount" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">


	<div class="form-group">
		<label for="password">Enter your current password:</label>
		<input class="form-control" type="password" name="password" id="password" />
	</div>

	<div class="form-group">
		<label for="email">Email:</label>
		<input class="form-control" type="text" name="email" id="email" value="<?php echo $loggedInUser->email;?>" />
	</div>	

	<div class="form-group">
		<label for="passwordc">New Password:</label>
		<input class="form-control" type="password" name="passwordc" id="passwordc" />
		<p class="help-block">8 character minimum</p>
		
		<label for="passwordcheck">Confirm Password:</label>
		<input class="form-control" type="password" name="passwordcheck" id="passwordcheck" />
		<p class="help-block">8 character minimum</p>
		
	</div>	
	
	<div class="form-group">
		<input class="btn btn-primary" type="submit" value="Update"  />
		<a href='account.php' class='btn btn-danger'>Cancel</a>
	</div>

	<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
</form>


		</div> <!-- /col -->

	</div> <!-- /row -->
<?php } ?>




<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>
          
      </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
