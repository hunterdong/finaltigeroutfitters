<div class="testimonial">
	<div class="testimonial-content">
		<?php echo $GLOBALS['content']; ?>
	</div>
	<div class="testimonial-details clearfix">
		<?php if(has_post_thumbnail()) { ?>
		<div class="testimonial-image">
			<?php the_post_thumbnail('thumbnail', array('class' => 'fullwidth')); ?>
		</div>
		<?php } ?>
		<div class="testimonial-author"><?php the_title(); ?></div>								
	</div>
</div>