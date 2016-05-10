<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

$customer_orders=get_posts(apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys(wc_get_order_statuses()),
)));
?>
<div class="element-title indented">
	<h1><?php echo apply_filters('woocommerce_my_account_my_orders_title', __('My Orders', 'makery')); ?></h1>
</div>
<?php if(empty($customer_orders)) { ?>
<span class="secondary"><?php _e('No orders made yet.', 'makery'); ?></span>
<?php } else { ?>
<table class="shop_table my_account_orders">
	<thead>
		<tr>
			<th class="order-number"><span class="nobr">&#8470;</span></th>
			<th class="order-date"><span class="nobr"><?php _e( 'Date', 'makery' ); ?></span></th>
			<th class="order-status"><span class="nobr"><?php _e( 'Status', 'makery' ); ?></span></th>
			<th class="order-total"><span class="nobr"><?php _e( 'Total', 'makery' ); ?></span></th>
			<th class="order-actions">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $customer_orders as $customer_order ) {
	$order = wc_get_order($customer_order);
	$order->populate( $customer_order );
	$item_count = $order->get_item_count();
	?>
	<tr class="order">
		<td class="order-number">
			<a href="<?php echo $order->get_view_order_url(); ?>">
				<?php echo $order->get_order_number(); ?>
			</a>
		</td>
		<td class="order-date">
			<time datetime="<?php echo date('Y-m-d', strtotime($order->order_date)); ?>" title="<?php echo esc_attr(strtotime($order->order_date)); ?>"><?php echo date_i18n(get_option( 'date_format' ), strtotime($order->order_date)); ?></time>
		</td>
		<td class="order-status" style="text-align:left; white-space:nowrap;">
			<?php echo wc_get_order_status_name($order->get_status()); ?>
		</td>
		<td class="order-total">
			<?php echo $order->get_formatted_order_total(); ?>
		</td>
		<td class="order-actions">
			<?php
			$actions=array();

			if(in_array( $order->get_status(), apply_filters('woocommerce_valid_order_statuses_for_payment', array('pending', 'failed'), $order))){
				$actions['pay']=array(
					'url'  => $order->get_checkout_payment_url(),
					'name' => __('Pay', 'makery'),
					'class' => 'shopping-cart',
				);
			}

			if(in_array($order->get_status(), apply_filters('woocommerce_valid_order_statuses_for_cancel', array('pending', 'failed'), $order))){
				$actions['cancel']=array(
					'url'  => $order->get_cancel_order_url(get_permalink(wc_get_page_id('myaccount'))),
					'name' => __('Cancel', 'makery'),
					'class' => 'times',
				);
			}

			$actions['view']=array(
				'url'  => $order->get_view_order_url(),
				'name' => __('View', 'makery'),
				'class' => 'eye',
			);

			$actions=apply_filters('woocommerce_my_account_my_orders_actions', $actions, $order);

			if ($actions) {
				foreach($actions as $key => $action){
					echo '<a href="'.esc_url($action['url']).'" title="'.esc_html($action['name']).'" class="element-button secondary small square'.sanitize_html_class($key).'"><span class="fa fa-'.$action['class'].'"></span></a>';
				}
			}
			?>
		</td>
	</tr>
	<?php } ?>
	</tbody>
</table>
<?php } ?>