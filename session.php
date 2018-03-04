<?php

	$db = new DB();	/*create DB class object*/
	session_start();	/*start session*/
	$data = $db->checkUser($_SESSION['login_user']);	/*access checkuser method to check if valid user exists(logged in) or not*/
     
    if(!isset($_SESSION['login_user'])){
      header("location:login.php");		/*if user not found, redirect to login page*/
    }
?>