<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}
?>
<tr class="shipping">
	<th>
	<?php
	if($show_package_details) {
		printf(__('Shipping #%d', 'makery'), $index+1);
	} else {
		_e('Shipping and Handling', 'makery');
	}
	?>
	</th>
	<td>
		<?php if(!empty($available_methods)): ?>
			<?php if(1===count($available_methods)):
				$method=current($available_methods);
				echo wp_kses_post(wc_cart_totals_shipping_method_label($method)); ?>
				<input type="hidden" name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" value="<?php echo esc_attr($method->id); ?>" class="shipping_method" />
			<?php elseif(get_option('woocommerce_shipping_method_format')=== 'select'): ?>
				<select name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" class="shipping_method">
					<?php foreach($available_methods as $method): ?>
						<option value="<?php echo esc_attr($method->id); ?>" <?php selected($method->id, $chosen_method); ?>><?php echo wp_kses_post(wc_cart_totals_shipping_method_label($method)); ?></option>
					<?php endforeach; ?>
				</select>
			<?php else: ?>
				<ul id="shipping_method">
					<?php foreach($available_methods as $method): ?>
						<li>
							<input type="radio" name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>_<?php echo sanitize_title($method->id); ?>" value="<?php echo esc_attr($method->id); ?>" <?php checked($method->id, $chosen_method); ?> class="shipping_method" />
							<label for="shipping_method_<?php echo $index; ?>_<?php echo sanitize_title($method->id); ?>"><?php echo wp_kses_post(wc_cart_totals_shipping_method_label($method)); ?></label>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php elseif (!WC()->customer->has_calculated_shipping()): ?>
			<?php echo wpautop(__('Shipping costs will be calculated once you have provided your address.', 'makery')); ?>
		<?php else: ?>
			<?php echo apply_filters(is_cart()?'woocommerce_cart_no_shipping_available_html':'woocommerce_no_shipping_available_html', wpautop(__('There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'makery'))); ?>
		<?php endif; ?>
		<?php if($show_package_details): ?>
			<?php
			foreach($package['contents'] as $item_id => $values){
				if($values['data']->needs_shipping()){
					$product_names[]=$values['data']->get_title().' &times;'.$values['quantity'];
				}
			}

			echo '<p class="woocommerce-shipping-contents"><small>'.__('Shipping', 'makery').': '.implode(', ', $product_names).'</small></p>';
			?>
		<?php endif; ?>
	</td>
</tr>