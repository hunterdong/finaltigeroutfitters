<aside class="column fourcol last">	
	<?php
	ThemexShop::refresh(ThemexUser::$data['active']['shop'], true);
	if(!ThemexCore::checkOption('shop_multiple') && ThemexShop::$data['status']=='publish') {
		get_template_part('module', 'shop');
	}
	
	if(!empty(ThemexShop::$data['reviews'])) {
		get_template_part('module', 'reviews');
	}
	
	if(!ThemexCore::checkOption('product_favorites')) {
		get_template_part('module', 'favorites');
	}
	
	if(ThemexUser::isProfile() && !ThemexCore::checkOption('shop_favorites')) {
		get_template_part('module', 'updates');
	} 
	
	if(!ThemexCore::checkOption('shop_multiple') && ThemexShop::$data['status']!='publish') {
		get_template_part('module', 'shop');
	}
	
	ThemexSidebar::renderSidebar('profile');
	?>
</aside>