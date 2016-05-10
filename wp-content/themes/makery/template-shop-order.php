<?php
/*
Template Name: Shop Order
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<?php $order=ThemexWoo::getOrder(get_query_var('shop-order')); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('Edit Order', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('shop-order'); ?>
	<?php if(ThemexCore::checkOption('shop_multiple')) { ?>
	<span class="secondary"><?php _e('This shop does not exist.', 'makery'); ?></span>
	<?php } else if(empty($order) || $order['author']!=ThemexUser::$data['current']['ID']) { ?>
	<span class="secondary"><?php _e('This order does not exist.', 'makery'); ?></span>
	<?php } else { ?>
	<form action="" method="POST" class="site-form">
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Number', 'makery'); ?></th>
					<td>
						<?php echo $order['number']; ?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Date', 'makery'); ?></th>
					<td>
						<?php echo date_i18n(get_option('date_format'), strtotime($order['date'])); ?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Status', 'makery'); ?></th>
					<td>
						<?php echo $order['condition']; ?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Customer', 'makery'); ?></th>
					<td>
						<?php $customer=ThemexUser::getUser($order['customer'], true); ?>
						<a href="<?php echo esc_url($customer['links']['profile']['url']); ?>"><?php echo $customer['profile']['full_name']; ?></a>
					</td>
				</tr>				
			</tbody>
		</table>
		<table class="profile-table shop_table order_details">
			<thead>
				<tr>
					<th class="product-name"><?php _e('Product', 'makery'); ?></th>
					<th class="product-total"><?php _e('Total', 'makery'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($order['products'] as $product) { ?>
				<tr class="order_item woocommerce">
					<td class="product-name">
						<a href="<?php echo get_permalink($product['product_id']); ?>"><?php echo $product['name']; ?></a>
						<strong class="product-quantity">&times; <?php echo $product['qty']; ?></strong>
						<?php $product['meta']->display(); ?>
					</td>
					<td class="product-total">
						<span class="amount"><?php echo $product['subtotal']; ?></span>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<?php foreach($order['totals'] as $total) { ?>
				<tr>
					<th scope="row"><?php echo $total['label']; ?></th>
					<td><span class="amount"><?php echo $total['value']; ?></span></td>
				</tr>
				<?php } ?>
			</tfoot>
		</table>
		<?php if(!ThemexCore::checkOption('order_address')) { ?>
		<table class="profile-table">
			<tbody>
				<tr>
					<th><?php _e('Customer Details', 'makery'); ?></th>
					<td>
						<?php if(!empty($order['email'])) { ?>
						<strong><?php _e('Email:', 'makery'); ?></strong>&nbsp;<?php echo $order['email']; ?><br />
						<?php } ?>
						<?php if(!empty($order['phone'])) { ?>
						<strong><?php _e('Phone:', 'makery'); ?></strong>&nbsp;<?php echo $order['phone']; ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<?php if($order['shipping_address']==$order['billing_address']) { ?>
					<th><?php _e('Customer Address', 'makery'); ?></th>
					<?php } else { ?>
					<th><?php _e('Billing Address', 'makery'); ?></th>
					<?php } ?>					
					<td>
						<address><?php echo $order['billing_address']; ?></address>
					</td>
				</tr>
				<?php if($order['shipping_address']!=$order['billing_address']) { ?>
				<tr>
					<th><?php _e('Shipping Address', 'makery'); ?></th>
					<td>
						<address><?php echo $order['shipping_address']; ?></address>
					</td>
				</tr>
				<?php } ?>
				<?php if(!empty($order['customer_note'])) { ?>
				<tr>
					<th><?php _e('Customer Note', 'makery'); ?></th>
					<td>
						<?php echo nl2br(esc_html($order['customer_note'])); ?>
					</td>
				</tr>		
				<?php } ?>
				<?php if(!empty($order['order_note'])) { ?>
				<tr>
					<th><?php _e('Order Note', 'makery'); ?></th>
					<td>
						<?php echo nl2br(esc_html($order['order_note'])); ?>
					</td>
				</tr>		
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<?php if($order['status']=='processing') { ?>
		<?php if(!ThemexCore::checkOption('order_note')) { ?>
		<div class="field-wrap">
			<textarea name="order_note" id="order_note" cols="30" rows="5" placeholder="<?php _e('Order Note', 'makery'); ?>"></textarea>
		</div>
		<?php } ?>
		<a href="#" class="element-button element-submit primary"><?php _e('Complete Order', 'makery'); ?></a>
		<input type="hidden" name="order_id" value="<?php echo $order['ID']; ?>" />
		<input type="hidden" name="woo_action" value="complete_order" />
		<?php } ?>
	</form>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>