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
}

// count the number of users in the users table
$usercount = '';
if($getusercount = countStuff('users')){
	$usercount = $getusercount['sum1'];
	}

// count the number of pages in the UserSpice database
$pagescount = '';
if($getpagescount = countStuff('pages')){
	$pagescount = $getpagescount['sum1'];
	}

// count the number of logins in the last 24 hours	
$numlogins24 = 0;
$numlogins_lookback = 86400;
$now = time();
$getnumlogins = $now - $numlogins_lookback;
if($getnumloginscount = countLoginsSince(1,$getnumlogins)){
	$numlogins24 = $getnumloginscount['sum1'];
	}	

// get notification of new events
$noticount = 0;
$uid = $loggedInUser->user_id; // user id
$now = time();

	if (isset($_GET['n']))
		{
		$_SESSION['ll'] = time();
		$_SESSION['lt'] = time();
		}
	else
		{
		$prev_login = $_SESSION['ll'];
		$this_sessi = $_SESSION['lt'];

		if($not1 = fetchAllLatest($uid,$this_sessi,$now,3))
					{ 
					$noticount = count($not1);
					}
		}
	
$display_activitydata = '';
$activityData = fetchAllAudit(); 
//Cycle through activity data
  foreach ($activityData as $v1)
		{
		$accuserid = ($v1['audit_userid'] == 666) ? 0 : $v1['audit_userid']; // do something with baddies
		$accuserip = $v1['audit_userip'];
		$accagodate = ago($v1['audit_timestamp']);
		$accaudate = date("d/M/Y G:i:s",$v1['audit_timestamp']);
		$adisp_name = ($v1['display_name'] == "") ? "Unknown" : $v1['display_name'];
		$adisp_rowc = ($v1['audit_eventcode'] == 3) ? "alert alert-danger" : '';
		$adisp_rowc = (($adisp_rowc == '') && ($v1['audit_eventcode'] == 5)) ? "alert alert-success" : $adisp_rowc;
		$display_activitydata .= '
		<tr class="'.$adisp_rowc.'">
		<td>'.$accagodate.'</td>
		<td><a href="admin_user.php?id='.$accuserid.'">'.$adisp_name.'</a></td>
		<td>'.$v1['audit_eventcode'].'</td>
		<td>'.$v1['audit_action'].'</td>	
		<td>'.$accuserip.'</td>
		</tr>
		';
		}
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
  
   <?php //only admins can view the dashboard
	if ($loggedInUser->checkPermission(array(2))){
	?>
  
	<div class="row">
		<div class="col-md-12">
			<h1><i class="fa fa-flask"></i> Admin Dashboard</h1><hr />
		</div>
	</div>
	<!-- A row of 4 blocks --> 
	<div class="row">
		<div class="col-xs-6 col-md-3">
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-flask"></i> Users <span class="badge pull-right"><a data-toggle="modal" href="#usModal1">US4</a></span></h3>
			  </div>
			  <div class="panel-body"><i class="fa fa-fw fa-user fa-3x"></i>
				<a href="admin_users.php" class="count giant" ><?php echo $usercount;?></a>
			  </div>
			</div>
		</div> <!-- /block -->
		<div class="col-xs-6 col-md-3">
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-flask"></i> Pages</h3>
			  </div>
			  <div class="panel-body"><i class="fa fa-file-text fa-3x"></i>
				<a class="count giant" href="admin_pages.php"><?php echo $pagescount;?></a>
			  </div>
			</div>
		</div> <!-- /block -->
		<div class="col-xs-6 col-md-3">
			<div class="panel panel-success">
			  <div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-flask"></i> Logins <span class="badge pull-right">24hrs</span></h3>
			  </div>
			  <div class="panel-body"><i class="fa fa-sign-in fa-3x"></i>
				<span class="count giant"><?php echo $numlogins24;?></span>
			  </div>
			</div>
		</div> <!-- /block -->
		<div class="col-xs-6 col-md-3">
			<div class="panel panel-danger">
			  <div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-flask"></i> Failed Logins</h3>
			  </div>
			  <div class="panel-body"><i class="fa fa-bell fa-3x"></i>
				<span class="count giant"><?php echo $noticount;?></span>
			  </div>
			</div>
		</div> <!-- /block -->
	</div> <!-- /blocks row -->
	<!-- Two FLOT Charts --> 
	<div class="row">
			<div class="col-xs-12 col-md-4">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-plus-square"></i> Sign Ups</h3>
				  </div>
				  <div class="panel-body">
					<div class="flotcontainer" id="flotcontainer0"></div>
				  </div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-sign-in"></i> Sign Ins</h3>
				  </div>
				  <div class="panel-body">
					<div class="flotcontainer" id="flotcontainer2"></div>
				  </div>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-4">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-flask"></i> Admin Audit</h3>
				  </div>
				  <div class="panel-body">
				   <div class="acctable table-responsive">
					<table class="table">
					  <thead>
						<tr>
						  <th>When</th>
						  <th>Who</th>
						  <th>What</th>
						  <th>Why</th>
						  <th>Where</th>
						 </tr>
					  </thead>
					  <tbody>
					  <?php echo $display_activitydata;?>
					  </tbody>
					</table>
				  </div>					
		
					
			  </div>
			</div>
		</div>
	</div> <!-- /row -->
	<!-- Two FLOT Charts --> 
	<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<a class="panel-title" href="admin_permissions.php"><i class="fa fa-fw fa-code"></i>Group Membership</a>
				  </div>
				  <div class="panel-body">
					<div class="flotcontainer" id="flotcontainer"></div>
				  </div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<a class="panel-title" href="admin_pages.php"><i class="fa fa-fw fa-newspaper-o"></i>Pages Privacy</a>
				  </div>
				  <div class="panel-body">
					<div class="flotcontainer" id="flotcontainer1"></div>
				  </div>
				</div>
			</div>
			
	</div> <!-- /row -->
	   <?php //only admins can view the dashboard
	}
	?>
	<!-- Modal -->
  <div class="modal fade" id="usModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">UserSpice 4</h4>
        </div>
        <div class="modal-body">
         <div class="row">
				<div class="col-md-12">
						<div class="panel panel-warning">
								<div class="panel-heading">
										<h3 class="text-center">UserSpice 4 is here!</h3>
										<p class="text-center">Visit the forums or GitHub</p>
								</div>
								<div class="panel-body text-center">
										<p class="lead" style="font-size:30px"><strong>Completely Rewritten OOP/PDO PHP Codebase</strong></p>
								</div>
								<ul class="list-group list-group-flush text-center">
										<li class="list-group-item">
												<span class="glyphicon glyphicon-calendar"></span> Provides a Clean Working Directory
										</li>
										<li class="list-group-item">
												<span class="glyphicon glyphicon-envelope"></span> New Flexible Classes
										</li>
										<li class="list-group-item">
												<span class="glyphicon glyphicon-heart"></span> Fully Documented and Supported
										</li>
								</ul>
								<div class="panel-footer"> <a class="btn btn-lg btn-block btn-success" target="_blank" href="http://UserSpice.com">UserSpice.com</a> </div>
						</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
	

<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

      </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->
	 <!-- Flot Charts JavaScript -->
    <!--[if lte IE 8]><script src="js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.pie.js"></script>

	<script type="text/javascript">
	$(document).ready(function(){	
	
	// Cheeky animation http://codepen.io/shivasurya/pen/FatiB
	$('.count').each(function () {
		$(this).prop('Counter',0).animate({
			Counter: $(this).text()
		}, {
			duration: 1600,
			easing: 'swing',
			step: function (now) {
				$(this).text(Math.ceil(now));
			}
		});
	});
	
	
// Example graphs 
// ------------------------------------------------------------------------- graph 0
// Signups			
		var options = {

			xaxis: {
				mode: "time",
				timeformat: "%m/%d",
				},
			yaxis: {
				tickSize : 1,
				tickDecimals: 0
				},
        series: {
            lines: { show: true },
            points: {
                radius: 12,
                show: true,
                fill: true
            }
        }
	
		};
			
		$.ajax({
				url: 'json_chart.php',
				contentType: 'application/json; charset=utf-8',
				type: 'GET',
				data: {"chartid" : 0},
				dataType: 'json',
				success: function (data) { 
				$.plot('#flotcontainer0', data, options);
					},
				failure: function (response) {
					alert(response.d);
				}
			});
		
// -------------------------------------------------------------------------
// Logins
		$.ajax({
				url: 'json_chart.php',
				contentType: "application/json; charset=utf-8",
				type: "GET",
				data: {"chartid" : 2},
				dataType: 'json',
				success: function (data) { 
			
				$.plot($("#flotcontainer2"), data, {
					xaxis: { mode: "time"},
					yaxis: {tickSize : 1,tickDecimals: 0},
					series: { bars: { show: true } }
				});
				
					},
				failure: function (response) {
					alert(response.d);
				}
			});
// -------------------------------------------------------------------------	
// The bottom left pie
		$.ajax({
				url: 'json_chart.php',
				contentType: "application/json; charset=utf-8",
				type: "POST",
				dataType: 'json',
				success: function (data) { 
				$.plot($("#flotcontainer"), data, {
							series: {
								pie: { 
									show: true,
									radius: 500,
									label: {
										show: true,
										radius: 3/4,
										background: { 
											opacity: 0.5,
											color: '#000'
										}
									}
								}
							},
							legend: {
								show: false
							}
						});
					},
				failure: function (response) {
					alert(response.d);
				}
			});
// -------------------------------------------------------------------------	
// The bottom right pie
		$.ajax({
				url: 'json_chart.php',
				contentType: "application/json; charset=utf-8",
				type: "GET",
				data: {"chartid" : 1},
				dataType: 'json',
				success: function (data) { 
				$.plot($("#flotcontainer1"), data, {
							series: {
								pie: { 
									show: true,
									radius: 1,
									label: {
										show: true,
										radius: 3/4,
										background: { 
											opacity: 0.5,
											color: '#000'
										}
									}
								}
							},
							legend: {
								show: false
							}
						});
					},
				failure: function (response) {
					alert(response.d);
				}
			});

// -------------------------------------------------------------------------		
		});
	</script>
<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
