<?php
	require_once("classes/DB.class.php");
	include_once("classes/product.class.php");
	include('LIB_project1.php');
	include('includes/header.php');
	include('session.php');
	
	if (isset($_POST['add_item'])) {	/*if add new item submit is clicked*/
		
		$errorflag = addSubmitValidation();	/*perform all validations for input fields*/
		if($errorflag['Error']){	/*if no error found in validations*/
			$salecount = $db->getItems('countsale');	/*get count of total items currently on sale*/
			/*condition to check on sale items constraint*/
			if(($salecount >= 3 && $salecount < 5) || ($salecount == 5 && $errorflag['ProdSalePrice'] == 0))
			{
				/*if uploaded file is moved correctly*/
				if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $errorflag['ProdImage'])){
					/*sanitize all input values*/
					foreach($errorflag as $key=>$value) {
						$errorflag[$key] = sanitizeString($value);
					}
					/*insert new product details in database*/
					$db->insertProduct($errorflag['ProdName'],$errorflag['ProdDesc'],floatval($errorflag['ProdPrice']),
						intval($errorflag['ProdQuantity']),floatval($errorflag['ProdSalePrice']),$errorflag['ProdImage']);

					/*refresh the page with success message*/
					echo createDropdown($db);
					echo "<h3 class='success'>New item added successfully!!</h3>";
					$x = new product();
					/*this is dummy value $x as reuired by below function( object - parameter is required from reusability purpose)*/
					echo addNewItem("Add Item",$x);	/*function call to add new item div*/
				}
			}else{
				/*refresh the page with error message of sale constarint*/
				echo createDropdown($db);
				echo "<h3 class='error'>Already 5 items on sale, you cannot have more than 5 items on sale</h3>";
				$x = new product();
				echo addNewItem("Add Item",$x);
			}
		}else{
			/*refresh the page with error messages of validation*/
			echo createDropdown($db);
			echo $errorflag['ErrorMsg'];
			$x = new product();
			echo addNewItem("Add Item",$x);
		}
	}
	elseif(isset($_POST['edit'])){	/*if dropdown edit submit button is clicked*/
		echo createDropdown($db);	
		/*simply refresh page by getting details of item selected to edit and pre-populate those details in edit product form*/
		$data = $db->getSingleProduct(intval($_POST['pick']));
		echo addNewItem("Edit Item",$data);	/*reuse add item method display edit item div with data to pre-populate*/
	}
	elseif(isset($_POST['edit_item'])){		/*if edit item submit is clicked*/
		$errorflag = addSubmitValidation();	/*perform all validations on input values*/
		if($errorflag['Error']){	/*if no error found in validations*/
			$salecount = $db->getItems('countsale');	/*get count of total items on sale*/
			/*condition if 3 items on sale, current item was on sale and is being moved from sale to catalogue*/
			if($salecount == 3 && $errorflag['ProdSalePrice'] == 0 && $errorflag['onsale'] == 'X'){
				/*refresh page with error message*/
				echo createDropdown($db);
				echo "<h3 class='error'>Atleast 3 items should be on sale</h3>";
				$data = $db->getSingleProduct(intval($errorflag['ProdId']));
				echo addNewItem("Edit Item",$data);
			}/*condition if 5 items on sale, current item was not on sale and is being moved from catalogue to sale*/
			elseif($salecount == 5 && $errorflag['ProdSalePrice'] != 0 && $errorflag['onsale'] == ''){
				/*refresh page with error message*/
				echo createDropdown($db);
				echo "<h3 class='error'>Already 5 items on sale, you cannot have more than 5 items on sale</h3>";
				$data = $db->getSingleProduct(intval($errorflag['ProdId']));
				echo addNewItem("Edit Item",$data);
			}
			else{	/*otherwise move uploaded file*/
				if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $errorflag['ProdImage'])){
					/*sanitize all input values*/
					foreach($errorflag as $key=>$value) {
						$errorflag[$key] = sanitizeString($value);
					}
					/*update product in database with new values*/
					$db->updateProduct($errorflag['ProdId'],$errorflag['ProdName'],$errorflag['ProdDesc'],floatval($errorflag['ProdPrice']),
						intval($errorflag['ProdQuantity']),floatval($errorflag['ProdSalePrice']),$errorflag['ProdImage']);

					/*refresh page with success message*/
					echo createDropdown($db);
					echo "<h3 class='success'>Item edited successfully!!</h3>";
					$x = new product();
					echo addNewItem("Add Item",$x);
				}
			}
		}else{
			/*refresh page with error messages of validations*/
			echo createDropdown($db);
			echo $errorflag['ErrorMsg'];
			$data = $db->getSingleProduct(intval($errorflag['ProdId']));
			echo addNewItem("Edit Item",$data);
		}
	}else{
		/*load the page with dropdown and new item to add option*/
		echo createDropdown($db);
		$x = new product();
		echo addNewItem("Add Item",$x);
	}

	include('includes/footer.php'); /*include for footer content*/
?>