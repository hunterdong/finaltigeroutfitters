<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product;

if(ThemexUser::isMember($post->post_author)) {

do_action('woocommerce_before_single_product');
if (post_password_required()) {
	echo get_the_password_form();
	return;
}
?>
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<aside class="column fourcol">
		<?php		
		do_action('woocommerce_before_single_product_summary');
		
		$show=false;
		ob_start();
		?>
		<div class="widget sidebar-widget">
			<div class="widget-title">
				<h4><?php _e('Details', 'makery'); ?></h4>
			</div>
			<div class="widget-content">
				<div class="item-attributes">
					<ul>
						<?php 
						if($product->enable_dimensions_display()) {
							if($product->has_weight()) {
							$show=true;
							?>
							<li class="clearfix">
								<div class="halfcol left"><?php _e('Weight', 'makery'); ?></div>
								<div class="halfcol right"><?php echo $product->get_weight().' '.esc_attr(get_option('woocommerce_weight_unit')); ?></div>
							</li>
							<?php
							}
							if($product->has_dimensions()) {
							$show=true;
							?>
							<li class="clearfix">
								<div class="halfcol left"><?php _e('Dimensions', 'makery'); ?></div>
								<div class="halfcol right"><?php echo $product->get_dimensions(); ?></div>
							</li>
							<?php
							}
						}
						
						foreach($product->get_attributes() as $attribute) {
						if(empty($attribute['is_visible']) || ($attribute['is_taxonomy'] && !taxonomy_exists($attribute['name']))) {
							continue;
						} else {
							$show=true;
						}
						?>
						<li class="clearfix">
							<div class="halfcol left"><?php echo wc_attribute_label($attribute['name']); ?></div>
							<div class="halfcol right">
							<?php
							if($attribute['is_taxonomy']) {
								$values=wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'names'));
								echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
							} else {
								$values=array_map('trim', explode(WC_DELIMITER, $attribute['value']));
								echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
							}
							?>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>							
			</div>
		</div>
		<?php
		if($show) {
			echo ob_get_clean();
		} else {
			ob_end_clean();
		}
		
		$shop=ThemexUser::getShop($post->post_author);		
		if(!empty($shop)) {
			ThemexShop::refresh($shop);
			get_template_part('module', 'shop');
		}
		
		ThemexSidebar::renderSidebar('product');
		?>
	</aside>
	<div class="item-full column eightcol last">
		<?php do_action('woocommerce_single_product_summary'); ?>
	</div>
	<div class="clear"></div>
	<?php do_action('woocommerce_after_single_product_summary'); ?>
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</div>
<?php do_action('woocommerce_after_single_product'); ?>
<?php } else { ?>
<h3><?php _e('This product is hidden because of the membership limit.','makery'); ?></h3>
<p><?php _e('Sorry, it is hidden because of the membership limit, upgrade or try removing a few products.','makery'); ?></p>
<?php } ?>