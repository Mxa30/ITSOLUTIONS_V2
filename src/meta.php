<!DOCTYPE html>
<?php
  session_start();
  // Include de default paths
  include "../../defaultpaths.php";
  // Include de database in elke pagina
  include SOURCE_PATH . "/connect.php";
?>
<html lang="nl">
<head>
    <link rel="stylesheet" type="text/css" href="../../main.css">
    <!-- clear resubmit of form so no popup is shown -->
    <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
    </script>
