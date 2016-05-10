<article <?php post_class('post clearfix'); ?>>
	<?php if(has_post_thumbnail()) { ?>
	<div class="column fivecol">
		<div class="post-image">
			<div class="image-wrap">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('normal'); ?></a>
			</div>
		</div>
	</div>
	<div class="column sevencol last">
	<?php } else { ?>
	<div class="fullcol">
	<?php } ?>
		<div class="element-title">
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		</div>
		<div class="post-content clearfix">
			<?php the_excerpt(); ?>
		</div>
		<div class="post-footer clearfix">
			<?php if(!ThemexCore::checkOption('post_date')) { ?>
			<time class="post-date left" datetime="<?php the_time('Y-m-d'); ?>">
				<span class="fa fa-calendar"></span>
				<span><?php the_time(get_option('date_format')); ?></span>
			</time>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('post_author')) { ?>
			<div class="post-author left">
				<span class="fa fa-pencil"></span>
				<span><?php the_author_posts_link(); ?></span>
			</div>
			<?php } ?>
			<?php if(comments_open()) { ?>
			<div class="comments-number left">				
				<span class="fa fa-comments-o"></span>
				<span><a href="<?php comments_link(); ?> "><?php comments_number('0','1','%'); ?></a></span>				
			</div>
			<?php } ?>
		</div>
	</div>
</article>