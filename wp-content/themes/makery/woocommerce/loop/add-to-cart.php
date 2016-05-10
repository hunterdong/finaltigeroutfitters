<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product;
?>
<div class="item-options right">
	<form class="element-form" method="POST" action="<?php echo AJAX_URL; ?>">
		<?php
		echo apply_filters('woocommerce_loop_add_to_cart_link',
			sprintf('<a href="%s" title="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s %s"><span class="fa fa-shopping-cart large"></span></a>',
				esc_url($product->add_to_cart_url()),
				esc_html($product->add_to_cart_text()),
				esc_attr($product->id),
				esc_attr($product->get_sku()),
				esc_attr(isset($quantity)? $quantity : 1),
				$product->is_purchasable() && $product->is_in_stock()?'add_to_cart_button':'hidden',
				esc_attr($product->product_type),
				esc_attr($product->is_type('variable')?'':'ajax_add_to_cart')
			),
		$product);
		?>
		<?php if(!ThemexCore::checkOption('product_favorites')) { ?>
		<?php if(is_user_logged_in()) { ?>
			<?php if(in_array($product->id, ThemexUser::$data['current']['favorites'])) { ?>
			<a href="#" title="<?php _e('Remove from Favorites', 'makery'); ?>" class="element-submit primary active" data-title="<?php _e('Add to Favorites', 'makery'); ?>"><span class="fa fa-heart small"></span></a>
			<input type="hidden" name="user_action" class="toggle" value="remove_relation" data-value="add_relation" />
			<?php } else { ?>
			<a href="#" title="<?php _e('Add to Favorites', 'makery'); ?>" class="element-submit primary" data-title="<?php _e('Remove from Favorites', 'makery'); ?>"><span class="fa fa-heart small"></span></a>
			<input type="hidden" name="user_action" class="toggle" value="add_relation" data-value="remove_relation" />
			<?php } ?>
		<?php } else { ?>
			<a href="<?php echo ThemexCore::getURL('register'); ?>" title="<?php _e('Add to Favorites', 'makery'); ?>" class="primary"><span class="fa fa-heart small"></span></a>
		<?php } ?>
		<?php } ?>
		<input type="hidden" name="relation_type" value="product" />
		<input type="hidden" name="relation_id" value="<?php echo $product->id; ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>
</div>