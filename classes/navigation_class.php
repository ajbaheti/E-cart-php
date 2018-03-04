<?php
	
	if(!class_exists('NavigateLinks')){

		/*this class is to create menu bar which is called from header with call to function 'navigate'*/
		class NavigateLinks{

			public function navigate($items_array){
				$nav = "<ul id='sddm'>";

				foreach($items_array as $item){
					$nav .= "<li><a href='".$item['url']."'>".$item['text']."</a></li>";
				}

				$nav .= "</ul>";

				return $nav;
			}
		}
	}
?>