<?php
    session_start();
    if(isset($_SESSION['username']) && !isset($_SESSION['alert_shown'])) {
      echo "Welcome , " . $_SESSION['username'] . "!";
      $_SESSION['alert_shown'] = true;
    }
?>
