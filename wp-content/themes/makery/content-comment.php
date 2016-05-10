<li id="comment-<?php echo $comment->comment_ID; ?>">
	<div class="comment clearfix">
		<div class="comment-image">
			<div class="image-border">
				<div class="image-wrap">
					<?php if(empty($comment->user_id)) { ?>
					<?php echo get_avatar($comment); ?>
					<?php } else { ?>
					<a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php echo get_avatar($comment); ?></a>
					<?php } ?>
				</div>
			</div>											
		</div>
		<div class="comment-content">
			<header class="comment-header clearfix">
				<h6 class="comment-author">
					<?php if(empty($comment->user_id)) { ?>
					<?php comment_author(); ?>
					<?php } else { ?>
					<a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php comment_author(); ?></a>
					<?php } ?>
				</h6>
				<time class="comment-date" datetime="<?php comment_time('Y-m-d'); ?>"><?php comment_time(get_option('date_format').' '.get_option('time_format')); ?></time>
				<?php 
				comment_reply_link(array(
					'reply_text' => '<span class="fa fa-repeat"></span>'.__('Reply', 'makery'),
					'depth' => $GLOBALS['depth'], 
					'max_depth' => 2,
				)); 
				?>
			</header>
			<?php comment_text(); ?>
		</div>
	</div>