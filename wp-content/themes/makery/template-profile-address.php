<?php
/*
Template Name: Profile Address
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('My Address', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('profile-address'); ?>
	<?php if(!ThemexWoo::isActive() || ThemexCore::checkOption('profile_address')) { ?>
	<span class="secondary"><?php _e('There are no fields in this form.', 'makery'); ?></span>
	<?php } else { ?>
	<form action="" method="POST" class="site-form">
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<table class="profile-fields">
			<tbody>
				<?php foreach(ThemexCore::$components['forms']['address'] as $field) { ?>
				<tr>
					<th><?php echo $field['label']; ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr(ThemexUser::$data['current']['profile'][$field['name']]); ?>" />
						</div>
					</td>							
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<a href="#" class="element-button element-submit primary"><?php _e('Save Changes', 'makery'); ?></a>
		<input type="hidden" name="user_action" value="update_profile" />
	</form>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>