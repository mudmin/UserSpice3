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
  <div class="col-lg-3"></div>
  <div class="col-lg-6">
    <h1 class="page-header">
      Register
    </h1>
    <!-- CONTENT GOES HERE -->


    <?php
    echo resultBlock($errors,$successes);

    echo "
    <div id='regbox'>
    <form name='newUser' action='register.php' method='post'>

    <p>
    <label>User Name (No Spaces or Special Characters - Min 5 characters):</label>
    <input class='form-control' type='text' name='username' />
    </p>
    <p>
    <label>Display Name (No Spaces or Special Characters - Min 5 characters):</label>
    <input class='form-control' type='text' name='displayname' />
    </p>
    <p>
    <label>Password (Min 8 Characters):</label>
    <input class='form-control' type='password' name='password' />
    </p>
    <p>
    <label>Confirm Password:</label>
    <input class='form-control' type='password' name='passwordc' />
    </p>
    <p>
    <label>Email:</label>
    <input class='form-control' type='text' name='email' />
    </p>
    <p>
    <p><label>Please enter the words as they appear:</label>"; ?>

      <div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>

      <p>
        <label>&nbsp;<br>
          <input class='btn btn-primary' type='submit' value='Register'/>
        </p>

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
<!-- footers -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
