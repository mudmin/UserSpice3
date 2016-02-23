<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com

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

<!-- If you are going to include the sidebar, do it here -->
<?php //require_once("models/left-nav.php"); ?>
</div>
<!-- /.navbar-collapse -->
</nav>
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

	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-3"></div>
		<div class="col-lg-6">
			<h1 class="page-header">
				Login
			</h1>
			<!-- CONTENT GOES HERE -->

			<?php
			echo resultBlock($errors,$successes);
			echo "
			<div id='regbox'>
			<form name='login' action='".$_SERVER['PHP_SELF']."' method='post'>
			<p>
			";
			?>
			<label>Username:</label>
			<input  class='form-control' type='text' name='username' />
		</p>
		<p>
			<label>Password:</label>
			<input  class='form-control'  type='password' name='password' />
		</p>
		<p><label>Please enter the words as they appear:</label>
			<div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
		</p>
		<p>
			<label>&nbsp;</label>
			<input class='btn btn-primary' type='submit' value='Login' class='submit' />
		</p>
		<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
	</form>
</div>
</div>
<!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
