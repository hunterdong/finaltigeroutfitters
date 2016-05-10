<?php 
get_header(); 

$layout=ThemexCore::getOption('posts_layout', 'right');
if($layout=='left') {
?>
<aside class="sidebar column fourcol">
<?php get_sidebar(); ?>
</aside>
<div class="column eightcol last">
<?php } else if($layout=='right') { ?>
<div class="column eightcol">
<?php } else { ?>
<div class="fullcol">
<?php } ?>
	<?php the_post(); ?>
	<article class="post-full clearfix">
		<?php if(has_post_thumbnail() && !ThemexCore::checkOption('post_image')) { ?>
		<div class="post-image">
			<div class="image-wrap">
				<?php the_post_thumbnail('extended'); ?>
			</div>
		</div>
		<?php } ?>
		<div class="post-content clearfix">
			<div class="element-title">
				<h1><?php the_title(); ?></h1>
			</div>			
			<?php the_content(); ?>
			<footer class="post-footer clearfix">
				<div class="sixcol column">
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
				</div>
				<div class="sixcol column last">
					<div class="tagcloud textright"><?php the_tags('','',''); ?></div>
				</div>
			</footer>
		</div>
		<?php
		wp_link_pages(array(
			'before' => '<nav class="pagination">',
			'after' => '</nav>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		));
		?>
	</article>
	<?php comments_template(); ?>
</div>
<?php if($layout=='right') { ?>
<aside class="sidebar column fourcol last">
<?php get_sidebar(); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>