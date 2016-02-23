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

<div class="container-fluid" style="">

   <div class="row row-offcanvas row-offcanvas-left">
    <div class="col-sm-6 col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
			<p class="visible-xs"><button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvas"><i class="fa fa-caret-square-o-left"></i></button></p>

			<?php require_once("models/left-nav.php"); ?>
	</div><!--/span-->

    <div class="col-sm-6 col-md-9 col-lg-10 main">
        <!--toggle sidebar button-->
          <p class="visible-xs"><button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="fa fa-caret-square-o-left"></i></button></p>

		<?php echo resultBlock($errors,$successes); ?>

		  <!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">

				<h1 class="page-header">Welcome to UserSpice 3 <small>Thanks for installing!</small></h1>
			  <!-- CONTENT GOES HERE -->
			  <p>UserSpice is designed to be the starting point for your next, great project. It's not a CMS. It's more of a kickstarting platform.
			  UserSpice handles things like your user accounts, password management, and deciding who can visit what page. From there, it's totally up to you what you build!</p>

			  <p>UserSpice is built using <a href="http://getbootstrap.com/">Twitter's Bootstrap</a>, so it is fully responsive and there is tons of documentation.
			  The look and the feel can be changed very easily. Consider checking out <a href="http://bootsnipp.com">Bootsnipp</a> to see all the widgets and tools you can easily drop into UserSpice to get
			  your project off the ground.</p>

				<p>Before you get started, there are a few things you need to do.</p>

				<h2>Step 1: Create an Account</h2>
				<p>The first account created is automatically the Super Master Administrator, so you want to jump on this first.  If you cannot create an account for some reason, edit the register.php file and uncomment out the lines<br> error_reporting(E_ALL);<br>
				  ini_set('display_errors', 1);<br>
				  to see if there are any errors in your server configuration. </p>

				  <h2>Step 2: Login and change some settings</h2>
				  <p>You want to go to the Account Info section and go into Admin Configuration. From there you can personalize your settings. You definitely want to set your Website Url so the page redirects work properly. Otherise, you will probably get redirected to your root instead of this homepage.</p>

				  <h2>Step 3: Poke around!</h2>
				  <p>You can go to Admin Permissions and add some new user levels.  Then check out Admin Pages to decide which pages are private and which are public. Once you make a page private, you can decide how what level of access someone needs to access it.  Any new pages you create in your site folder will automatically show up here.</p>

				  <h2>Step 4: Check out the other resources</h2>
				  <p>The /blank_pages folder contains a blank version of this page and one with the sidebar included for your convenience. There are also special_blanks that you can drop into your site folder and load up to check out all the things you can do with Bootstrap.</p>

				  <h2>Step 5: Design and secure your own pages</h2>
				  <p>Of course, using our blanks is the quickest way to get up and running, but you can also secure any page.  Simply add this php code to the top of your page and it will perform a check to see if you've set any special permissions.
					<br>require_once("models/config.php");<br>
					  if (!securePage($_SERVER['PHP_SELF'])){die();}</p>

					  <h2>Step 6: Check out the forums and documentation at <a href="http://UserSpice.com"></a>UserSpice.com</h2>
					  <p>That's where the latest options are and you can find people willing to help!</p>

					  <h2>Step 7: Replace this ugly homepage with your own beautiful creation</h2>
					  <p>Don't forget to swap out logo.png in the images folder with your own! If you're getting nagging message in the footer, <a href="https://www.google.com/recaptcha/admin#list">go get you some of your own reCAPTCHA keys</a></p>


				</div>
			</div>
			<!-- /content .row -->

<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

        </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
