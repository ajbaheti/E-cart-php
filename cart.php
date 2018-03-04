<?php

	require_once("classes/DB.class.php");
	include('LIB_project1.php');
	include('includes/header.php');
	include('session.php');
	// $db = new DB();	/*create DB class object*/
	// $data = $db->checkUser($_SESSION['login_user']);	access checkuser method to check if valid user exists(logged in) or not
     
 //    if(!isset($_SESSION['login_user'])){
 //      header("location:login.php");		/*if user not found, redirect to login page*/
 //    }
    
	echo "<h1 class='headColor'>Current Cart Contents</h1>";

	if (isset($_POST['emptycart'])){
		/*if buy cart button is clicked, it will empty the cart and will not update product tables back*/
		// $db->deleteAndUpdate(intval($_SESSION['uid']));
		$db->deleteCart(intval($_SESSION['uid']));
		/*refresh the page after deletion*/
		header('Location: '.$_SERVER['REQUEST_URI']);
	}/*elseif(isset($_POST['buycart'])){
		//if empty cart button is clicked, it will empty cart and update product table back with quantity
		// $db1 = new DB();
		$db->deleteAndUpdate(intval($_SESSION['uid']));
		// $db1 = new DB();
		// $db1->deleteCart1(intval($_SESSION['uid']));
		// refresh the page after deletion
		header('Location: '.$_SERVER['REQUEST_URI']);}*/
	else{
		/*otherwise get all the products in cart for logged in user*/
		$data = $db->getCart(intval($_SESSION['uid']));

		if(count($data) > 0){	/*if cart contains at least one item*/

			$cost = 0;
			$cartcontent = "<div id='cart'>";	/*create cart div*/
			foreach($data as $row){
				
				/*display name,description,quantity,price for each item in cart*/
				$cartcontent .= "<div class='individualitem'>";
				$cartcontent .= "<h3>{$row->getProdName()}</h3>";
				$cartcontent .= "<p>{$row->getProdDesc()}</p>";
				$cartcontent .= "<p>Quantity:<strong>{$row->getProdQuantity()}</strong> at \${$row->getProdPrice()} each.
								 Total for item: <strong>\$".$row->getProdQuantity()*$row->getProdPrice()."</strong> Only</p>";
				$cartcontent .= "</div>";
				$cost += $row->getProdQuantity()*$row->getProdPrice();	/*calculation of total cost of cart items*/
			}
			$cartcontent .= "<h2>Total cost: \$$cost</h2>";	/*display total cost*/
			$cartcontent .= "<form action='cart.php' method='POST'>";
			/*empty cart button*/
			$cartcontent .= "<input type='submit' name='emptycart' value='Empty Cart' class='btnBorder' />";
			//$cartcontent .= "<input type='submit' name='buycart' value='Buy Items' class='submitMargin' />";
			$cartcontent .= "</form> </div>";
		}else{
			/*otherwise show empty cart message*/
			$cartcontent = "<div id='cart'>";
			$cartcontent .= "<h2 class='error'>Your cart is empty!</h2>";
			$cartcontent .= "</div>";
		}
		echo $cartcontent;
	}

	include('includes/footer.php'); 
?>