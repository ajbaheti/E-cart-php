<?php
	
	if(!class_exists('cart')){

		/*class to access all cart table columns*/
		class cart{

			private $ProdId, $ProdName, $ProdDesc, $ProdQuantity, $ProdPrice;

			/*accessor for cart product id*/
			function getProdId(){
				return $this->ProdId;
			}

			/*accessor for cart product name*/
			function getProdName(){
				return $this->ProdName;
			}

			/*accessor for cart product description*/
			function getProdDesc(){
				return $this->ProdDesc;
			}

			/*accessor for cart product quantity*/
			function getProdQuantity(){
				return $this->ProdQuantity;
			}

			/*accessor for cart product price*/
			function getProdPrice(){
				return $this->ProdPrice;
			}
		}//end of class
	}
?>