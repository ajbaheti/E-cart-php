<?php

	if(!class_exists('cart')){
		
		/*this is product class to access all product table columns*/
		class product{

			private $ProdId, $ProdName, $ProdDesc, $ProdQuantity;
			private $ProdPrice, $ProdSalePrice, $ProdImage, $onsale;

			/*accessor for product id*/
			function getProdId(){
				return $this->ProdId;
			}

			/*accessor for product name*/
			function getProdName(){
				return $this->ProdName;
			}

			/*accessor for product description*/
			function getProdDesc(){
				return $this->ProdDesc;
			}

			/*accessor for product quantity*/
			function getProdQuantity(){
				return $this->ProdQuantity;
			}

			/*accessor for product price*/
			function getProdPrice(){
				return $this->ProdPrice;
			}

			/*accessor for product sale price*/
			function getProdSalePrice(){
				return $this->ProdSalePrice;
			}

			/*accessor for product image path*/
			function getProdImage(){
				return $this->ProdImage;
			}

			/*accessor for product onsale flag*/
			function getonsale(){
				return $this->onsale;
			}

			/*mutator for product sale price*/
			function setProdSalePrice($x=0){
				$this->ProdSalePrice = $x;
			}
		}//end of class
	}

?>