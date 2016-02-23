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
$pageId = $_GET['id'];

//Check if selected pages exist
if(!pageIdExists($pageId)){
  header("Location: admin_pages.php"); die();
}

$pageDetails = fetchPageDetails($pageId); //Fetch information specific to page

//Forms posted
if(!empty($_POST)){
  $token = $_POST['csrf'];
  if(!Token::check($token)){
    die('Token doesn\'t match!');
  }
  $update = 0;

  if(!empty($_POST['private'])){ $private = $_POST['private']; }

  //Toggle private page setting
  if (isset($private) AND $private == 'Yes'){
    if ($pageDetails['private'] == 0){
      if (updatePrivate($pageId, 1)){
        $successes[] = lang("PAGE_PRIVATE_TOGGLED", array("private"));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
  }
  elseif ($pageDetails['private'] == 1){
    if (updatePrivate($pageId, 0)){
      $successes[] = lang("PAGE_PRIVATE_TOGGLED", array("public"));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }

  //Remove permission level(s) access to page
  if(!empty($_POST['removePermission'])){
    $remove = $_POST['removePermission'];
    if ($deletion_count = removePage($pageId, $remove)){
      $successes[] = lang("PAGE_ACCESS_REMOVED", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }

  }

  //Add permission level(s) access to page
  if(!empty($_POST['addPermission'])){
    $add = $_POST['addPermission'];
    if ($addition_count = addPage($pageId, $add)){
      $successes[] = lang("PAGE_ACCESS_ADDED", array($addition_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }

  $pageDetails = fetchPageDetails($pageId);
}

$pagePermissions = fetchPagePermissions($pageId);
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
      <h1>Admin Page</h1>
      <!-- CONTENT GOES HERE -->

			  <form name="adminPage" action="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $pageId; ?>" method="post">
			  <input type="hidden" name="process" value="1">

	<div class="row">
		<div class="col-md-6">
			  <h3>Page Information</h3>

			<div class="form-group">
			  <label>ID:</label>
			  <?php echo $pageDetails['id'];?>
			  </div>

				<div class="form-group">
			  <label>Name:</label>
			  <?php echo $pageDetails['page'];?>
			  </div>

			 <div class="form-group">
			  <label for="private">Private:</label>

			 <?php //Display private checkbox
				if ($pageDetails['private'] == 1){
				echo '<input type="checkbox" name="private" id="private" value="Yes" checked>';
			  }
			  else {
				echo '<input type="checkbox" name="private" id="private" value="Yes" >';
			  }
			  ?>
			  </div>
		 </div> <!-- /col -->

		<div class="col-md-6">
				<h3>Page Access</h3>

				<div class="form-group">
			  <label>Remove Access:</label>
			<ul>
			<?php //Display list of permission levels with access
			  foreach ($permissionData as $v1) {
				if(isset($pagePermissions[$v1['id']])){
				  echo "<li><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</li>";
				}
			  }
				?>
				</ul>
			</div>

			<div class="form-group">
			<label>Add Access:</label>
			<ul>
			<?php
			  //Display list of permission levels without access
			  foreach ($permissionData as $v1) {
				if(!isset($pagePermissions[$v1['id']])){
				  echo "<li><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</li>";
				}
			  }
			  ?>
			  </ul>
			</div>

		 </div> <!-- /col -->
	</div> <!-- /row -->


		<div class="row">
			<div class="col-md-12">
				<input type="hidden" name="csrf" value="<?=Token::generate();?>" >

				<div class="form-group">
				  <label>&nbsp;</label>
					<input class='btn btn-primary' type='submit' value='Update Page' />
					<a href='admin_pages.php' class='btn btn-danger'>Cancel</a>
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
