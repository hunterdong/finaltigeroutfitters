<?php
/*
Template Name: Profile Referrals
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('My Referrals', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('profile-referrals'); ?>
	<?php if(ThemexCore::checkOption('shop_referrals') || !ThemexWoo::isActive()) { ?>
	<span class="secondary"><?php _e('This page does not exist.', 'makery'); ?></span>
	<?php } else { ?>
		<form action="" method="POST" class="site-form">
			<table class="profile-fields">
				<tbody>
					<tr>
						<th><?php _e('Referral Link', 'makery'); ?></th>
						<td>
							<div class="field-wrap">
								<input type="text" name="link" class="element-copy" readonly="readonly" value="<?php echo home_url('?ref='.ThemexUser::$data['current']['login']); ?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><?php _e('Current Rate', 'makery'); ?></th>
						<td><?php echo ThemexCore::getOption('shop_rate_referral', '30'); ?>%</td>
					</tr>
					<tr>
						<th><?php _e('Clickthroughs', 'makery'); ?></th>
						<td><?php echo ThemexUser::$data['current']['clicks']; ?></td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
		$orders=ThemexWoo::getReferrals(ThemexUser::$data['current']['ID']);
		if(empty($orders)) {
		?>
		<span class="secondary"><?php _e('No orders made yet.', 'makery'); ?></span>
		<?php } else { ?>
		<table>
			<thead>
				<tr>
					<th>&#8470;</th>
					<th><?php _e('Date', 'makery'); ?></th>
					<th><?php _e('Status', 'makery'); ?></th>
					<th><?php _e('Total', 'makery'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach($orders as $ID) {
				$order=ThemexWoo::getOrder($ID);
				?>
				<tr>
					<td><?php echo $order['number']; ?></td>
					<td>
						<time datetime="<?php echo date('Y-m-d', strtotime($order['date'])); ?>" title="<?php echo esc_attr(strtotime($order['date'])); ?>"><?php echo date_i18n(get_option('date_format'), strtotime($order['date'])); ?></time>
					</td>
					<td><?php echo $order['condition']; ?></td>
					<td><?php echo $order['total']; ?></td>
				</tr>
				<?php 
				}
				?>
			</tbody>
		</table>
		<?php } ?>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>