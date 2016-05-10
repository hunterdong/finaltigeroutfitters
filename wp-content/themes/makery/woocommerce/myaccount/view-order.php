<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}
?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title">
		<h1><?php _e('View Order', 'makery'); ?></h1>
	</div>
	<?php wc_print_notices(); ?>
	<?php do_action('woocommerce_view_order', $order_id); ?>
</div>
<?php get_sidebar('profile-right'); ?>