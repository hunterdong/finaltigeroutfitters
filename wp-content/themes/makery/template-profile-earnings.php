<?php
/*
Template Name: Profile Earnings
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('My Earnings', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('profile-earnings'); ?>
	<?php if(ThemexCore::checkOption('shop_multiple') && ThemexCore::checkOption('shop_referrals')) { ?>
	<span class="secondary"><?php _e('This page does not exist.', 'makery'); ?></span>
	<?php 
	} else {
	
	ThemexShop::refresh(ThemexUser::$data['current']['shop'], true);	
	$withdrawals=ThemexShop::$data['withdrawals'];
	if(empty(ThemexUser::$data['current']['shop'])) {
		$withdrawals=ThemexShop::getWithdrawals(ThemexUser::$data['current']['ID']);
	}
	
	if(!empty($withdrawals)) {
	?>
	<table class="profile-table">
		<thead>
			<tr>				
				<th><?php _e('Date', 'makery'); ?></th>
				<th><?php _e('Method', 'makery'); ?></th>
				<th><?php _e('Amount', 'makery'); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($withdrawals as $ID) {
			$withdrawal=ThemexShop::getWithdrawal($ID);
			?>
			<tr>
				<td><?php echo date_i18n(get_option('date_format'), strtotime($withdrawal['date'])); ?></td>
				<td>
					<?php _e('via', 'makery'); ?>
					<?php echo $withdrawal['method']['label']; ?>
					<?php _e('to', 'makery'); ?>
					<?php 
					$recipient=array();
					foreach(ThemexCore::$components['forms']['withdrawal'][$withdrawal['method']['name']] as $field) { 
						$recipient[]=$withdrawal[$field['name']];
					}
					echo implode(', ', $recipient);
					?>
				</td>
				<td><?php echo ThemexWoo::getPrice($withdrawal['amount']); ?></td>
				<td class="textright">
					<form action="" method="POST">
						<a href="#" title="<?php _e('Remove', 'makery'); ?>" class="element-button element-submit small square secondary">
							<span class="fa fa-times"></span>
						</a>
						<input type="hidden" name="withdrawal_id" value="<?php echo $withdrawal['ID']; ?>" />
						<input type="hidden" name="shop_action" value="remove_withdrawal" />
					</form>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	<form action="" method="POST" class="site-form">
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<table class="profile-fields">
			<tbody>
				<?php if(!ThemexCore::checkOption('shop_multiple') && ThemexShop::$data['status']=='publish') { ?>
				<tr>
					<th><?php _e('Total Revenue', 'makery'); ?></th>
					<td><?php echo ThemexWoo::getPrice(ThemexShop::$data['revenue']); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<th><?php _e('Total Profit', 'makery'); ?></th>
					<td><?php echo ThemexWoo::getPrice(ThemexUser::$data['current']['profit']); ?></td>
				</tr>
				<tr>
					<th><?php _e('Current Balance', 'makery'); ?></th>
					<td><?php echo ThemexWoo::getPrice(ThemexUser::$data['current']['balance']); ?></td>
				</tr>
				<?php if(!ThemexCore::checkOption('shop_multiple') && ThemexShop::$data['status']=='publish') { ?>
				<tr>
					<th><?php _e('Current Rate', 'makery'); ?></th>				
					<td><?php echo ThemexShop::filterRate(ThemexShop::$data['ID'], ThemexShop::$data['rate']); ?>%</td>
				</tr>
				<?php 
				}
				
				$methods=array_flip(ThemexCore::getOption('withdrawal_methods', array('paypal', 'skrill', 'swift')));
				$gateways=ThemexWoo::getPaymentMethods();
				
				if(count($gateways)>1 || !isset($gateways['paypal-adaptive-payments'])) {
					foreach(ThemexCore::$components['forms']['withdrawal'] as $name => $field) {
						if(is_array(reset($field))) {
							if(isset($methods[$name])) {
								foreach($field as $key => $child) {
								?>
								<tr class="trigger-method-<?php echo $name; ?>">
									<th><?php echo $child['label']; ?></th>
									<td>
										<?php if(in_array($child['type'], array('select', 'select_country'))) { ?>
										<div class="element-select">
											<span></span>
											<?php 
											echo ThemexInterface::renderOption(array(
												'id' => $child['name'],
												'type' => $child['type'],
												'options' => themex_array('options', $child),
												'value' => themex_value($child['name'], $_POST),
												'wrap' => false,
											));
											?>
										</div>
										<?php } else { ?>
										<div class="field-wrap">
											<input type="text" name="<?php echo esc_attr($child['name']); ?>" value="<?php echo esc_attr(themex_value($child['name'], $_POST)); ?>" />
										</div>
										<?php } ?>
									</td>
								</tr>
								<?php 
								}
							}
						} else {
						?>
						<tr>
							<th><?php echo $field['label']; ?></th>
							<td>
								<?php if(in_array($field['type'], array('select', 'select_country'))) { ?>
								<div class="element-select">
									<span></span>
									<?php 
									echo ThemexInterface::renderOption(array(
										'id' => $field['name'],
										'type' => $field['type'],
										'options' => array_intersect_key(themex_array('options', $field), $methods),
										'value' => themex_value($field['name'], $_POST),
										'wrap' => false,
										'attributes' => array(
											'class' => 'element-trigger',
										),
									));
									?>
								</div>
								<?php } else { ?>
								<div class="field-wrap">
									<input type="text" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr(themex_value($field['name'], $_POST)); ?>" />
								</div>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>				
					<?php 
					}
				}
				?>
			</tbody>
		</table>
		<?php if(count($gateways)>1 || !isset($gateways['paypal-adaptive-payments'])) { ?>
		<a href="#" class="element-button element-submit primary"><?php _e('Submit Request', 'makery'); ?></a>
		<input type="hidden" name="shop_action" value="add_withdrawal" />
		<?php } ?>
	</form>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>