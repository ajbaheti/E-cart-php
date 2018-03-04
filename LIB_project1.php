<?php
	
	include('includes/validations.php');

	/*
	This function takes DB object as input. First it gets all items on sale and displays them.
	Then it calls getPages method which in turn calls another method and displays all catalogue items in pages.
	*/
	function indexContent($db){
		
		//display sale items
		$data = $db->getItems("saleitems");
		$saleitem .= "<h1 class='headColor'>Sale Items</h1>";
		$saleitem .= "<div id='saleitems'>";
		// $saleitem .= itemsTemplate($saleitem,$data,"sale");
		foreach($data as $row){
				$imagepath = "/~ajb9736/756/project1/".$row->getProdImage();
				$saleitem .= "<div class='individualitem'>";
				$saleitem .= "<h3>{$row->getProdName()}</h3>";
				$saleitem .= "<img class='aleft' src='".$imagepath."' alt='product image' />";
				$saleitem .= "<p>{$row->getProdDesc()}</p>";
				$saleitem .= "<p><strong>Sale Price: </strong>\${$row->getProdSalePrice()} 
							 (Regularly: \${$row->getProdPrice()}) Only <strong>{$row->getProdQuantity()}</strong> left!</p>";
				$saleitem .= "<form action='index.php' method='post'>";
				$saleitem .= "<input type='hidden' name='item' value='".$row->getProdId()."'>";
				$saleitem .= "<input type='submit' name='add' value='Add To Cart' class='btnBorder' />";
				$saleitem .= "</form> </div>";
		}
		$saleitem .= "</div>";	//closing sale items div
		echo $saleitem;

		//display catalogue items
		$otheritem = "<h1 class='headColor'>Catalogue Items</h1>";
		$otheritem .= "<div id='catalogueitems'>";
		$otheritem = $db->getPages($otheritem);
		$otheritem .= "</div>";	//closing catalogue items div
		echo $otheritem;
	}//indexContent

	/*
	This function takes DB object as input. It calls get allproducts method which returns list of all products
	and then it adds them to a dropdown and also adds a button which provides option to edit selected product.
	*/
	function createDropdown($db){	//function to create product edit dropdown

		$data = $db->getAllProducts();	//query to get all products list
		$dropdown = "<div id='admin_invent'>";
		$dropdown .= "<form action='admin.php' method='POST'>";
		$dropdown .= "<h1 class='headColor'>Admin Inventory Page</h1>";
		$dropdown .= "<label>Choose an item to edit:</label>";
		$dropdown .= "<select name='pick'>";		
	  	foreach($data as $row){
			$dropdown .= "<option value='{$row->getProdId()}'>{$row->getProdName()} - {$row->getProdDesc()}</option>";	
	  	}
	  	$dropdown .= "</select>";
	  	$dropdown .= "<input type='submit' name='edit' value='Click to Edit' class='submitMargin' />";
	  	$dropdown .= "</form> </div>";
	  	return $dropdown;
	}//createDropdown

	/*
	This function takes title and data as input.
	When title is Add item, add item form is displayed which has no pre-populated values.
	When title is Edit item, data has details of selected product to edit which are pre-populated in the form.
	Finally buttons are provided to either Add or Edit item based on condition.
	*/
	function addNewItem($title,$data){	//function to add new item in products

		if($data->getProdSalePrice() == "")
			$data->setProdSalePrice(0);

		$newprod = "<div id='admin_add'>";
		$newprod .= "<form action='admin.php' method='post' enctype='multipart/form-data'>";
		$newprod .= "<h2>$title:</h2>";
		if($title == "Add Item")
			$title = "add_item";
		else
			$title = "edit_item";
		$newprod .= "<div><label>Name: </label><input type='text' name='ProdName' value='{$data->getProdName()}'/></div>";
		$newprod .= "<div><label>Description: </label><textarea name='ProdDesc' cols='30' rows='3'>{$data->getProdDesc()}</textarea></div>";
		$newprod .= "<div><label>Price: </label><input type='text' name='ProdPrice' value='{$data->getProdPrice()}'/></div>";
		$newprod .= "<div><label>Quantity: </label><input type='text' name='ProdQuantity' value='{$data->getProdQuantity()}' /></div>";
		$newprod .= "<div><label>Sale Price: </label><input type='text' name='ProdSalePrice' value='{$data->getProdSalePrice()}' /></div>";
		$newprod .= "<input type='hidden' class='hidden' name='ProdId' value='{$data->getProdId()}' />";
		$newprod .= "<input type='hidden' class='hidden' name='onsale' value='{$data->getonsale()}' />";
		$newprod .= "<input type='hidden' class='hidden' name='MAX_FILE_SIZE' id='MAX_FILE_SIZE' value='3500000' />";
		$newprod .= "<div><label>New Image: </label>";
		$newprod .= "<input name='uploaded_file' id='uploaded_file' type='file' /> </div>";
		$newprod .= "<div><label><strong>Password: </strong></label><input type='password' name='PassWord' /></div>";
		$newprod .= "<div><input type='reset' value='Reset Form' class='btnBorder'/>";
		$newprod .= "<input type='submit' name='$title' value='Submit Form' class='submitMargin' /></div>";
		$newprod .= "</form> </div>";

		return $newprod;
	}//addNewItem

	/*
	This function performs all add or edit item form fields validation.
	First it gets form field values from $_POST and then performs validations for each field.
	If all validations are successful, only then it goes to update.
	Otherwise respective errors are displayed.
	*/
	function addSubmitValidation(){

		$errorflag = True;
		$ProdName = isset($_POST['ProdName']) ? trim($_POST['ProdName']) : '';
		$ProdDesc = isset($_POST['ProdDesc']) ? trim($_POST['ProdDesc']) : '';
		$ProdPrice = isset($_POST['ProdPrice']) ? trim($_POST['ProdPrice']) : '';
		$ProdSalePrice = isset($_POST['ProdSalePrice']) ? trim($_POST['ProdSalePrice']) : '';
		$ProdQuantity = isset($_POST['ProdQuantity']) ? trim($_POST['ProdQuantity']) : '';
		$ProdId = $_POST['ProdId'];
		$pwd = sha1($_POST['PassWord']);
		$onsale = $_POST['onsale'];

		if($ProdName == "" || !alphanumeric($ProdName) || strlen($ProdName) > 50){
			$errorflag = False;
			if($ProdName == "")
				$errormsg .= "<p class='errorClass'>Please enter product name</p>";
			else if(!alphabetic($ProdName))
				$errormsg .= "<p class='errorClass'>Please enter alphanumeric characters only in product name</p>";
			else
				$errormsg .= "<p class='errorClass'>Product name cannot be greater than 50 characters</p>";
		}
		if($ProdDesc == "" || (sqlMetaChars($ProdDesc) || sqlInjection($ProdDesc) || sqlInjectionUnion($ProdDesc) ||
  			sqlInjectionSelect($ProdDesc) || sqlInjectionInsert($ProdDesc) || sqlInjectionDelete($ProdDesc) ||
  		 	sqlInjectionUpdate($ProdDesc) || sqlInjectionDrop($ProdDesc) || crossSiteScripting($ProdDesc) ||
  		 	crossSiteScriptingImg($ProdDesc))){
			$errorflag = False;
			if($ProdDesc == "")
				$errormsg .= "<p class='errorClass'>Please enter appropriate description</p>";
			else
				$ProdDesc .= "<p class='errorClass'>Enter valid description</p>";
		}
		if($ProdQuantity == "" || !numeric($ProdQuantity) || strlen($ProdQuantity) > 3){
			$errorflag = False;
			if($ProdQuantity == "")
				$errormsg .= "<p class='errorClass'>Please enter quantity</p>";
			else if(!numeric($ProdQuantity))
				$errormsg .= "<p class='errorClass'>Enter numbers only</p>";
			else
				$errormsg .= "<p class='errorClass'>Quantity cannot be grater than 3 digit number</p>";
		}
		if($ProdPrice == "" || !dotnumeric($ProdPrice)){
			$errorflag = False;
			if($ProdPrice == "")
				$errormsg .= "<p class='errorClass'>Please enter price</p>";
			else
				$errormsg .= "<p class='errorClass'>Enter numbers with upto 2 decimal places only</p>";
		}
		if($ProdSalePrice == "" || !dotnumeric($ProdSalePrice)){
			$errorflag = False;
			if($ProdSalePrice == "")
				$errormsg .= "<p class='errorClass'>Please enter sale price</p>";
			else
				$errormsg .= "<p class='errorClass'>Enter numbers with upto 2 decimal places only</p>";
		}

		if(basename($_FILES['uploaded_file']['name']) == ""){
			$errorflag = False;
	        $errormsg .= "<p class='errorClass'>No file selected</p>";
		}else{
			$target_dir = "images/";
			$target_file = $target_dir . basename($_FILES['uploaded_file']['name']);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$check = getimagesize($_FILES['uploaded_file']['tmp_name']);
		    if($check == false) {
		    	$errorflag = False;
		        $errormsg .= "<p class='errorClass'>File is not an image</p>";
		    }
		    // Check if file already exists
			elseif (file_exists($target_file)) {
			    $errorflag = False;
		        $errormsg .= "<p class='errorClass'>File already exists</p>";
			}
			// Check file size
			elseif ($_FILES["uploaded_file"]["size"] > $_POST['MAX_FILE_SIZE']) {
			    $errorflag = False;
		        $errormsg .= "<p class='errorClass'>File is too large</p>";
			}
			// Allow certain file formats
			elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
			    $errorflag = False;
		        $errormsg .= "<p class='errorClass'>Only JPG, JPEG, PNG files are allowed</p>"; 
			}
		}

		/*
		First check if user is admin or not. If not admin, give an error.
		If admin, check password, if correct then proceed otherwise throw an error.
		*/
		if($_SESSION['isadmin'] == "X"){
			if($pwd != $_SESSION['pwd']){
				$errorflag = False;
		    	$errormsg .= "<p class='errorClass'>Incorrect password</p>";
			}
		}else{
			$errorflag = False;
		    $errormsg .= "<p class='errorClass'>You are not authorized to add or edit product</p>";
		}

		$instance = array('ProdName'=>$ProdName,
						  'ProdDesc'=>$ProdDesc,
						  'ProdQuantity'=>$ProdQuantity,
						  'ProdPrice'=>$ProdPrice,
						  'ProdSalePrice'=>$ProdSalePrice,
						  'ProdImage'=>$target_file,
						  'ProdId'=>$ProdId,
						  'onsale'=>$onsale,
						  'Error'=>$errorflag,
						  'ErrorMsg'=>$errormsg);
		return $instance;
	}//addSubmitValidation

	/*
	This function displays each individual product on index page which contains information such as
	image, name, description, quantity, price and saleprice if applicable. 	
	*/
	function itemsTemplate($item,$data,$x){

		foreach($data as $row){
			$imagepath = "/~ajb9736/756/project1/".$row->getProdImage();
			$item .= "<div class='individualitem'>";
			$item .= "<h3>{$row->getProdName()}</h3>";
			$item .= "<img class='aleft' src='".$imagepath."' alt='product image' />";
			$item .= "<p>{$row->getProdDesc()}</p>";
			if($x == "sale"){
				$item .= "<p><strong>Sale Price: </strong>\${$row->getProdSalePrice()} (Regularly: 
						 \${$row->getProdPrice()}) Only <strong>{$row->getProdQuantity()}</strong> left!</p>";
			}else{
				$item .= "<p><strong>Price: </strong>\${$row->getProdPrice()} Only <strong>{$row->getProdQuantity()}</strong> left!</p>";
			}
			$item .= "<form action='index.php' method='post'>";
			$item .= "<input type='hidden' name='item' value='".$row->getProdId()."'>";
			$item .= "<input type='submit' name='add' value='Add To Cart' class='btnBorder'>";
			$item .= "</form></div>";
		}

		return $item;
	}//itemsTemplate

?>