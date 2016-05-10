<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')){
    exit;
}

global $product, $post;
?>
<?php do_action('woocommerce_before_add_to_cart_form'); ?>
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr(json_encode($available_variations))?>">
	<?php do_action('woocommerce_before_variations_form'); ?>
	<?php if(!empty($available_variations)) { ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach($attributes as $attribute_name => $options): ?>
					<tr>
						<th class="label"><label for="<?php echo sanitize_title($attribute_name); ?>"><?php echo wc_attribute_label($attribute_name); ?></label></th>
						<td class="value">
							<div class="element-select">
								<span></span>
								<?php
								$selected=isset($_REQUEST[ 'attribute_'.sanitize_title($attribute_name)])?wc_clean($_REQUEST['attribute_'.sanitize_title($attribute_name)]):$product->get_variation_default_attribute($attribute_name);
								wc_dropdown_variation_attribute_options(array('options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected));
								?>
							</div>							
						</td>
					</tr>
		        <?php endforeach; ?>
			</tbody>
		</table>
		<div class="item-options clearfix">
			<?php do_action('woocommerce_before_add_to_cart_button'); ?>
			<div class="single_variation_wrap clearfix" style="display:none;">
				<?php do_action('woocommerce_before_single_variation'); ?>
				<?php if(strpos($product->get_price_html(), '&ndash')==false) { ?>
				<div class="item-price"><?php echo $product->get_price_html(); ?></div>
				<?php } else { ?>
				<div class="single_variation item-price"></div>
				<?php } ?>
				<?php woocommerce_quantity_input(); ?>
				<a href="#" class="element-button element-submit item-cart primary"><?php echo $product->single_add_to_cart_text(); ?></a>
				<a href="#" class="element-button element-submit cart-button square primary" title="<?php echo $product->single_add_to_cart_text(); ?>"><span class="fa fa-shopping-cart large"></span></a>
				<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
				<input type="hidden" name="product_id" value="<?php echo esc_attr($post->ID); ?>" />
				<input type="hidden" name="variation_id" value="" />
				<?php do_action('woocommerce_after_single_variation'); ?>
				<?php if(is_user_logged_in()) { ?>
					<?php if(!ThemexCore::checkOption('product_favorites')) { ?>
						<?php if(in_array($product->id, ThemexUser::$data['current']['favorites'])) { ?>
						<a href="#favorite_form" title="<?php _e('Remove from Favorites', 'makery'); ?>" class="element-button element-submit secondary active" data-title="<?php _e('Add to Favorites', 'makery'); ?>"><span class="fa fa-heart"></span></a>
						<?php } else { ?>
						<a href="#favorite_form" title="<?php _e('Add to Favorites', 'makery'); ?>" class="element-button element-submit secondary" data-title="<?php _e('Remove from Favorites', 'makery'); ?>"><span class="fa fa-heart"></span></a>
						<?php } ?>
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
			</div>
			<?php do_action('woocommerce_after_add_to_cart_button'); ?>			
		</div>
	<?php } else { ?>
		<p class="stock out-of-stock secondary"><?php _e('This product is currently out of stock and unavailable.', 'makery'); ?></p>
	<?php } ?>
	<?php do_action('woocommerce_after_variations_form'); ?>
</form>
<?php if(!ThemexCore::checkOption('product_favorites')) { ?>
<form id="favorite_form" class="element-form" method="POST" action="<?php echo AJAX_URL; ?>">
	<?php if(in_array($product->id, ThemexUser::$data['current']['favorites'])) { ?>
	<input type="hidden" name="user_action" class="toggle" value="remove_relation" data-value="add_relation" />
	<?php } else { ?>
	<input type="hidden" name="user_action" class="toggle" value="add_relation" data-value="remove_relation" />
	<?php } ?>
	<input type="hidden" name="relation_type" value="product" />
	<input type="hidden" name="relation_id" value="<?php echo $product->id; ?>" />
	<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
</form>
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
<?php do_action('woocommerce_after_add_to_cart_form'); ?>