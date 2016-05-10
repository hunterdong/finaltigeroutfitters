<?php if(have_comments() || comments_open()) { ?>
<div class="post-comments clearfix">
	<div class="element-title">
		<h1><?php _e('Comments', 'makery'); ?></h1>
	</div>
	<?php if(have_comments()) { ?>
	<div class="comments-wrap">
		<ul>
			<?php
			wp_list_comments(array(
				'per_page' => -1,
				'avatar_size' => 75,
				'type' => 'comment',
				'callback'=>array('ThemexInterface', 'renderComment'),
			));
			?>
		</ul>
	</div>
	<?php } ?>
	<?php if(comments_open()) { ?>
	<div class="comments-form">
		<?php comment_form(); ?>
	</div>
	<?php } ?>
</div>
<?php } ?>