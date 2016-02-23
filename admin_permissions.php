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
if(!empty($_POST))
{
  $token = $_POST['csrf'];
  if(!Token::check($token)){
    die('Token doesn\'t match!');
  }
  //Delete permission levels
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
      $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
  }

  //Create new permission level
  if(!empty($_POST['newPermission'])) {
    $permission = trim($_POST['newPermission']);

    //Validate request
    if (permissionNameExists($permission)){
      $errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
    }
    elseif (minMaxRange(1, 50, $permission)){
      $errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));
    }
    else{
      if (createPermission($permission)) {
        $successes[] = lang("PERMISSION_CREATION_SUCCESSFUL", array($permission));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
  }
}

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
      <h1 >
        Admin Permissions
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php

      echo "
      <form name='adminPermissions' action='".$_SERVER['PHP_SELF']."' method='post'>
      <table class='table table-hover'>
      <tr>
      <th>Delete</th><th>Permission Name</th>
      </tr>";

      //List each permission level
      foreach ($permissionData as $v1) {
        echo "
        <tr>
        <td><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></td>
        <td><a href='admin_permission.php?id=".$v1['id']."'>".$v1['name']."</a></td>
        </tr>";
      }

      echo "
      </table>
      <p>
      <label>New Permission Name :</label>
      <input type='text' name='newPermission' />
      </p>
      ";
      ?>
      <input type="hidden" name="csrf" value="<?=Token::generate();?>" >
      <?php echo "
      <input class='btn btn-primary' type='submit' name='Submit' value='Create Permission Group' />
	  <a href='account.php' class='btn btn-danger'>Cancel</a>
      </form>
      ";
      ?>

	 </div> <!-- /col -->
</div> <!-- /row -->



<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

      </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
