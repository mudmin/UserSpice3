<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com

a modern version of
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels




*/
require_once("settings.php");
?>
<style>
p {
  font-color:white;
}
</style>
<div class="container">
  <div class="col-md-12">
    <footer>
      <br><strong><p align="center" style="color:white">&copy; <?php echo $copyright_message; ?></p></strong>
      <?php if($your_public_key  == "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI") {
        echo "<h3 align='center'style='color:white'>For security reasons, you need to change your reCAPTCHA key.</h3>";
      }
      ?>
    </footer>
  </div>
</div>
<!-- /container -->


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

</body>

</html>
