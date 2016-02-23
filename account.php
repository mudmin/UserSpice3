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
  $adisp_avat = "images/noava.jpg";
  $get_info_id = $loggedInUser->user_id;
  $groupname = ucfirst($loggedInUser->title);
  $signupdate = date("D jS M Y G:i:s",$loggedInUser->signupTimeStamp());
  $userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details
  
$display_activitydata = '';
$activityData = fetchUserAudit($get_info_id); 
//Cycle through activity data
  foreach ($activityData as $v1)
		{
		$accuserid = ($v1['audit_userid'] == 666) ? 0 : $v1['audit_userid']; // do something with baddies
		$accagodate = ago($v1['audit_timestamp']);
		$accaudate = date("d/M/Y G:i:s",$v1['audit_timestamp']);
		$adisp_rowc = ($v1['audit_eventcode'] == 3) ? "alert alert-danger" : '';
		$display_activitydata .= '
		<tr class="'.$adisp_rowc.'">
		<td>'.$accagodate.'</td>
		<td>'.$v1['audit_action'].'</td>
		</tr>
		';
		}
		
	?>


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

      <div class="row">

		  <div class="col-xs-12 col-md-6">

				<div class="panel panel-default">
				<header class="panel-heading"><h3 class="panel-title"><i class="fa fa-flask"></i> Profile</h3></header>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-4">
								<img src="<?php echo $adisp_avat;?>" class="left-block img-thumbnail" alt="Generic placeholder thumbnail">
							</div>
							<div class="col-md-8">
								<h1><?php $liu = ucfirst($loggedInUser->displayname); echo $liu; ?></h1>
								<h2 class="text-muted"><?php echo $groupname; ?></h2>
								<p><?php  echo $groupname; ?> since <?php  echo $signupdate; ?></p>
								<?php 	if ($loggedInUser->checkPermission(array(2))){ ?>
								<a class="btn btn-success btn-lg" href="admin_dashboard.php" role="button"><i class="fa fa-flask"></i> Admin Dashboard</a>
								<?php } ?>
							</div>
						</div>
					</div>
			</div>

		  </div> <!-- /col1 -->
		  
		 <div class="col-xs-12 col-md-6">

				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-flask"></i> Your Activity</h3>
				  </div>
				  <div class="panel-body">
				   <div class="acctable table-responsive">
					<table class="table">
					  <thead>
						<tr>
						  <th>When</th>
						  <th>&nbsp;</th>
						 </tr>
					  </thead>
					  <tbody>
					  <?php echo $display_activitydata;?>
					  </tbody>
					</table>
				  </div>					
			
			  </div>
			</div>
		 
		  </div> <!-- /col2 -->


	</div> <!-- /row -->


		<!-- footers -->
		<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>
		</div><!--/main-split-row-->
	</div>
</div><!--/.container-->

<!-- Place any per-page javascript here -->
<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
