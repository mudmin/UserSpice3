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
$permissionId = $_GET['id'];

//Check if selected permission level exists
if(!permissionIdExists($permissionId)){
  header("Location: admin_permissions.php"); die();
}

$permissionDetails = fetchPermissionDetails($permissionId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
  $token = $_POST['csrf'];
  if(!Token::check($token)){
    die('Token doesn\'t match!');
  }
  //Delete selected permission level
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
      $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }
  else
  {
    //Update permission level name
    if($permissionDetails['name'] != $_POST['name']) {
      $permission = trim($_POST['name']);

      //Validate new name
      if (permissionNameExists($permission)){
        $errors[] = lang("ACCOUNT_PERMISSIONNAME_IN_USE", array($permission));
      }
      elseif (minMaxRange(1, 50, $permission)){
        $errors[] = lang("ACCOUNT_PERMISSION_CHAR_LIMIT", array(1, 50));
      }
      else {
        if (updatePermissionName($permissionId, $permission)){
          $successes[] = lang("PERMISSION_NAME_UPDATE", array($permission));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }

    //Remove access to pages
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($permissionId, $remove)) {
        $successes[] = lang("PERMISSION_REMOVE_USERS", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Add access to pages
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($permissionId, $add)) {
        $successes[] = lang("PERMISSION_ADD_USERS", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Remove access to pages
    if(!empty($_POST['removePage'])){
      $remove = $_POST['removePage'];
      if ($deletion_count = removePage($remove, $permissionId)) {
        $successes[] = lang("PERMISSION_REMOVE_PAGES", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Add access to pages
    if(!empty($_POST['addPage'])){
      $add = $_POST['addPage'];
      if ($addition_count = addPage($add, $permissionId)) {
        $successes[] = lang("PERMISSION_ADD_PAGES", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    $permissionDetails = fetchPermissionDetails($permissionId);
  }
}

$pagePermissions = fetchPermissionPages($permissionId); //Retrieve list of accessible pages
$permissionUsers = fetchPermissionUsers($permissionId); //Retrieve list of users with membership
$userData = fetchAllUsers(); //Fetch all users
$pageData = fetchAllPages(); //Fetch all pages

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
      <h1>
        Admin Permissions
      </h1>
      <!-- CONTENT GOES HERE -->
<form name="adminPermission" action="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $permissionId;?>" method="post">
	<div class="row">
		<div class="col-md-4">
		 <h3>Permission Information</h3>

			<div class="form-group">
			  <label>ID:</label>
			  <?php echo $permissionDetails['id'];?>
			  </div>
			  
			<div class="form-group">
			  <label>Name:</label>
			  <input type="text" name="name" value="<?php echo $permissionDetails['name']; ?>" />
			  </div>
			  
				<div class="form-group">
			  <label>Delete:</label>
			  <input type="checkbox" name="delete[<?php echo $permissionDetails['id'];?>]" id="delete[<?php echo $permissionDetails['id'];?>]" value="<?php echo $permissionDetails['id'];?>">
			  </div>

		</div><!-- /col -->

		<div class="col-md-4">
			 <h3>Permission Membership</h3>
			 
			<div class="form-group">
			   <label>Remove Members: </label>
				<ul>
				<?php   //List users with permission level
				  foreach ($userData as $v1) {
					if(isset($permissionUsers[$v1['id']])){
					  echo "<li><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name']."</li>";
					}
				  }
				  ?>
				  </ul>
			  </div>
			 
			 
			<div class="form-group">
			<label>Add Members:</label>
				<ul>
				<?php //List users without permission level
			  foreach ($userData as $v1) {
				if(!isset($permissionUsers[$v1['id']])){
				  echo "<li><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name']."</li>";
				}
			  }
				?>
				</ul>
			</div>
			 
		</div><!-- /col -->

		<div class="col-md-4">

		  <h3>Permission Access</h3>

				<div class="form-group">
				  <label>Public Access:</label>
						<ul>
							  <?php //List public pages
							  foreach ($pageData as $v1) {
								if($v1['private'] != 1){
								  echo "<li>".$v1['page']."</li>";
								}
							  }

						?>
							</ul>
				  </div>
	  
	  
				<div class="form-group">
				  <label>Remove Access:</label>
						<ul>
							  <?php //List pages accessible to permission level
							  foreach ($pageData as $v1) {
								if(isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
								  echo "<li><input type='checkbox' name='removePage[".$v1['id']."]' id='removePage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page']."</li>";
								}
							  }

						?>
							</ul>  
			  </div>
	  
				<div class="form-group">
			  <label>Add Access:</label>
					<ul>
						  <?php //List pages inaccessible to permission level
						  foreach ($pageData as $v1) {
							if(!isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
							  echo "<li><input type='checkbox' name='addPage[".$v1['id']."]' id='addPage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page']."</li>";
							}
						  }

					?>
						</ul>  
		  </div>
		
		
		
		</div><!-- /col-md-4 -->

	</div> <!-- /row -->
	
	<div class="row">
	<div class="col-md-12">
	   <input type="hidden" name="csrf" value="<?=Token::generate();?>" >

	<div class="form-group">
      <label>&nbsp;</label>
      <input class='btn btn-primary' type='submit' value='Update Permission' />
	  <a href='admin_permissions.php' class='btn btn-danger'>Cancel</a>
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

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
