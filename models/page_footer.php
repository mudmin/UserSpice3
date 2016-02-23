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
require_once("settings.php");
?>



<div class="container" style="">
	<div class="row">
	  <div class="col-md-12">
		<div id="footer">
			<footer>
			  &copy; <?php echo $copyright_message; ?>
			</footer>
		</div>
	  </div>
	</div>
	
				  <?php if($your_public_key  == "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI") {
				echo '<div class="alert alert-danger" role="alert">For security reasons, you need to change your reCAPTCHA key.</div>';
			  }
			  ?>
	
</div>


<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
// (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
// function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
// e=o.createElement(i);r=o.getElementsByTagName(i)[0];
// e.src='//www.google-analytics.com/analytics.js';
// r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
// ga('create','UA-XXXXX-X','auto');ga('send','pageview');
</script>
<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script src="js/user.js"></script>
