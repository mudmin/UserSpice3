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
<?php if(!empty($_POST))
{
	$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
	$cfgId = array();
	$newSettings = $_POST['settings'];

	//Validate new site name
	if ($newSettings[1] != $websiteName) {
		$newWebsiteName = $newSettings[1];
		if(minMaxRange(1,150,$newWebsiteName))
		{
			$errors[] = lang("CONFIG_NAME_CHAR_LIMIT",array(1,150));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 1;
			$cfgValue[1] = $newWebsiteName;
			$websiteName = $newWebsiteName;
		}
	}

	//Validate new URL
	if ($newSettings[2] != $websiteUrl) {
		$newWebsiteUrl = $newSettings[2];
		if(minMaxRange(1,150,$newWebsiteUrl))
		{
			$errors[] = lang("CONFIG_URL_CHAR_LIMIT",array(1,150));
		}
		else if (substr($newWebsiteUrl, -1) != "/"){
			$errors[] = lang("CONFIG_INVALID_URL_END");
		}
		else if (count($errors) == 0) {
			$cfgId[] = 2;
			$cfgValue[2] = $newWebsiteUrl;
			$websiteUrl = $newWebsiteUrl;
		}
	}

	//Validate new site email address
	if ($newSettings[3] != $emailAddress) {
		$newEmail = $newSettings[3];
		if(minMaxRange(1,150,$newEmail))
		{
			$errors[] = lang("CONFIG_EMAIL_CHAR_LIMIT",array(1,150));
		}
		elseif(!isValidEmail($newEmail))
		{
			$errors[] = lang("CONFIG_EMAIL_INVALID");
		}
		else if (count($errors) == 0) {
			$cfgId[] = 3;
			$cfgValue[3] = $newEmail;
			$emailAddress = $newEmail;
		}
	}

	//Validate email activation selection
	if ($newSettings[4] != $emailActivation) {
		$newActivation = $newSettings[4];
		if($newActivation != "true" AND $newActivation != "false")
		{
			$errors[] = lang("CONFIG_ACTIVATION_TRUE_FALSE");
		}
		else if (count($errors) == 0) {
			$cfgId[] = 4;
			$cfgValue[4] = $newActivation;
			$emailActivation = $newActivation;
		}
	}

	//Validate new email activation resend threshold
	if ($newSettings[5] != $resend_activation_threshold) {
		$newResend_activation_threshold = $newSettings[5];
		if($newResend_activation_threshold > 72 OR $newResend_activation_threshold < 0)
		{
			$errors[] = lang("CONFIG_ACTIVATION_RESEND_RANGE",array(0,72));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 5;
			$cfgValue[5] = $newResend_activation_threshold;
			$resend_activation_threshold = $newResend_activation_threshold;
		}
	}

	//Validate new language selection
	if ($newSettings[6] != $language) {
		$newLanguage = $newSettings[6];
		if(minMaxRange(1,150,$language))
		{
			$errors[] = lang("CONFIG_LANGUAGE_CHAR_LIMIT",array(1,150));
		}
		elseif (!file_exists($newLanguage)) {
			$errors[] = lang("CONFIG_LANGUAGE_INVALID",array($newLanguage));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 6;
			$cfgValue[6] = $newLanguage;
			$language = $newLanguage;
		}
	}

	//Validate new template selection
	if ($newSettings[7] != $template) {
		$newTemplate = $newSettings[7];
		if(minMaxRange(1,150,$template))
		{
			$errors[] = lang("CONFIG_TEMPLATE_CHAR_LIMIT",array(1,150));
		}
		elseif (!file_exists($newTemplate)) {
			$errors[] = lang("CONFIG_TEMPLATE_INVALID",array($newTemplate));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 7;
			$cfgValue[7] = $newTemplate;
			$template = $newTemplate;
		}
	}

	//Update configuration table with new settings
	if (count($errors) == 0 AND count($cfgId) > 0) {
		updateConfig($cfgId, $cfgValue);
		$successes[] = lang("CONFIG_UPDATE_SUCCESSFUL");
	}
}

$languages = getLanguageFiles(); //Retrieve list of language files
$templates = getTemplateFiles(); //Retrieve list of template files
$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels
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
        <?php resultBlock($errors,$successes); ?>
  <!-- Page Heading -->
	<div class="row">
		<div class="col-lg-6">
			<h1 class="page-header">
				Admin Configuration
			</h1>
			<!-- CONTENT GOES HERE -->
			<?php
			echo resultBlock($errors,$successes);

			echo "
			<form name='adminConfiguration' action='".$_SERVER['PHP_SELF']."' method='post'>
			<p>
			<label>Website Name:</label>
			<input class='form-control' type='text' name='settings[".$settings['website_name']['id']."]' value='".$websiteName."' />
			</p>
			<p>
			<label>Website URL: MUST end with a /</label>
			<input type='text' class='form-control' name='settings[".$settings['website_url']['id']."]' value='".$websiteUrl."' />
			</p>
			<p>
			<label>Email:</label>
			<input class='form-control'  type='text' name='settings[".$settings['email']['id']."]' value='".$emailAddress."' />
			</p>
			<p>
			<label>Activation Threshold:</label>
			<input class='form-control'  type='text' name='settings[".$settings['resend_activation_threshold']['id']."]' value='".$resend_activation_threshold."' />
			</p>
			<p>
			<label>Language:</label>
			<select class='form-control'  name='settings[".$settings['language']['id']."]'>";

			//Display language options
			foreach ($languages as $optLang){
				if ($optLang == $language){
					echo "<option value='".$optLang."' selected>$optLang</option>";
				}
				else {
					echo "<option value='".$optLang."'>$optLang</option>";
				}
			}

			echo "
			</select>
			</p>
			<p>
			<label>Email Activation:</label>
			<select class='form-control'  name='settings[".$settings['activation']['id']."]'>";

			//Display email activation options
			if ($emailActivation == "true"){
				echo "
				<option value='true' selected>True</option>
				<option value='false'>False</option>
				</select>";
			}
			else {
				echo "
				<option value='true'>True</option>
				<option value='false' selected>False</option>
				</select>";
			}

			echo "</p>
			<p>
			<label>Template:</label>
			<select class='form-control'  name='settings[".$settings['template']['id']."]'>";

			//Display template options
			foreach ($templates as $temp){
				if ($temp == $template){
					echo "<option value='".$temp."' selected>$temp</option>";
				}
				else {
					echo "<option value='".$temp."'>$temp</option>";
				}
			}
			?>

		</select>
	</p>
	<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
	<input  class="btn btn-primary" type="submit" name="Submit" value="Update Configuration" />
	<a href="account.php" class="btn btn-danger">Cancel</a>
</form>


	 </div> <!-- /col -->

  
</div> <!-- /row -->



<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

      </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
