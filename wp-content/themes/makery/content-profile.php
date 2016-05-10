<?php ThemexUser::$data['active']=ThemexUser::getUser($GLOBALS['user']->ID, true); ?>
<div class="image-border small">
	<div class="image-wrap">
		<a href="<?php echo ThemexUser::$data['active']['links']['profile']['url']; ?>" title="<?php echo ThemexUser::$data['active']['profile']['full_name']; ?>">
			<?php echo get_avatar(ThemexUser::$data['active']['ID'], 200); ?>
		</a>
	</div>
</div>