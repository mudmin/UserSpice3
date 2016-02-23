<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>


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
<!-- Main content goes here!           -->


    </div>
  </div>
</div>
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>
 
<!-- Place any per-page javascript here -->
 
<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>

</body>
</html>
