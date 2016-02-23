<?php
include("install/includes/install_settings.php");
foreach ($files as $file) {

if (!unlink($file))
  {
  echo ("Error deleting $file<br>");
  }
else
  {
  echo ("Deleted $file<br>");
  }
}

rrmdir("install");
?>
<p align="center">Everything SHOULD be installed properly. You can always go back and edit your models/settings.php file manually if you need to. <br><br>Login using the default credentials<br><br>

<strong>admin<br>password<br><br>Change these credentials immediately after login</strong><br><br>

Be sure to go to "Admin Configuration" to set the proper path for your installation.

<p align="center">If you had <strong>errors</strong> at the top of this page, you MUST go into the /models folder and <strong>delete the entire install folder<br> Leaving these files present is a security vulnerability.</p>


<h3 align="center"><a class="button" href="../../index.php">Check Out UserSpice!</a></h3>
<?php
function rrmdir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (is_dir($dir."/".$object))
          rrmdir($dir."/".$object);
        else
          unlink($dir."/".$object);
      }
    }
    rmdir($dir);
  }
}
?>
