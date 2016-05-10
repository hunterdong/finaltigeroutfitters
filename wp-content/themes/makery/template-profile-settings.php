<?php
/*
Template Name: Profile Settings
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('My Settings', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('profile-settings'); ?>
	<form action="" method="POST" class="site-form">
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Notifications', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php
							echo ThemexInterface::renderOption(array(
								'id' => 'notices',
								'type' => 'select',
								'value' => esc_attr(ThemexUser::$data['current']['settings']['notices']),
								'options' => array(
									'1' => __('Enabled', 'makery'),
									'0' => __('Disabled', 'makery'),
								),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Email Address', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="email" value="<?php echo esc_attr(ThemexUser::$data['current']['email']); ?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('New Password', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="password" name="new_password" value="" />
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Confirm Password', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="password" name="confirm_password" value="" />
						</div>
					</td>
				</tr>
				<?php if(ThemexFacebook::isUpdated(ThemexUser::$data['current']['ID'])) { ?>
				<tr>
					<th><?php _e('Current Password', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="password" name="current_password" value="" />
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<a href="#" class="element-button element-submit primary"><?php _e('Save Changes', 'makery'); ?></a>
		<input type="hidden" name="user_action" value="update_settings" />
	</form>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>