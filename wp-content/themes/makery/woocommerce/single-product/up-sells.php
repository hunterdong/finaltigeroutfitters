<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product, $woocommerce_loop;

$upsells=$product->get_upsells();
if(sizeof($upsells)==0) {
	return;
}

$meta_query=WC()->query->get_meta_query();
$args=array(
	'post_type' => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows' => 1,
	'posts_per_page' => $posts_per_page,
	'orderby' => $orderby,
	'post__in' => $upsells,
	'post__not_in' => array($product->id),
	'meta_query' => $meta_query,
);

$products=new WP_Query($args);
$woocommerce_loop['columns']=$columns;

if($posts_per_page!=0 && $products->have_posts()) {
?>
<div class="item-related clearfix">
	<div class="element-title indented">
		<h1><?php _e('Additional Items', 'makery'); ?></h1>
	</div>
	<?php woocommerce_product_loop_start(); ?>
		<?php while($products->have_posts()) { ?>
		<?php $products->the_post(); ?>
		<?php wc_get_template_part('content', 'product'); ?>
		<?php } ?>
	<?php woocommerce_product_loop_end(); ?>

</div>
<?php } ?>
<?php wp_reset_postdata(); ?>