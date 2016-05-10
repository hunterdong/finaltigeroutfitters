<?php 
if(get_query_var('shop_category') || themex_search('shop')) {
	get_template_part('template', 'shops');
} else {
	get_template_part('template', 'posts');
}
?>