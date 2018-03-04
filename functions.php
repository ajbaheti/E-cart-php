<?php
	include('classes/navigation_class.php');	/*include class as we are accessing its method*/
	
	function menu_navigation(){
		$dtm = new NavigateLinks();		/*create class object*/

		$items_array = array(			/*create all navigation items with name and their url*/
							array('text'=>'Home','url'=>'index.php'),
							array('text'=>'Cart','url'=>'cart.php'),
							array('text'=>'Admin','url'=>'admin.php'),
							array('text'=>'Log Out','url'=>'logout.php')
						);

		return $dtm->navigate($items_array);	/*call navigation method which will create actual menu bar div*/
	}//menu_navigation
?>