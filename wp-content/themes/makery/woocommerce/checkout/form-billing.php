<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}
?>
<div class="woocommerce-billing-fields">
	<?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>
	<?php foreach($checkout->checkout_fields['billing'] as $key => $field): ?>
		<?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
	<?php endforeach; ?>
	<?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
	<?php do_action('woocommerce_before_order_notes', $checkout); ?>
	<?php if(apply_filters('woocommerce_enable_order_notes_field', get_option('woocommerce_enable_order_comments', 'yes')=== 'yes')): ?>
		<?php if(! WC()->cart->needs_shipping()|| WC()->cart->ship_to_billing_address_only()): ?>
			<h3><?php _e('Additional Information', 'makery'); ?></h3>
		<?php endif; ?>
		<?php foreach($checkout->checkout_fields['order'] as $key => $field): ?>
			<?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php do_action('woocommerce_after_order_notes', $checkout); ?>
	<?php if(! is_user_logged_in()&& $checkout->enable_signup): ?>
		<?php if($checkout->enable_guest_checkout): ?>
		<p class="form-row form-row-wide create-account">
			<input class="input-checkbox" id="createaccount" <?php checked((true === $checkout->get_value('createaccount')||(true === apply_filters('woocommerce_create_account_default_checked', false))), true)?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e('Create an account?', 'makery'); ?></label>
		</p>
		<?php endif; ?>
		<?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>
		<?php if(! empty($checkout->checkout_fields['account'])): ?>
		<div class="create-account">
			<p><?php _e('Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'makery'); ?></p>
			<?php foreach($checkout->checkout_fields['account'] as $key => $field): ?>
				<?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
			<?php endforeach; ?>
			<div class="clear"></div>
		</div>
		<?php endif; ?>
		<?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
	<?php endif; ?>
</div>