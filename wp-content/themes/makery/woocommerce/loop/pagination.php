<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $wp_query, $shop;

if($wp_query->max_num_pages<=1) {
	return;
}
?>
<nav class="pagination clearfix">
	<?php
	$out=paginate_links(apply_filters('woocommerce_pagination_args', array(
		'base' => esc_url(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false)))),
		'format' => '',
		'current' => max(1, get_query_var('paged')),
		'total' => $wp_query->max_num_pages,
		'prev_text' => '',
		'next_text' => '',
		'type' => 'plain',
		'end_size' => 3,
		'mid_size' => 3,
	)));
	
	if(isset($shop)) {
		$out=str_replace('/page/', '/', $out);
		$out=str_replace('/'.get_query_var('paged').'/', '/', $out);
	}
	
	echo $out;
	?>
</nav>
