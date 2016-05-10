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
		<h1><?php _e('View Order', 'makery' ); ?></h1>
	</div>
	<?php if($order){ ?>
		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'makery' ); ?></p>
			<p><?php
				if ( is_user_logged_in() )
					_e( 'Please attempt your purchase again or go to your account page.', 'makery' );
				else
					_e( 'Please attempt your purchase again.', 'makery' );
			?></p>
			<p>
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'makery' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'makery' ); ?></a>
				<?php endif; ?>
			</p>
		<?php else : ?>
			<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'makery' ), $order ); ?></p>
		<?php endif; ?>
		<div class="method_details">
			<?php do_action('woocommerce_thankyou_' . $order->payment_method, $order->id); ?>
			<?php do_action('woocommerce_thankyou', $order->id); ?>
		</div>
	<?php } else { ?>
		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'makery' ), null ); ?></p>
	<?php } ?>
</div>
<?php remove_filter('the_title', 'wc_page_endpoint_title'); ?>
<?php get_sidebar('profile-right'); ?>