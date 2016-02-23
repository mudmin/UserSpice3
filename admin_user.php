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
    <div class="col-md-12">
      <h1 >
        Edit User
      </h1>
      <!-- CONTENT GOES HERE -->
	<form name="adminUser" action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $userId;?>" method="post">
	
		<div class="row">
				<div class="col-md-6">
				 <h3>User Information</h3>
					<div class="form-group">
					  <label>ID:</label>
					  <?php echo $userdetails['id'];?>
					  </div>
					<div class="form-group">
					  <label>Username:</label>
					  <?php echo $userdetails['user_name'];?>
					  </div>
					<div class="form-group row">
						<div class="col-md-9">
						  <label for="display">Display Name:</label>
						  <input  class="form-control" type="text" name="display" id="display" value="<?php echo $userdetails['display_name'];?>" />
						  </div>
						 </div>
					<div class="form-group row">
						<div class="col-md-9">
						  <label for="display">Email:</label>
						  <input  class="form-control" type="text" name="email" id="email" value="<?php echo $userdetails['email'];?>" />
						  </div>
						</div>
					<div class="form-group">
					  <label>Active:</label>
						<?php //Display activation link, if account inactive
						if ($userdetails['active'] == '1'){
						echo "Yes";
						}
					  else{
						echo 'No
						<label for="activate" >Activate:</label>
						<input type="checkbox" name="activate" id="activate" value="activate">
						';
					  }
					  ?>	
					  </div>
					  
					<div class="form-group row">
						<div class="col-md-9">
						  <label for="display">Title:</label>
						  <input  class="form-control" type="text" name="title" id="title" value="<?php echo $userdetails['title'];?>" />
						  </div>
						</div>

					<div class="form-group">
					  <label for="display">Sign Up:</label>
					  <?php echo date("j M, Y", $userdetails['sign_up_stamp']);?>
					  </div>
					  
					<div class="form-group">
					  <label for="display">Last Sign In:</label>
					  <?php 
					  //Last sign in, interpretation
					  if ($userdetails['last_sign_in_stamp'] == '0'){
						echo "Never";
					  }
					  else {
						echo date("j M, Y", $userdetails['last_sign_in_stamp']);
					  }
					  ?>
					  </div>


					<div class="form-group">
					  <label>Delete (No warning):</label>
					  <input type="checkbox" name="delete[<?php echo $userdetails['id'];?>]" id="delete[<?php echo $userdetails['id'];?>]" value="<?php echo $userdetails['id'];?>">
					  </div>
					  
				</div><!-- /col -->
				
				<div class="col-md-6">
				<h3>Permission Membership</h3>
				
					<div class="form-group">
					  <label>Remove Permission:</label>
								<ul>
					        <?php //List of permission levels user is apart of
						  foreach ($permissionData as $v1) {
							if(isset($userPermission[$v1['id']])){
							  echo "<li><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</li>";
							}
						  }
						  ?>
							</ul>			  
					 </div>

					<div class="form-group">
					  <label>Add Permission:</label>
								<ul>
					        <?php //List of permission levels user is apart of
								  foreach ($permissionData as $v1) {
								if(!isset($userPermission[$v1['id']])){
								  echo "<li><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</li>";
							}
						  }
						  ?>
							</ul>			  
					 </div>					 
				
				</div><!-- /col -->
				
		</div><!-- /col-md-4 -->

			<div class="row">
		<div class="col-md-12">
		  <input type="hidden" name="csrf" value="<?=Token::generate();?>" >

		<div class="form-group">
		  <label>&nbsp;</label>
		  <input class='btn btn-primary' type='submit' value='Update User' />
		  <a href='admin_users.php' class='btn btn-danger'>Cancel</a>
		  </div>
		</div>
	</div>
</form>
		
	 </div> <!-- /col -->

</div> <!-- /row -->




<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>


      </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->

<script type="text/javascript">
$(document).ready(function(){  
 
 $( "#Submit" ).click(function(e) {
 
	 	var answer = confirm("Delete User - Are you sure?")
		if (answer)
			{
			return true;
			}
		else	
			{
			return false;
			}
		});
       
    });
</script>

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
