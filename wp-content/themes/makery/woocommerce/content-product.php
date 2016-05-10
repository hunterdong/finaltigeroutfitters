<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product, $woocommerce_loop;

if(empty($woocommerce_loop['columns'])) {
	$woocommerce_loop['columns']=apply_filters('loop_shop_columns', 3);
}

if(empty($woocommerce_loop['loop'])) {
	$woocommerce_loop['loop']=0;
}

$width='four';
if($woocommerce_loop['columns']==4) {
	$width='three';
}
	
$shop=ThemexUser::getShop($post->post_author);
if(!$product || !$product->is_visible()) {
	return;
}

if(!isset($woocommerce_loop['view'])) {
	$woocommerce_loop['view']='grid';
}

$woocommerce_loop['loop']++;
if($woocommerce_loop['view']=='grid') {
?>
<div class="column <?php echo $width; ?>col <?php if($woocommerce_loop['loop']%$woocommerce_loop['columns']==0) { ?>last<?php } ?>">	
	<div class="item-preview">
		<div class="item-image">
			<?php do_action('woocommerce_before_shop_loop_item'); ?>
			<div class="image-wrap">
				<a href="<?php the_permalink(); ?>"><?php woocommerce_template_loop_product_thumbnail(); ?></a>
			</div>
			<?php do_action('woocommerce_before_shop_loop_item_title'); ?>		
		</div>
		<div class="item-details">			
			<h3 class="item-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if(!empty($shop)) { ?>
			<div class="author-name"><a href="<?php echo get_permalink($shop); ?>"><?php echo get_the_title($shop); ?></a></div>
			<?php } ?>
		</div>
		<footer class="item-footer clearfix">
			<?php do_action('woocommerce_after_shop_loop_item_title'); ?>
			<?php 
			if(ThemexUser::isMember($post->post_author)) {
				do_action('woocommerce_after_shop_loop_item'); 
			}
			?>
		</footer>
	</div>
</div>
<?php } else { ?>
<div class="item-full clearfix">
	<div class="column fourcol">
		<div class="item-preview">
			<div class="item-image">
				<div class="image-wrap">
					<a href="<?php the_permalink(); ?>"><?php woocommerce_template_loop_product_thumbnail(); ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="column eightcol last">
		<div class="element-title">
			<h1 itemprop="name" class="product_title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php woocommerce_template_single_rating(); ?>
		</div>
		<?php 
		if(ThemexUser::isMember($post->post_author)) {
			woocommerce_template_single_add_to_cart(); 
		}
		?>
		<div class="item-details">
			<?php the_excerpt(); ?>
		</div>
	</div>
</div>
<?php } ?>