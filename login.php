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
	$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
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

	$errors = array();
	$username = sanitize2(trim($_POST["username"]));
	$password = trim($_POST["password"]);

	//Perform some validation
	//Feel free to edit / change as required
	if($username == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	}
	if($password == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
	}

	//A security note here, never tell the user which credential was incorrect
	if(!usernameExists($username))
	{
		$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
	}
	else
	{
		$userdetails = fetchUserDetails($username);
		//See if the user's account is activated
		if($userdetails["active"]==0)
		{
			$errors[] = lang("ACCOUNT_INACTIVE");
		}
		else
		{
			//- THE OLD SYSTEM IS BEING REMOVED - Hash the password and use the salt from the database to compare the password.
			//$entered_pass = generateHash($password,$userdetails["password"]);
			$entered_pass = password_verify($password,$userdetails["password"]);


			if($entered_pass != $userdetails["password"])
			{

				$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID"); //MAKE UPGRADE CHANGE HERE

			}
			else
			{
				//Passwords match! we're good to go'

				//Construct a new logged in user object
				//Transfer some db data to the session object
				$loggedInUser = new loggedInUser();
				$loggedInUser->email = $userdetails["email"];
				$loggedInUser->user_id = $userdetails["id"];
				$loggedInUser->hash_pw = $userdetails["password"];
				$loggedInUser->title = $userdetails["title"];
				$loggedInUser->displayname = $userdetails["display_name"];
				$loggedInUser->username = $userdetails["user_name"];


				//Update last sign in
				$loggedInUser->updateLastSignIn();
				$_SESSION["userCakeUser"] = $loggedInUser;

				//Redirect to user account page
				header("Location: account.php");
				die();
			}
		}
	}
}
}

?>

 <div class="container">

	 <?php
	echo resultBlock($errors,$successes); ?>

	<div class="form-signin">

		<form class="" name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <h2 class="form-signin-heading">Please sign in</h2>

	  <div class="form-group">
        <label for="username">Username</label>
        <input  class="form-control" type="text" name="username" id="username" placeholder="Username" required autofocus>
	  </div>
	  <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control"  name="password" id="password"  placeholder="Password" required>
	</div>
	<div class="form-group">
       <label>Please enter the words as they appear:</label>
		<div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
	</div>

	<button class="submit  btn btn-lg btn-primary btn-block" type="submit">Login</button>
	<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
	</form>

		<div class="row">
			<div class="col-xs-6">
				<a class="pull-left" href='forgot-password.php'>Forgot Password</a>
			</div>
			<div class="col-xs-6">
				<a class="pull-right" href='register.php'>Sign Up</a>
			</div>
		</div>

	  </div>
</div> <!-- /container -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
