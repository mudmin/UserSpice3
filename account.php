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

<div class="container-fluid" style="">

	<div class="row row-offcanvas row-offcanvas-left">

         <div class="col-sm-6 col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
			<p class="visible-xs">
                <a href="#" class="btn btn-primary btn-xs"  data-toggle="offcanvas"><i class="fa fa-fw fa-caret-square-o-left "></i></a>
              </p>

		<?php require_once("models/left-nav.php"); ?>

		</div><!--/sidebar-->

        <div class="col-sm-6 col-md-9 col-lg-10 main">

          <!--toggle sidebar button-->
          <p class="visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="fa fa-fw fa-caret-square-o-left  "></i></button>
          </p>


	 <?php

  $adisp_avat = "images/noava.jpg";
  $get_info_id = $loggedInUser->user_id;
  $groupname = ucfirst($loggedInUser->title);
  $signupdate = date("D jS M Y G:i:s",$loggedInUser->signupTimeStamp());
  $userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details

  ?>

      <div class="row">

		  <div class="col-xs-12 col-md-6">

		<div class="panel panel-primary">
		<header class="panel-heading"><h3 class="panel-title">Profile</h3></header>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
							<img src="<?php echo $adisp_avat;?>" class="left-block img-thumbnail" alt="Generic placeholder thumbnail">
					</div>
					<div class="col-md-8">
						<h1><?php $liu = ucfirst($loggedInUser->displayname); echo $liu; ?></h1>
						<h2 class="text-muted"><?php echo $groupname; ?></h2>
						<p><?php  echo $groupname; ?> since <?php  echo $signupdate; ?></p>
					</div>
				</div>
			</div>
	</div>




		  </div> <!-- /col1 -->


		</div> <!-- /row -->


		<!-- footers -->
		<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>
		</div><!--/main-split-row-->
	</div>
</div><!--/.container-->

<!-- Place any per-page javascript here -->
<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
