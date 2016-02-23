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
    <div class="col-sm-6 col-md-3 col-lg-2 sidebar-offcanvas " id="sidebar" role="navigation">
			<p class="visible-xs"><button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvas"><i class="fa fa-caret-square-o-left"></i></button></p>

			<?php require_once("models/left-nav.php"); ?>
	</div><!--/span-->

    <div class="col-sm-6 col-md-9 col-lg-10 main">
        <!--toggle sidebar button-->

		<?php echo resultBlock($errors,$successes); ?>

		<div class="jumbotron">
          <p class="visible-xs"><button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="fa fa-caret-square-o-left"></i></button> <small>Toggle Menu</small></p>
		<h1>Welcome to UserSpice 3<small>Thanks for installing!</small></h1>
			  <p>UserSpice is designed to be the starting point for your next, great project. It's not a CMS. It's more of a kickstarting platform.</p>
			 <p> UserSpice handles things like your user accounts, password management, and deciding who can visit what page. </p>
			 <p>From there, it's totally up to you what you build!</p>
		  <p>
			<a class="btn btn-primary btn-lg" href="#step1" role="button"><i class="fa fa-flask"></i> Get Started</a>
			<a class="btn btn-warning btn-lg" href="public.php" role="button"><i class="fa fa-flask"></i> Blank Page</a>
		  </p>
		</div>

		  <!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">


			  <div class="well well-lg"><p>UserSpice is built using <a href="http://getbootstrap.com/">Twitter's Bootstrap</a>, so it is fully responsive and there is tons of documentation.
			  The look and the feel can be changed very easily. </p>
			  <p>Consider checking out <a href="http://bootsnipp.com">Bootsnipp</a> to see all the widgets and tools you can easily drop into UserSpice to get
			  your project off the ground.</div>
			  <!-- CONTENT GOES HERE -->

			  <h3 >What's New?</h3>
				  <ul>
						<li>Language file entries for Sign-Up and Sign-In.</li>
						<li>A <a href="public.php">Blank Page</a>.</li>
						<li>Activity audit.</li>
						<li><a href="admin_dashboard.php">Admin Dashboard</a>.</li>
						<li>Example queries and markup for Flot graphs.</li>
						<li>Clean Bootstrap markup compatible with <a href="https://bootswatch.com/">Bootswatch</a> and <a href="http://www.lavishbootstrap.com/">Lavish Bootstrap</a>.</li>
						<li>New ago() date formatting function.</li>
						<li>Simple notifications (eg failed logins).</li>
					</ul>

				<hr />

				<p>Before you get started, there are a few things you need to do.</p>

				<h3  id="step1">Step 1: Change your password!</h3>
				<p>You're going to login with the default username of admin and the default password of password.  If you cannot login for some reason, edit the login.php file and uncomment out the lines<br> error_reporting(E_ALL);<br>
				  ini_set('display_errors', 1);<br>
				  to see if there are any errors in your server configuration. </p>

				  <h3 >Step 2: Change some settings</h3>
				  <p>You want to go to the Account Info section and go into Admin Configuration. From there you can personalize your settings. You definitely want to set your Website Url so the page redirects work properly. Otherise, you will probably get redirected to your root instead of this homepage.</p>

				  <h3 >Step 3: Poke around!</h3>
				  <p>You can go to Admin Permissions and add some new user levels.  Then check out Admin Pages to decide which pages are private and which are public. Once you make a page private, you can decide how what level of access someone needs to access it.  Any new pages you create in your site folder will automatically show up here.</p>

				 <h3 >Step 4: Check out the other resources</h3>
				  <p>The /blank_pages folder contains a blank version of this page and one with the sidebar included for your convenience. There are also special_blanks that you can drop into your site folder and load up to check out all the things you can do with Bootstrap.</p>

				  <h3 >Step 5: Design and secure your own pages</h3>
				  <p>Of course, using our blanks is the quickest way to get up and running, but you can also secure any page.  Simply add this php code to the top of your page and it will perform a check to see if you've set any special permissions.
					<br>require_once("models/config.php");<br>
					  if (!securePage($_SERVER['PHP_SELF'])){die();}</p>

					  <h3 >Step 6: Check out the forums and documentation at <a target="_blank" href="http://UserSpice.com">UserSpice.com</a></h3>
					  <p>That's where the latest options are and you can find people willing to help!</p>

					  <h3 >Step 7: Replace this ugly homepage with your own beautiful creation</h3>
					  <p>Don't forget to swap out logo.png in the images folder with your own! If you're getting nagging message in the footer, <a href="https://www.google.com/recaptcha/admin#list">go get you some of your own reCAPTCHA keys</a></p>

				 <p><a class="btn btn-success" href="account.php" role="button"><i class="fa fa-flask"></i> Explore</a></p>

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
