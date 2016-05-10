<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}
?>
<?php do_action('woocommerce_before_add_to_cart_button'); ?>
<div class="item-options clearfix">
	<?php woocommerce_template_single_price(); ?>
	<a href="<?php echo esc_url($product_url); ?>" rel="nofollow" class="single_add_to_cart_button element-button primary"><?php echo $button_text; ?></a>
</div>
<?php do_action('woocommerce_after_add_to_cart_button'); ?>