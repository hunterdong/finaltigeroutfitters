<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $woocommerce_loop, $layout;

$view=ThemexCore::getOption('products_view', 'grid');
if(!isset($layout)) {
	$layout=ThemexCore::getOption('products_layout', 'right');
}

$woocommerce_loop['view']=themex_value('view', $_GET, $view);
$woocommerce_loop['columns']=4;
if($layout!='full') {
	$woocommerce_loop['columns']=3;
}

get_header('shop');

if($layout=='left') {
?>
<aside class="column fourcol">
	<?php ThemexSidebar::renderSidebar('products', true); ?>
</aside>
<div class="column eightcol last">
<?php } else if($layout=='right') { ?>
<div class="column eightcol">
<?php } else { ?>
<div class="fullcol">
<?php } ?>
	<?php do_action('woocommerce_before_main_content'); ?>
	<?php if(apply_filters('woocommerce_show_page_title', true)) { ?>
		<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
	<?php } ?>
	<?php do_action('woocommerce_archive_description'); ?>
	<?php if(have_posts()) { ?>
		<?php wc_print_notices(); ?>
		<div class="items-toolbar clearfix">
			<?php do_action('woocommerce_before_shop_loop'); ?>			
		</div>		
		<?php woocommerce_product_loop_start(); ?>
			<?php woocommerce_product_subcategories(); ?>
			<?php 
			while(have_posts()) {
				the_post();				
				wc_get_template_part('content', 'product');
			}
			?>
		<?php woocommerce_product_loop_end(); ?>
		<?php do_action('woocommerce_after_shop_loop'); ?>
	<?php } else if(!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) { ?>
		<?php wc_get_template('loop/no-products-found.php'); ?>
	<?php } ?>
	<?php do_action('woocommerce_after_main_content'); ?>
</div>
<?php if($layout=='right') { ?>
<aside class="column fourcol last">
	<?php ThemexSidebar::renderSidebar('products', true); ?>
</aside>
<?php } ?>
<?php if(!isset($woocommerce_loop['single'])) { ?>
<?php get_footer('shop'); ?>
<?php } ?>