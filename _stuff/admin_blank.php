<?php
/*
UserSpice 2.5.3
by www.UserSpice.com

a modern version of
UserCake Version: 2.0.2
http://usercake.com

UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels




*/
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>
<?php
// Top Code Goes Here

?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<!-- <div class="jumbotron">
<div class="container">
<h1>Jumbotron!!!</h1>
<p>This is a great area to highlight something.</p>
<p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
</div> -->

<div class="container">
  <div class="row">
    <div class="col-md-12">
					<div class="col-md-3"><?php include("models/left-nav.php");  ?></div>
				<div class="col-md-8">

<!-- Main content goes here!           -->

<?php


?>
</div>
</div>


    </div>
  </div>
</div>
</div>
</div>
</div>


<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>
 
<!-- Place any per-page javascript here -->
 
<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>

</body>
</html>
