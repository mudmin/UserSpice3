<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com

a modern version of
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels




*/
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>

<!-- If you are going to include the sidebar, do it here -->

</div>
<!-- /.navbar-collapse -->
</nav>
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
  <?php require_once("models/account-nav.php"); ?>
  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-3">
      <h1>
        Admin Page
      </h1>
      <!-- CONTENT GOES HERE -->


      <?php resultBlock($errors,$successes);

      echo "
      <form name='adminPage' action='".$_SERVER['PHP_SELF']."?id=".$pageId."' method='post'>
      <input type='hidden' name='process' value='1'>
      <table class='table table-responsive'>
      <tr><td>
      <h3>Page Information</h3>
      <div id='regbox'>
      <p>
      <label>ID:</label>
      ".$pageDetails['id']."
      </p>
      <p>
      <label>Name:</label>
      ".$pageDetails['page']."
      </p>
      <p>
      <label>Private:</label>";

      //Display private checkbox
      if ($pageDetails['private'] == 1){
        echo "<input type='checkbox' name='private' id='private' value='Yes' checked>";
      }
      else {
        echo "<input type='checkbox' name='private' id='private' value='Yes'>";
      }

      echo "
      </p>
      </div>
      <div class='col-lg-3'></td><td>
      <h3>Page Access</h3>
      <div id='regbox'>
      <p>
      Remove Access:";

      //Display list of permission levels with access
      foreach ($permissionData as $v1) {
        if(isset($pagePermissions[$v1['id']])){
          echo "<br><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
        }
      }

      echo"
      </p><p>Add Access:";

      //Display list of permission levels without access
      foreach ($permissionData as $v1) {
        if(!isset($pagePermissions[$v1['id']])){
          echo "<br><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
        }
      }
      ?>


    </p>

  </div>
</td>
</tr>
</table>
<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
<p>
  <label>&nbsp;</label>
  <input class='btn btn-primary' type='submit' value='Update' class='submit' />
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
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
