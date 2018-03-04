<?php
   /*start of session*/
   session_start();

	/*delete session as we are logging out*/
   session_unset();
   if(session_destroy()) {	/*redirect user back to login page*/
      header("Location: login.php");
   }
?>
