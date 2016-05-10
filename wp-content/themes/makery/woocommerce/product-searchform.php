<?php 
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $post;

$author=themex_array('search_author', $_GET);
if(is_singular('shop')) {
	$author=$post->post_author;
}
?>
<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search Products&hellip;', 'placeholder', 'makery'); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x('Search for:', 'label', 'makery'); ?>" />
	<input type="submit" value="<?php echo esc_attr_x('Search', 'submit button', 'makery'); ?>" />
	<input type="hidden" name="post_type" value="product" />
	<?php if(!empty($author)) { ?>
	<input type="hidden" name="search_author" value="<?php echo esc_attr($author); ?>" />
	<?php } ?>
</form>