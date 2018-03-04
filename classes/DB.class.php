<?php
	
if(!class_exists('DB')){
	class DB{
	
		private $dbh;
		
		/*constructor of class to connect with database*/
		function __construct(){
			require_once("dbInfo.php");	/*include this file as it contains all credential details*/	
		
			try{	
				$this->dbh = new PDO("mysql:host=$host;dbname=$db",$user,$password);
				/*change error reporting*/
				$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			}catch(PDOException $e){
				die("Bad database connection");
			}
		}//constructor

		/*
		This function takes username and password as input. Performs query on database to check if any user exists with
		provided username and password	and returns the result of query
		*/
		function checkLogin($user,$pwd){

			try{
				$stmt = $this->dbh->prepare("select * from users where username=:user and password=:pwd");
				$stmt->bindParam("user",$user,PDO::PARAM_STR);
				$stmt->bindParam("pwd",$pwd,PDO::PARAM_STR);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_ASSOC);

				return $data;

			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//checkLogin

		/*
		This function takes only user as input. It checks if provided user exists in the database or not. This is
		for session validation. If one tries to access index or any other page without login, this check will be
		performed and you will be redirected to login page.
		*/
		function checkUser($user){

			try{
				$stmt = $this->dbh->prepare("select username from users where username = :user");
				$stmt->bindParam("user",$user,PDO::PARAM_STR);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_ASSOC);

				return $data;

			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//checkUser

		/*
		This function performs select * query on product table to get all product details.
		*/
		function getAllProducts(){
			
			try{
				include_once "product.class.php";
				$data = Array();
				$stmt = $this->dbh->prepare("select * from products");
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS,"product");
				
				while($product = $stmt->fetch()){
					$data[] = $product;
				}
				return $data;
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//getProducts

		/*
		This function takes a string as input.
		First it gets all products from product table.
		Then divides those products in sale and catalogue categories in two different arrays based on SALEPRICE attribute
		and also keeps track of their counts respectively.
		If string is 'countsale', it returns count of number of items on sale
		if string is 'countother', it returns count of number of items not on sale
		if string is 'saleitems', it returns array of sale items
		otherwise it returns array of non-sale items.
		*/
		function getItems($x){

			$saleCount = 0;
			$otherCount = 0;

			$data = $this->getAllProducts();
			
			foreach($data as $row){
				if($row->getProdSalePrice() != 0){
					$saleCount++;
					$datasale[] = $row;
				}else{
					$otherCount++;
					$dataOther[] = $row;
				}
			}
			
			if($x == 'countsale'){
				return $saleCount;
			}elseif($x == 'countother'){
				return $otherCount;
			}elseif($x == 'saleitems'){
				return $datasale;
			}else{
				return $dataOther;
			}
		}//getItems

		/*
		This function takes product name, desciption, price, saleprice, quantity and image as input.
		If sale price is 0, onsale flag is set to 'X' otherwise blank. This is to meet constraint of >=3 & <=5.
		All the inputs are inserted in product table in a new row and returns newly inserted id.
		*/
		function insertProduct($ProdName,$ProdDesc,$ProdPrice,$ProdQuantity,$ProdSalePrice,$ProdImage){

			try{
				if($ProdSalePrice != 0)
					$onsale = "X";
				else
					$onsale = "";

				$stmt = $this->dbh->prepare("insert into products (ProdName,ProdDesc,ProdPrice,ProdQuantity,ProdSalePrice,ProdImage,onsale)
							values (:ProdName,:ProdDesc,:ProdPrice,:ProdQuantity,:ProdSalePrice,:ProdImage,:onsale)");
				$stmt->bindParam("ProdName",$ProdName,PDO::PARAM_STR);
				$stmt->bindParam("ProdDesc",$ProdDesc,PDO::PARAM_STR);
				$stmt->bindParam("ProdPrice",$ProdPrice,PDO::PARAM_STR);
				$stmt->bindParam("ProdQuantity",$ProdQuantity,PDO::PARAM_INT);
				$stmt->bindParam("ProdSalePrice",$ProdSalePrice,PDO::PARAM_STR);
				$stmt->bindParam("ProdImage",$ProdImage,PDO::PARAM_STR);
				$stmt->bindParam("onsale",$onsale,PDO::PARAM_STR);
				$stmt->execute();
				
				return $this->dbh->lastInsertId();				
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//insertProduct

		/*
		This function takes userid as an input.
		It selects all the products in cart table for provided userid and returns them.
		*/
		function getCart($uid){

			try{				
				include_once "cart.class.php";
				$data = Array();
				$stmt = $this->dbh->prepare("select * from cart where userid  = :uid");
				$stmt->bindParam("uid",$uid,PDO::PARAM_INT);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS,"cart");
				
				while($cart = $stmt->fetch()){
					$data[] = $cart;
				}

				return $data;

			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//getCart

		/*
		This function takes product id, name, description, price, saleprice, quantity and image path as input.
		If saleprice is != 0, then set onsale flag to X.
		It selects row with provided product id and updates all fields with provided values.
		*/
		function updateProduct($ProdId,$ProdName,$ProdDesc,$ProdPrice,$ProdQuantity,$ProdSalePrice,$ProdImage){
			try{

				if($ProdSalePrice != 0)
					$onsale = "X";
				else
					$onsale = "";
				//echo "$ProdId ,$ProdName ,$ProdDesc ,$ProdPrice ,$ProdQuantity ,$ProdSalePrice ,$ProdImage";
				$stmt = $this->dbh->prepare("update products set ProdName = :ProdName,
											 ProdDesc = :ProdDesc,
											 ProdPrice = :ProdPrice,
											 ProdQuantity = :ProdQuantity,
											 ProdSalePrice = :ProdSalePrice,
											 ProdImage = :ProdImage,
											 onsale = :onsale where ProdId = :pid");
				$stmt->bindParam("ProdName",$ProdName,PDO::PARAM_STR);
				$stmt->bindParam("ProdDesc",$ProdDesc,PDO::PARAM_STR);
				$stmt->bindParam("ProdPrice",$ProdPrice,PDO::PARAM_STR);
				$stmt->bindParam("ProdQuantity",$ProdQuantity,PDO::PARAM_INT);
				$stmt->bindParam("ProdSalePrice",$ProdSalePrice,PDO::PARAM_STR);
				$stmt->bindParam("ProdImage",$ProdImage,PDO::PARAM_STR);
				$stmt->bindParam("onsale",$onsale,PDO::PARAM_STR);
				$stmt->bindParam("pid",$ProdId,PDO::PARAM_INT);
				$stmt->execute();

			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//updateProduct

		/*
		This function takes product id as an input and returns all product details of given product id.
		*/
		function getSingleProduct($id){
			try{
				include_once "product.class.php";				
				$stmt = $this->dbh->prepare("select * from products where ProdId = :id");
				$stmt->bindParam("id",$id,PDO::PARAM_INT);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_CLASS,"product");
				$row = $stmt->fetch();

				return $row;
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//getSingleProduct

		/*
		This function takes user id and product id as input and inserts product in cart table against provided user id.
		First it retrieves all details of provided product id.
		If returned value is not greater than 0, then it returns error message.
		Otherwise quantity is updated (reduced by 1) in product table.
		Then if product already exists in cart, count is increased by 1 otherwise new row is added with (quantity)count as 1.
		*/
		function insertCart($id,$uid){
			try{				
				
				$row = $this->getSingleProduct($id);
				if($row->getProdQuantity() > 0){	//if quantity is greater than 0

					//update quantity in product table
					$quant = $row->getProdQuantity()-1;
					$stmt1 = $this->dbh->prepare("update products set ProdQuantity = :quant where ProdId = :pid");
					$stmt1->bindParam("pid",$id,PDO::PARAM_INT);
					$stmt1->bindParam("quant",$quant,PDO::PARAM_INT);
					$stmt1->execute();

					//check if product already exists in cart, if yes just change quantity
					$stmt2 = $this->dbh->prepare("select * from cart where ProdId = :pid and userid = :uid");
					$stmt2->bindParam("pid",$id,PDO::PARAM_INT);
					$stmt2->bindParam("uid",$uid,PDO::PARAM_INT);
					$stmt2->execute();
					$stmt2->setFetchMode(PDO::FETCH_CLASS,"product");
					$rowdata = $stmt2->fetch();
					
					if($rowdata){
						$itemcount = $rowdata->getProdQuantity() + 1;
						$stmt1 = $this->dbh->prepare("update cart set ProdQuantity = :itemcount where ProdId = :pid and userid = :uid");
						$stmt1->bindParam("pid",$id,PDO::PARAM_INT);
						$stmt1->bindParam("uid",$uid,PDO::PARAM_INT);
						$stmt1->bindParam("itemcount",$itemcount,PDO::PARAM_INT);
						$stmt1->execute();

						return "The item quantity has been updated in the cart";
					}else{
						//insert row in cart table
						$price = 0;
						$val = 1;
						if($row->getProdSalePrice() != 0){	//if item on sale, add sale price otherwise actual price
							$price = $row->getProdSalePrice();
						}else{
							$price = $row->getProdPrice();
						}

						$stmt3 = $this->dbh->prepare("insert into cart (ProdId,userid,ProdName,ProdDesc,ProdQuantity,ProdPrice)
								values (:ProdId,:userid,:ProdName,:ProdDesc,:ProdQuantity,:ProdPrice)");
						/*$stmt3->bindParam("pid",$row->getProdId(),PDO::PARAM_INT);
						$stmt3->bindParam("userid",$uid,PDO::PARAM_INT);
						$stmt3->bindParam("ProdName",$row->getProdName(),PDO::PARAM_STR);
						$stmt3->bindParam("ProdDesc",$row->getProdDesc(),PDO::PARAM_STR);
						$stmt3->bindParam("ProdQuantity",$val,PDO::PARAM_INT);
						$stmt3->bindParam("ProdPrice",$price,PDO::PARAM_STR);				
						$stmt3->execute();*/
						$stmt3->execute(array("ProdId"=>$row->getProdId(),
											  "userid"=>$uid,
										 	  "ProdName"=>$row->getProdName(),
										 	  "ProdDesc"=>$row->getProdDesc(),
										 	  "ProdPrice"=>$price,
										 	  "ProdQuantity"=>1
											));
						return "The item has been added to the cart";
					}				
				}else{
					return "Sorry, item you're trying to buy is out of stock";
				}
				
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//insertCart

		/*
		This function takes userid as input.
		First it brings all cart items details for provided userid and then deletes these items from cart table.
		*/
		function deleteCart($uid){

			try{				
				$data = $this->getCart($uid);
				if(count($data) > 0){
					foreach($data as $row){
						$stmt = $this->dbh->prepare("delete from cart where ProdId = :id and userid = :uid");
						$stmt->bindParam("id",$row->getProdId(),PDO::PARAM_INT);
						$stmt->bindParam("uid",$uid,PDO::PARAM_INT);
						$stmt->execute();
					}
				}
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//deleteCart

		/*
		This function takes a string as input which has information of div.
		First it gets count of non-sale items, sets limit to 5 and performs paging calculations and defines 
		previous,next, current page links.
		Then it performs query for nonsale items with limit and offset values calculated.
		After that it calls 'itemstemplate' function which adds each product details to div and this is appended
		to string taken as input. Finally page div is appended to string and this string is returned.
		*/
		function getPages($otheritem){

			try{

				$total = $this->getItems("countother");
				$limit = 5;
				$pages = ceil($total / $limit);
				$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, 
								array('options' => array('default'=>1,'min_range'=>1),)
							));
				$offset = ($page - 1) * $limit;
				$start = $offset + 1;
    			$end = min(($offset + $limit), $total);
    			$prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
    			$nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
				//echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';

				$stmt = $this->dbh->prepare("select * from products where ProdSalePrice = 0 limit :limit offset :offset");
				$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    			$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
				$stmt->execute();

				if ($stmt->rowCount() > 0) {
			        $stmt->setFetchMode(PDO::FETCH_CLASS,"product");
			        $iterator = new IteratorIterator($stmt);
			        $otheritem = itemsTemplate($otheritem,$iterator,"other");
			        $otheritem .= "<div id='paging'><p>$prevlink  Page  $page  of $pages pages displaying $start - $end of $total results $nextlink</p></div>";

			        return $otheritem;
			    }
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}//getPages

	}//end of class DB
}

?>