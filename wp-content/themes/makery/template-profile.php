<?php
/*
Template Name: Profile
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('My Profile', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('profile'); ?>
	<form action="" method="POST" class="site-form">
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<table class="profile-fields">
			<tbody>
				<?php 
				foreach(ThemexCore::$components['forms']['profile'] as $field) {
				
				if(in_array($field['name'], array('first_name', 'last_name')) && ThemexCore::checkOption('profile_name')) {
					continue;
				}
				
				if(in_array($field['name'], array('billing_city', 'billing_country')) && ThemexCore::checkOption('profile_location')) {
					continue;
				}
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
								'options' => themex_array('options', $field),
								'value' => esc_attr(ThemexUser::$data['current']['profile'][$field['name']]),
								'wrap' => false,
							));
							?>
						</div>
						<?php } else { ?>
						<div class="field-wrap">
							<input type="text" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr(ThemexUser::$data['current']['profile'][$field['name']]); ?>" />
						</div>
						<?php } ?>
					</td>							
				</tr>
				<?php } ?>
				<?php ThemexForm::renderData('profile', array(
					'edit' => true,
					'placeholder' => false,
					'before_title' => '<tr><th>',
					'after_title' => '</th>',
					'before_content' => '<td>',
					'after_content' => '</td></tr>',
				), ThemexUser::$data['current']['profile']); ?>
			</tbody>
		</table>
		<div class="profile-editor">
			<?php ThemexInterface::renderEditor('description', ThemexUser::$data['current']['profile']['description']); ?>
		</div>
		<a href="#" class="element-button element-submit primary"><?php _e('Save Changes', 'makery'); ?></a>
		<input type="hidden" name="user_action" value="update_profile" />
	</form>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>