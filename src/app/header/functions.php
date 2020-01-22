<?php

  function logOut() {
    session_destroy();
    $redirectlocation = APP_PATH . "/login/index.php";
    header("location: {$redirectlocation}");
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Als de POST request van de knop 'loginButton' komt
    if (isset($_POST['logOut'])) {
      logOut();
    }
  }
?>
