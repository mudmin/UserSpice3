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
  $deletions = $_POST['delete'];
  if ($deletion_count = deleteUsers($deletions)){
    $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
  }
  else {
    $errors[] = lang("SQL_ERROR");
  }
}

$userData = fetchAllUsers(); //Fetch information for all users
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

  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-12">
      <h1 >
        Admin Users
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo resultBlock($errors,$successes); ?>

      <form name="adminUsers" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
      <div class="table-responsive">
		<table class="table table-hover">
		<tr>
			<th>Delete</th><th>Username</th><th>Display Name</th><th>Title</th><th>Last Sign In</th>
		</tr>
	
		<?php //Cycle through users
      foreach ($userData as $v1) {
        echo "
        <tr>
        <td>	<div class='form-group'><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></div></td>
        <td><a href='admin_user.php?id=".$v1['id']."'>".$v1['user_name']."</a></td>
        <td>".$v1['display_name']."</td>
        <td>".$v1['title']."</td>
        <td>
        ";

        //Interprety last login
        if ($v1['last_sign_in_stamp'] == '0'){
          echo "Never";
        }
        else {
          echo date("j M, Y", $v1['last_sign_in_stamp']);
        }
        echo "
        </td>
        </tr>";
      }
	  ?>

      </table>
	 </div> <!-- /table-responsive -->
 
	<input class="btn btn-primary" type="submit" id="Submit" name="Submit" value="Delete User" />
	<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
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
