<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')){
    exit;
}

if(is_user_logged_in()){
	return;
}	
?>
<form method="post" class="site-form login" <?php if($hidden)echo 'style="display:none;"'; ?>>
	<?php do_action('woocommerce_login_form_start'); ?>
	<?php if($message)echo wpautop(wptexturize($message)); ?>
	<div class="sixcol column">
		<div class="field-wrap">
			<input type="text" class="input-text" name="username" id="username" placeholder="<?php _e('Username', 'makery'); ?>" />
		</div>
	</div>	
	<div class="sixcol column last">
		<div class="field-wrap">
			<input class="input-text" type="password" name="password" id="password" placeholder="<?php _e('Password', 'makery'); ?>" />
		</div>		
	</div>
	<div class="clear"></div>
	<?php do_action('woocommerce_login_form'); ?>		
	<?php wp_nonce_field('woocommerce-login'); ?>
	<a href="#" class="element-button element-submit primary"><?php _e('Sign In', 'makery'); ?></a>
	<a href="#password_form" class="element-button element-colorbox square" title="<?php _e('Lost your password?', 'makery'); ?>"><span class="fa fa-life-ring"></span></a>
	<input type="hidden" name="redirect" value="<?php echo esc_url($redirect)?>" />
	<input name="rememberme" type="hidden" id="rememberme" value="forever" />
	<input type="hidden" name="login" value="1" />	
	<?php do_action('woocommerce_login_form_end'); ?>
</form>