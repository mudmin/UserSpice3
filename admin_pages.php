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
$pages = getPageFiles(); //Retrieve list of pages in root usercake folder
$dbpages = fetchAllPages(); //Retrieve list of pages in pages table
$creations = array();
$deletions = array();

//Check if any pages exist which are not in DB
foreach ($pages as $page){
  if(!isset($dbpages[$page])){
    $creations[] = $page;
  }
}

//Enter new pages in DB if found
if (count($creations) > 0) {
  createPages($creations)	;
}

if (count($dbpages) > 0){
  //Check if DB contains pages that don't exist
  foreach ($dbpages as $page){
    if(!isset($pages[$page['page']])){
      $deletions[] = $page['id'];
    }
  }
}

//Delete pages from DB if not found
if (count($deletions) > 0) {
  deletePages($deletions);
}

//Update DB pages
$dbpages = fetchAllPages();
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
    <div class="col-md-6">
      <h1>
        Admin Pages
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo "<div class='table-responsive'>
      <table class='table table-hover'>
      <tr><th>Id</th><th>Page</th><th>Access</th></tr>";

      //Display list of pages
      foreach ($dbpages as $page){
        echo "
        <tr>
        <td>
        ".$page['id']."
        </td>
        <td>
        <a href ='admin_page.php?id=".$page['id']."'>".$page['page']."</a>
        </td>
        <td>";

        //Show public/private setting of page
        if($page['private'] == 0){
          echo "Public";
        }
        else {
          echo "Private";
        }

        echo "
        </td>
        </tr>";
      }

      ?>
    </table></div> <!-- /table-responsive -->


	 </div> <!-- /col -->


</div> <!-- /row -->


<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

      </div><!--/main-split-row-->
	</div>
</div><!--/.container-->
<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
