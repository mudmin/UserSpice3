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

// config is required everywhere, it handles all the includes and setup required for each page
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

if(isUserLoggedIn())
	{
	// Now we have a secure page, we can go ahead and define some variable and get some data!
	// Create vars
	$display_activitytitle = '<h2>Sample Data</h2>';
	$display_activitydata = '';
	$blank_button = '<a class="btn btn-success btn-lg" href="account.php" role="button"><i class="fa fa-flask"></i> '.lang("SIGNIN_AUDITTEXT","").'</a>';

	// $loggedInUser is an object representing the current user. For procedural people, bear with it.
	// With the id of the current user, we can get data from all over the system.
	$get_info_id = $loggedInUser->user_id;

	// Access the $loggedInUser user object again, get the text field that contains the title/user group
	$groupname = ucfirst($loggedInUser->title);

	// Again, data in the user object, get the time they signed up and format with php
	$signupdate = date("D jS M Y G:i:s",$loggedInUser->signupTimeStamp());

	// You can user the helper function ago() to get a contemporary 'time ago' format too
	$signupago = ago($loggedInUser->signupTimeStamp());
	$signupago_display = 'You signed up on '.$signupdate.' ('.$signupago.' ago)';

	// A note on dates and times in UserSpice.
	// All timestamps are in UNIX time() format (the number of seconds since January 1 1970 00:00:00 GMT
	// This provides enormous flexibility in calculating ranges of dates and formatting.
	// Note that javascript (particularly flot graph data) is measured in milliseconds from January 1 1970 00:00:00 GMT
	// For javascript, multiply your timestamps by 1000

	// Now we use the id of the user to get full details from the users table.
	$userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details

	// Get an array of data from the audit table, loop through it and build a HTML string for display later  
	$display_activitydata = '';
	$activityData = fetchUserAudit($get_info_id); 
	//Cycle through activity data
	  foreach ($activityData as $v1)
			{
			$accuserid = ($v1['audit_userid'] == 666) ? 0 : $v1['audit_userid']; // do something with baddies
			$accagodate = ago($v1['audit_timestamp']);
			$accaudate = date("d/M/Y G:i:s",$v1['audit_timestamp']);
			$adisp_rowc = ($v1['audit_eventcode'] == 3) ? "alert alert-danger" : '';
			$display_activitydata .= '<p class="'.$adisp_rowc.'">'.$v1['audit_action'].'</p>';
			}
	} 
else // user is not logged in
	{
	$display_activitytitle = lang("SIGNIN_TITLE","").' or '.lang("SIGNUP_TEXT","");
	$display_activitydata = '';
	$signupago_display = 'No '.lang("SIGNIN_TEXT","").' Data';
	$blank_button = '<a class="btn btn-warning btn-lg" href="login.php" role="button"><i class="fa fa-flask"></i> Not '.lang("SIGNUP_AUDITTEXT","").'</a>';
	}
	
// Start to construct the page - top-nav includes all the html <head> elements as well as the markup for the top bar navigation.
require_once("models/top-nav.php"); 		
?>

<!-- 'container-fluid' is full width, use 'container' for default Bootstrap width -->	
<div class="container">

	<div class="jumbotron text-center">
			<h1>UserSpice Blank Page</h1>
			<p><?php echo $blank_button;?></p>
			<p><?php echo $signupago_display;?></p>
		  </div>

	<div class="well text-center">
			<?php echo $display_activitytitle;?>
			<?php echo $display_activitydata;?>
	</div>

</div><!--/.container-->

<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>


<!-- Place any per-page javascript here -->
<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
