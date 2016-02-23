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
//Forms posted
if(!empty($_POST) && $emailActivation)
{
	$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
	$email = $_POST["email"];
	$username = $_POST["username"];

	//Perform some validation
	//Feel free to edit / change as required
	if(trim($email) == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
	}
	//Check to ensure email is in the correct format / in the db
	else if(!isValidEmail($email) || !emailExists($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}

	if(trim($username) == "")
	{
		$errors[] =  lang("ACCOUNT_SPECIFY_USERNAME");
	}
	else if(!usernameExists($username))
	{
		$errors[] = lang("ACCOUNT_INVALID_USERNAME");
	}

	if(count($errors) == 0)
	{
		//Check that the username / email are associated to the same account
		if(!emailUsernameLinked($email,$username))
		{
			$errors[] = lang("ACCOUNT_USER_OR_EMAIL_INVALID");
		}
		else
		{
			$userdetails = fetchUserDetails($username);

			//See if the user's account is activation
			if($userdetails["active"]==1)
			{
				$errors[] = lang("ACCOUNT_ALREADY_ACTIVE");
			}
			else
			{
				if ($resend_activation_threshold == 0) {
					$hours_diff = 0;
				}
				else {
					$last_request = $userdetails["last_activation_request"];
					$hours_diff = round((time()-$last_request) / (3600*$resend_activation_threshold),0);
				}

				if($resend_activation_threshold!=0 && $hours_diff <= $resend_activation_threshold)
				{
					$errors[] = lang("ACCOUNT_LINK_ALREADY_SENT",array($resend_activation_threshold));
				}
				else
				{
					//For security create a new activation url;
					$new_activation_token = generateActivationToken();

					if(!updateLastActivationRequest($new_activation_token,$username,$email))
					{
						$errors[] = lang("SQL_ERROR");
					}
					else
					{
						$mail = new userCakeMail();

						$activation_url = $websiteUrl."activate-account.php?token=".$new_activation_token;

						//Setup our custom hooks
						$hooks = array(
							"searchStrs" => array("#ACTIVATION-URL","#USERNAME#"),
							"subjectStrs" => array($activation_url,$userdetails["display_name"])
						);

						if(!$mail->newTemplateMsg("resend-activation.txt",$hooks))
						{
							$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
						}
						else
						{
							if(!$mail->sendMail($userdetails["email"],"Activate your ".$websiteName." Account"))
							{
								$errors[] = lang("MAIL_ERROR");
							}
							else
							{
								//Success, user details have been updated in the db now mail this information out.
								$successes[] = lang("ACCOUNT_NEW_ACTIVATION_SENT");
							}
						}
					}
				}
			}
		}
	}
}

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

?>

<div class="container-fluid">
<?php  echo resultBlock($errors,$successes); ?>

	<div class="form-signin">

	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				Resend Activation
			</h1>
			<!-- CONTENT GOES HERE -->

			<?php
			//Show disabled if email activation not required
			if(!$emailActivation)
			{
				echo lang("FEATURE_DISABLED");
			}
			else
			{
			?>
			<form name="resendActivation" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			  <div class="form-group">		
				<label for="username">Username:</label>
				<input class="form-control" type="text" name="username" id="username" />
				</div>
				
				 <div class="form-group">		
				<label for="email">Email:</label>
				<input class="form-control" type="text" name="email" id="email" />
				</div>
				
				<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
	
				 <div class="form-group">		
				<label>&nbsp;</label>
				<input class="btn btn-primary" type="submit" value="Resend" />
				</div>
				</form>
			<?php
			}
			?>


			</div>
		</div>
		<!-- /.row -->
	</div><!-- /.siognin -->
</div>
<!-- /.container-fluid -->


<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
