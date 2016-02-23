<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com

a modern version of
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels




*/
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>

<!-- If you are going to include the sidebar, do it here -->

</div>
<!-- /.navbar-collapse -->
</nav>
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




<div id="page-wrapper">
  <!-- Main jumbotron for a primary marketing message or call to action -->

  <!-- <div class="jumbotron">
  <div class="container">
  <h1>Jumbotron!!!</h1>
  <p>This is a great area to highlight something.</p>
  <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
</div> -->

<div class="container-fluid">
  <?php require_once("models/account-nav.php"); ?>
  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
      <h1>
        Admin Pages
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo "
      <div id='main'>
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
    </table>






  </div>
</div>
<!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<!-- footers -->
<?php require_once("models/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("models/html_footer.php"); // currently just the closing /body and /html ?>
