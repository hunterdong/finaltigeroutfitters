<div class="themex-page themex-interface">
	<form name="themex_options" id="themex_options" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>">
		<div class="themex-header">
			<h1 class="themex-page-title"><?php _e('Theme Options','makery'); ?></h1>
			<input type="submit" name="<?php echo THEMEX_PREFIX; ?>save_options" value="<?php _e('Save Changes','makery'); ?>" class="themex-button disabled themex-save-button" />
		</div>
		<div class="themex-content">
			<div class="themex-menu">
				<?php ThemexInterface::renderMenu(); ?>
			</div>
			<div class="themex-sections">				
				<?php self::renderSections(); ?>				
			</div>
		</div>	
		<div class="themex-footer">
			<input type="submit" name="<?php echo THEMEX_PREFIX; ?>reset_options" value="<?php _e('Reset Options','makery'); ?>" class="themex-button themex-reset-button" />
			<input type="submit" name="<?php echo THEMEX_PREFIX; ?>save_options" value="<?php _e('Save Changes','makery'); ?>" class="themex-button disabled themex-save-button" />
		</div>
		<div class="themex-popup"></div>
	</form>
</div>
<?php if(isset($_GET['activated'])) { ?>
<iframe class="hidden" src="<?php echo admin_url('options-permalink.php'); ?>"></iframe>
<?php } ?>