<?php
/*
Template Name: Shop Shipping
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('Shop Shipping', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('shop-shipping'); ?>
	<?php if(!ThemexWoo::isActive() || ThemexCore::checkOption('shop_shipping')) { ?>
	<span class="secondary"><?php _e('This shop does not exist.', 'makery'); ?></span>
	<?php 
	} else {

	$methods=ThemexWoo::getShippingMethods();
	$shipping=ThemexShop::getShipping(ThemexShop::$data['ID']);
	
	if(!empty($methods)) {
	?>
	<form action="" method="POST" class="site-form">
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<?php if(isset($methods['free_shipping']) && $methods['free_shipping']->enabled=='yes') { ?>
		<h3><?php echo $methods['free_shipping']->title; ?></h3>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Status', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'free_shipping_enabled',
								'type' => 'select',
								'options' => array(
									'yes' => __('Enabled', 'makery'),
									'no' => __('Disabled', 'makery'),
								),
								'value' => themex_value('enabled', $shipping['free_shipping'], 'yes'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Availability', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'free_shipping_availability',
								'type' => 'select',
								'options' => array(
									'all' => __('All Countries', 'makery'),
									'specific' => __('Specific Countries', 'makery'),
								),
								'attributes' => array(
									'class' => 'element-trigger',
								),
								'value' => themex_value('availability', $shipping['free_shipping'], 'all'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr class="trigger-free-shipping-availability-specific">
					<th><?php _e('Countries', 'makery'); ?></th>
					<td>
						<?php
						echo ThemexInterface::renderOption(array(
							'id' => 'free_shipping_countries[]',
							'type' => 'select',
							'options' => ThemexWoo::getShippingCountries(),
							'value' => themex_array('countries', $shipping['free_shipping']),
							'wrap' => false,
							'attributes' => array(
								'class' => 'element-chosen',
								'multiple' => 'multiple',
								'data-placeholder' => __('Select Options', 'makery'),
							),
						));
						?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Minimum Amount', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="free_shipping_min_amount" value="<?php echo ThemexWoo::formatPrice(themex_value('min_amount', $shipping['free_shipping'], '0')); ?>" />
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($methods['flat_rate']) && $methods['flat_rate']->enabled=='yes') { ?>
		<h3><?php echo $methods['flat_rate']->title; ?></h3>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Status', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'flat_rate_enabled',
								'type' => 'select',
								'options' => array(
									'yes' => __('Enabled', 'makery'),
									'no' => __('Disabled', 'makery'),
								),
								'value' => themex_value('enabled', $shipping['flat_rate'], 'yes'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Availability', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'flat_rate_availability',
								'type' => 'select',
								'options' => array(
									'all' => __('All Countries', 'makery'),
									'specific' => __('Specific Countries', 'makery'),
								),
								'attributes' => array(
									'class' => 'element-trigger',
								),
								'value' => themex_value('availability', $shipping['flat_rate'], 'all'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr class="trigger-flat-rate-availability-specific">
					<th><?php _e('Countries', 'makery'); ?></th>
					<td>
						<?php
						echo ThemexInterface::renderOption(array(
							'id' => 'flat_rate_countries[]',
							'type' => 'select',
							'options' => ThemexWoo::getShippingCountries(),
							'value' => themex_array('countries', $shipping['flat_rate']),
							'wrap' => false,
							'attributes' => array(
								'class' => 'element-chosen',
								'multiple' => 'multiple',
								'data-placeholder' => __('Select Options', 'makery'),
							),
						));
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="profile-table halved">
			<thead>
				<tr>
					<th><?php _e('Shipping Class', 'makery'); ?></th>
					<th><?php _e('Cost', 'makery'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr> 
					<td><?php _e('All Classes', 'makery'); ?></td>
					<td>
						<div class="field-wrap">
							<input type="text" name="flat_rate_default_cost" value="<?php echo ThemexWoo::formatPrice(themex_value('default_cost', $shipping['flat_rate'], '0')); ?>" />
						</div>
					</td>
				</tr>
				<?php
				$classes=ThemexWoo::getShippingClasses();
				$costs=themex_array('costs', $shipping['flat_rate']);

				foreach($classes as $index => $class) {
				?>
				<tr> 
					<td><?php echo $class->name; ?></td>
					<td>
						<div class="field-wrap">
							<input type="text" name="flat_rate_cost[<?php echo $index; ?>]" value="<?php echo ThemexWoo::formatPrice(themex_value($class->slug, $costs, '0')); ?>" />
							<input type="hidden" name="flat_rate_class[<?php echo $index; ?>]" value="<?php echo $class->slug; ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($methods['international_delivery']) && $methods['international_delivery']->enabled=='yes') { ?>
		<h3><?php echo $methods['international_delivery']->title; ?></h3>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Status', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'international_delivery_enabled',
								'type' => 'select',
								'options' => array(
									'yes' => __('Enabled', 'makery'),
									'no' => __('Disabled', 'makery'),
								),
								'value' => themex_value('enabled', $shipping['international_delivery'], 'yes'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Availability', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'international_delivery_availability',
								'type' => 'select',
								'options' => array(
									'including' => __('Selected Countries', 'makery'),
									'excluding' => __('Excluded Countries', 'makery'),
								),
								'value' => themex_value('availability', $shipping['international_delivery'], 'including'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Countries', 'makery'); ?></th>
					<td>
						<?php
						echo ThemexInterface::renderOption(array(
							'id' => 'international_delivery_countries[]',
							'type' => 'select',
							'options' => ThemexWoo::getShippingCountries(),
							'value' => themex_array('countries', $shipping['international_delivery']),
							'wrap' => false,
							'attributes' => array(
								'class' => 'element-chosen',
								'multiple' => 'multiple',
								'data-placeholder' => __('Select Options', 'makery'),
							),
						));
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="profile-table halved">
			<thead>
				<tr>
					<th><?php _e('Shipping Class', 'makery'); ?></th>
					<th><?php _e('Cost', 'makery'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr> 
					<td><?php _e('All Classes', 'makery'); ?></td>
					<td>
						<div class="field-wrap">
							<input type="text" name="international_delivery_default_cost" value="<?php echo ThemexWoo::formatPrice(themex_value('default_cost', $shipping['international_delivery'], '0')); ?>" />
						</div>
					</td>
				</tr>
				<?php
				$classes=ThemexWoo::getShippingClasses();
				$costs=themex_array('costs', $shipping['international_delivery']);

				foreach($classes as $index => $class) {
				?>
				<tr> 
					<td><?php echo $class->name; ?></td>
					<td>
						<div class="field-wrap">
							<input type="text" name="international_delivery_cost[<?php echo $index; ?>]" value="<?php echo ThemexWoo::formatPrice(themex_value($class->slug, $costs, '0')); ?>" />
							<input type="hidden" name="international_delivery_class[<?php echo $index; ?>]" value="<?php echo $class->slug; ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($methods['local_delivery']) && $methods['local_delivery']->enabled=='yes') { ?>
		<h3><?php echo $methods['local_delivery']->title; ?></h3>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Status', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'local_delivery_enabled',
								'type' => 'select',
								'options' => array(
									'yes' => __('Enabled', 'makery'),
									'no' => __('Disabled', 'makery'),
								),
								'value' => themex_value('enabled', $shipping['local_delivery'], 'yes'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Availability', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'local_delivery_availability',
								'type' => 'select',
								'options' => array(
									'all' => __('All Countries', 'makery'),
									'specific' => __('Specific Countries', 'makery'),
								),
								'attributes' => array(
									'class' => 'element-trigger',
								),
								'value' => themex_value('availability', $shipping['local_delivery'], 'all'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr class="trigger-local-delivery-availability-specific">
					<th><?php _e('Countries', 'makery'); ?></th>
					<td>
						<?php
						echo ThemexInterface::renderOption(array(
							'id' => 'local_delivery_countries[]',
							'type' => 'select',
							'options' => ThemexWoo::getShippingCountries(),
							'value' => themex_array('countries', $shipping['local_delivery']),
							'wrap' => false,
							'attributes' => array(
								'class' => 'element-chosen',
								'multiple' => 'multiple',
								'data-placeholder' => __('Select Options', 'makery'),
							),
						));
						?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Cost', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="local_delivery_cost" value="<?php echo ThemexWoo::formatPrice(themex_value('cost', $shipping['local_delivery'], '0')); ?>" />
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($methods['local_pickup']) && $methods['local_pickup']->enabled=='yes') { ?>
		<h3><?php echo $methods['local_pickup']->title; ?></h3>	
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Status', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'local_pickup_enabled',
								'type' => 'select',
								'options' => array(
									'yes' => __('Enabled', 'makery'),
									'no' => __('Disabled', 'makery'),
								),
								'value' => themex_value('enabled', $shipping['local_pickup'], 'yes'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Availability', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'local_pickup_availability',
								'type' => 'select',
								'options' => array(
									'all' => __('All Countries', 'makery'),
									'specific' => __('Specific Countries', 'makery'),
								),
								'attributes' => array(
									'class' => 'element-trigger',
								),
								'value' => themex_value('availability', $shipping['local_pickup'], 'all'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr class="trigger-local-pickup-availability-specific">
					<th><?php _e('Countries', 'makery'); ?></th>
					<td>
						<?php
						echo ThemexInterface::renderOption(array(
							'id' => 'local_pickup_countries[]',
							'type' => 'select',
							'options' => ThemexWoo::getShippingCountries(),
							'value' => themex_array('countries', $shipping['local_pickup']),
							'wrap' => false,
							'attributes' => array(
								'class' => 'element-chosen',
								'multiple' => 'multiple',
								'data-placeholder' => __('Select Options', 'makery'),
							),
						));
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<a href="#" class="element-button element-submit primary"><?php _e('Save Changes', 'makery'); ?></a>
		<input type="hidden" name="shop_id" value="<?php echo ThemexShop::$data['ID']; ?>" />
		<input type="hidden" name="shop_action" value="update_shipping" />
	</form>
	<?php } ?>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>