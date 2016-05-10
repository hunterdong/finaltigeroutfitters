<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product;

if(!$product->is_purchasable()) {
	return;
}

if(!$product->is_in_stock()) {
	$availability=$product->get_availability();
	$availability_html=empty($availability['availability'])?'':'<p class="stock '.esc_attr( $availability['class']).'">'.esc_html($availability['availability']).'</p>';

	echo apply_filters('woocommerce_stock_html', $availability_html, $availability['availability'], $product);
}
	
if($product->is_in_stock()) {
?>
<?php do_action('woocommerce_before_add_to_cart_form'); ?>
<div class="item-options clearfix">
	<?php woocommerce_template_single_price(); ?>
	<form class="cart" action="<?php echo themex_url(); ?>" method="POST" enctype="multipart/form-data">
	 	<?php do_action('woocommerce_before_add_to_cart_button'); ?>		
	 	<?php
		if(!$product->is_sold_individually()) {
			woocommerce_quantity_input(array(
				'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $product),
				'max_value' => apply_filters('woocommerce_quantity_input_max', $product->backorders_allowed()?'':$product->get_stock_quantity(), $product),
			));
		}	 			
	 	?>
		<a href="#" class="element-button element-submit item-cart primary"><?php echo $product->single_add_to_cart_text(); ?></a>
		<a href="#" class="element-button element-submit cart-button square primary" title="<?php echo $product->single_add_to_cart_text(); ?>"><span class="fa fa-shopping-cart large"></span></a>
		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>" />
		<?php do_action('woocommerce_after_add_to_cart_button'); ?>
	</form>
	<?php if(is_user_logged_in()) { ?>
	<?php if(!ThemexCore::checkOption('product_favorites')) { ?>
	<form class="element-form" method="POST" action="<?php echo AJAX_URL; ?>">
		<?php if(in_array($product->id, ThemexUser::$data['current']['favorites'])) { ?>
		<a href="#" title="<?php _e('Remove from Favorites', 'makery'); ?>" class="element-button element-submit secondary active" data-title="<?php _e('Add to Favorites', 'makery'); ?>"><span class="fa fa-heart"></span></a>
		<input type="hidden" name="user_action" class="toggle" value="remove_relation" data-value="add_relation" />
		<?php } else { ?>
		<a href="#" title="<?php _e('Add to Favorites', 'makery'); ?>" class="element-button element-submit secondary" data-title="<?php _e('Remove from Favorites', 'makery'); ?>"><span class="fa fa-heart"></span></a>
		<input type="hidden" name="user_action" class="toggle" value="add_relation" data-value="remove_relation" />
		<?php } ?>
		<input type="hidden" name="relation_type" value="product" />
		<input type="hidden" name="relation_id" value="<?php echo $product->id; ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>
	<?php } ?>
	<?php if(!ThemexCore::checkOption('product_questions')) { ?>
	<a href="#contact_form_<?php echo $product->id; ?>" class="element-button element-colorbox square secondary" title="<?php _e('Ask a Question', 'makery'); ?>"><span class="fa fa-comment"></span></a>
	<?php } ?>
	<?php } else { ?>
	<?php if(!ThemexCore::checkOption('product_favorites')) { ?>
	<a href="<?php echo ThemexCore::getURL('register'); ?>" title="<?php _e('Add to Favorites', 'makery'); ?>" class="element-button secondary"><span class="fa fa-heart"></span></a>
	<?php } ?>
	<?php if(!ThemexCore::checkOption('product_questions')) { ?>
	<a href="<?php echo ThemexCore::getURL('register'); ?>" class="element-button square secondary" title="<?php _e('Ask a Question', 'makery'); ?>"><span class="fa fa-comment"></span></a>
	<?php } ?>
	<?php } ?>
	<div class="site-popups hidden">
		<?php if(!ThemexCore::checkOption('product_questions')) { ?>
		<div id="contact_form_<?php echo $product->id; ?>">
			<div class="site-popup medium">
				<form class="site-form element-form" method="POST" action="<?php echo AJAX_URL; ?>">
					<div class="field-wrap">
						<input type="text" name="email" readonly="readonly" value="<?php echo esc_attr(ThemexUser::$data['current']['email']); ?>" />
					</div>
					<div class="field-wrap">
						<textarea name="question" cols="30" rows="5" placeholder="<?php _e('Question', 'makery'); ?>"></textarea>
					</div>
					<a href="#" class="element-button element-submit primary"><?php _e('Send Question', 'makery'); ?></a>				
					<input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
					<input type="hidden" name="shop_action" value="submit_question" />
					<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_shop" />
				</form>
			</div>
		</div>
		<?php } ?>
	</div>
	<!-- /popups -->
</div>
<?php do_action('woocommerce_after_add_to_cart_form'); ?>
<?php } ?>