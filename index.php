<?php

	require_once("classes/DB.class.php");
	include('LIB_project1.php');
	include('includes/header.php');
	include('session.php');

	$main = "<div id='main'>";
	
	if(isset($_POST['add'])){	/*if Add to cart button is clicked*/

		/*insert selected item in the cart for that particular user and display success or error message accordingly*/
		$str = $db->insertCart(intval($_POST['item']),intval($_SESSION['uid']));
		if (strpos($str, 'Sorry') !== false) {
    		echo "<h2 class='error'>$str</h2>";
		}else{
			echo "<h2 class='success'>$str</h2>";
		}
		indexContent($db);	/*display all the products by dividing them in 2 div's, on sale and catalogue*/
	}else{
		indexContent($db);	/*display all the products by dividing them in 2 div's, on sale and catalogue*/
	}
	
	$main .= "</div>";	/*closing main div*/
	echo $main;
	
	include('includes/footer.php'); 
?>