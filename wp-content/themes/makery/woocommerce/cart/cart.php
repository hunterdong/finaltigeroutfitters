<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

$cart=ThemexWoo::getCart();

wc_print_notices();
do_action('woocommerce_before_cart');
?>
<div id="content">
	<div class="eightcol column">
		<?php 
		if(!empty($cart)) {
		$title=get_the_title(ThemexUser::getShop(ThemexWoo::getUser()));
		?>
		<div class="element-title indented">
			<h2><?php echo __('Order from', 'makery').' '.$title; ?></h2>
		</div>
		<?php } ?>
		<form action="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" method="POST">
			<?php do_action('woocommerce_before_cart_table'); ?>
			<table class="shop_table cart primary" cellspacing="0">
				<thead>
					<tr>
						
						<th class="product-thumbnail">&nbsp;</th>
						<th class="product-name"><?php _e('Product', 'makery'); ?></th>
						<th class="product-price"><?php _e('Price', 'makery'); ?></th>
						<th class="product-quantity"><?php _e('Quantity', 'makery'); ?></th>
						<th class="product-subtotal"><?php _e('Total', 'makery'); ?></th>
						<th class="product-remove">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php do_action('woocommerce_before_cart_contents'); ?>
					<?php
					foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item){
						$_product=apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
						$product_id=apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

						if($_product && $_product->exists()&& $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)){
						?>
						<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
							<td class="product-thumbnail">
								<?php
								$thumbnail=apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

								if(! $_product->is_visible())
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
								?>
							</td>
							<td class="product-name">
								<?php
								if(! $_product->is_visible())
									echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
								else
									echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()), $cart_item, $cart_item_key);

								echo WC()->cart->get_item_data($cart_item);

								if($_product->backorders_require_notification()&& $_product->is_on_backorder($cart_item['quantity']))
									echo '<p class="backorder_notification">'.__('Available on backorder', 'makery').'</p>';
								?>
							</td>
							<td class="product-price">
								<?php
								echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
								?>
							</td>
							<td class="product-quantity">
								<?php
								if($_product->is_sold_individually()){
									$product_quantity=sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
								} else {
									$product_quantity=woocommerce_quantity_input(array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->backorders_allowed()? '' : $_product->get_stock_quantity(),
										'min_value'   => '0'
									), $_product, false);
								}

								echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
								?>
							</td>
							<td class="product-subtotal">
								<?php
								echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
								?>
							</td>
							<td class="product-remove">
								<?php
								echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="element-button small square secondary" title="%s"><span class="fa fa-times"></span></a>', esc_url(WC()->cart->get_remove_url($cart_item_key)), __('Remove this item', 'makery')), $cart_item_key);
								?>
							</td>
						</tr>
						<?php
						}
					}

					do_action('woocommerce_cart_contents');
					?>
					<tr>
						<td colspan="6" class="actions">
							<?php if(WC()->cart->coupons_enabled()){ ?>
								<div class="coupon">
									<label for="coupon_code"><?php _e('Coupon', 'makery'); ?>:</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e('Coupon Code', 'makery'); ?>" /> <input type="submit" class="button secondary" name="apply_coupon" value="<?php _e('Apply', 'makery'); ?>" />
									<?php do_action('woocommerce_cart_coupon'); ?>
								</div>
							<?php } ?>
							<input type="submit" class="button secondary" name="update_cart" value="<?php _e('Update', 'makery'); ?>" />
							<?php do_action('woocommerce_proceed_to_checkout'); ?>
							<?php wp_nonce_field('woocommerce-cart'); ?>
						</td>
					</tr>
					<?php do_action('woocommerce_after_cart_contents'); ?>
				</tbody>
			</table>
			<?php do_action('woocommerce_after_cart_table'); ?>
		</form>
		<?php		
		foreach($cart as $author=>$products) {
		$title=get_the_title(ThemexUser::getShop($author));
		?>
		<div class="element-title indented">
			<h2><?php echo __('Order from', 'makery').' '.$title; ?></h2>
		</div>
		<form action="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" method="POST">
			<table class="shop_table cart" cellspacing="0">
				<thead>
					<tr>					
						<th class="product-thumbnail">&nbsp;</th>
						<th class="product-name"><?php _e('Product', 'makery'); ?></th>
						<th class="product-price"><?php _e('Price', 'makery'); ?></th>
						<th class="product-quantity"><?php _e('Quantity', 'makery'); ?></th>
						<th class="product-subtotal"><?php _e('Total', 'makery'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach($products as $cart_item_key => $cart_item) {
						$cart_item['data']=wc_get_product($cart_item['product_id']);
						$_product=apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
						$product_id=apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
						
						if($_product && $_product->exists()&& $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)){
						?>
						<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
							<td class="product-thumbnail">
								<?php
								$thumbnail=apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

								if(! $_product->is_visible())
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
								?>
							</td>
							<td class="product-name">
								<?php
								if(! $_product->is_visible())
									echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
								else
									echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()), $cart_item, $cart_item_key);

								echo WC()->cart->get_item_data($cart_item);

								if($_product->backorders_require_notification()&& $_product->is_on_backorder($cart_item['quantity']))
									echo '<p class="backorder_notification">'.__('Available on backorder', 'makery').'</p>';
								?>
							</td>
							<td class="product-price">
								<?php
								echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
								?>
							</td>
							<td class="product-quantity">
								<?php
								if($_product->is_sold_individually()){
									$product_quantity=1;
								} else {
									$product_quantity=$cart_item['quantity'];
								}

								echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
								?>
							</td>
							<td class="product-subtotal">
								<?php
								echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
								?>
							</td>
						</tr>
						<?php
						}
					} 
					?>
					<tr>
						<td colspan="6" class="actions">
							<input type="submit" class="button secondary" name="remove" value="<?php _e('Remove', 'makery'); ?>" />
							<input type="submit" class="checkout-button button" name="add" value="<?php _e('Make Primary', 'makery'); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="cart_id" value="<?php echo $author; ?>" />
			<input type="hidden" name="woo_action" value="update_cart" />
		</form>
		<?php } ?>
	</div>
	<div class="fourcol column last">
		<?php do_action('woocommerce_cart_collaterals'); ?>		
	</div>
</div>
<?php do_action('woocommerce_after_cart'); ?>