<?php if(ThemexUser::isProfile()) { ?>
<?php get_template_part('template', 'profile'); ?>
<?php } else { ?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title clearfix">
		<h1><?php echo ThemexUser::$data['active']['profile']['full_name']; ?></h1>
	</div>
	<table class="profile-fields">
		<tbody>
			<?php 
			ThemexForm::renderData('profile', array(
				'edit' => false,
				'before_title' => '<tr><th>',
				'after_title' => '</th>',
				'before_content' => '<td>',
				'after_content' => '</td></tr>',
			), ThemexUser::$data['active']['profile']);
			?>
		</tbody>
	</table>
	<div class="profile-content">
		<?php echo wpautop(ThemexUser::$data['active']['profile']['description']); ?>
	</div>
	<div class="profile-options clearfix">
		<?php if(!empty(ThemexUser::$data['active']['settings']['notices'])) { ?>
			<?php if(is_user_logged_in()) { ?>
			<a href="#contact_form" class="element-button element-colorbox"><?php _e('Contact', 'makery'); ?></a>
			<div class="site-popups hidden">
				<div id="contact_form">
					<div class="site-popup medium">
						<form class="site-form element-form" method="POST" action="<?php echo AJAX_URL; ?>">
							<div class="field-wrap">
								<input type="text" name="email" readonly="readonly" value="<?php echo esc_attr(ThemexUser::$data['current']['email']); ?>" />
							</div>
							<div class="field-wrap">
								<textarea name="message" cols="30" rows="5" placeholder="<?php _e('Message', 'makery'); ?>"></textarea>
							</div>
							<a href="#" class="element-button element-submit primary"><?php _e('Send Message', 'makery'); ?></a>
							<input type="hidden" name="user_id" value="<?php echo ThemexUser::$data['active']['ID']; ?>" />
							<input type="hidden" name="user_action" value="submit_message" />
							<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
						</form>
					</div>
				</div>
			</div>
			<!-- /popups -->
			<?php } else { ?>			
			<a href="<?php echo ThemexCore::getURL('register'); ?>" class="element-button"><?php _e('Contact', 'makery'); ?></a>
			<?php } ?>
		<?php } ?>
		<?php if(!ThemexCore::checkOption('profile_links')) { ?>
		<?php get_template_part('module', 'links'); ?>
		<?php } ?>
	</div>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>
<?php } ?>