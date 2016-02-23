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
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
require_once("models/config.php");

require_once("models/recaptcha.config.php"); //include reCAPTCHA keys

if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>

<!-- PHP GOES HERE -->
<?php
//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Forms posted
if(!empty($_POST))
{
  $errors = array();
  $email = trim($_POST["email"]);
  $username = trim($_POST["username"]);
  $displayname = trim($_POST["displayname"]);
  $password = trim($_POST["password"]);
  $confirm_pass = trim($_POST["passwordc"]);

  //reCAPTCHA 2.0 check
  // empty response
  $response = null;

  // check secret key
  $reCaptcha = new ReCaptcha($privatekey);

  // if submitted check response
  if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
    $_SERVER["REMOTE_ADDR"],
    $_POST["g-recaptcha-response"]
  );
}
if ($response != null && $response->success) {

  if(minMaxRange(5,25,$username))
  {
    $errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
  }
  if(!ctype_alnum($username)){
    $errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
  }
  if(minMaxRange(5,25,$displayname))
  {
    $errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
  }
  if(!ctype_alnum($displayname)){
    $errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
  }
  if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
  {
    $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
  }
  else if($password != $confirm_pass)
  {
    $errors[] = lang("ACCOUNT_PASS_MISMATCH");
  }
  if(!isValidEmail($email))
  {
    $errors[] = lang("ACCOUNT_INVALID_EMAIL");
  }

  //End data validation
  if(count($errors) == 0)
  {
    //Construct a user object
    $user = new User($username,$displayname,$password,$email);

    //Checking this flag tells us whether there were any errors such as possible data duplication occured
    if(!$user->status)
    {
      if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
      if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
      if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));
    }
    else
    {
      //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
      if(!$user->userCakeAddUser())
      {
        if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
        if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
      }
    }
  }
  if(count($errors) == 0) {
    $successes[] = $user->success;
  }
}
}
?>

<div class="container">

	 <?php 	echo resultBlock($errors,$successes); ?>

	<div class="form-signup">
		<form class="" name="newUser" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<h2 class="form-signin-heading"><i class="fa fa-flask"></i> <?php echo lang("SIGNUP_TEXT","");?></h2>

			<div class="form-group">
				<label for="username">Choose a Username</label>
				<input  class="form-control" type="text" name="username" id="username" placeholder="Username" required autofocus>
				<p class="help-block">No Spaces or Special Characters - Min 5 characters</p>
			</div>

			<div class="form-group">
				<label for="displayname">Choose Display Name</label>
				<input  class="form-control" type="text" name="displayname" id="displayname" placeholder="Display Name" required >
				<p class="help-block">No Spaces or Special Characters - Min 5 characters</p>
			</div>

			<div class="form-group">
				<label for="password">Choose a Password</label>
				<input  class="form-control" type="password" name="password" id="password" placeholder="Password" required >
				<p class="help-block">Password (Min 8 Characters)</p>
			</div>

			<div class="form-group">
				<label for="passwordc">Confirm Password</label>
				<input  class="form-control" type="password" name="passwordc" id="passwordc" placeholder="Confirm Password" required >
			</div>

			<div class="form-group">
				<label for="email">Email Address</label>
				<input  class="form-control" type="text" name="email" id="email" placeholder="Email Address" required >
			</div>

			<div class="form-group">
				<div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
			</div>

			<button class="submit  btn btn-lg btn-primary btn-block" type="submit"><i class="fa fa-plus-square"></i> <?php echo lang("SIGNUP_BUTTONTEXT","");?></button>
			<input type="hidden" name="csrf" value="<?=Token::generate();?>" >

		</form>


	  </div>
</div> <!-- /container -->


<!-- footers -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
