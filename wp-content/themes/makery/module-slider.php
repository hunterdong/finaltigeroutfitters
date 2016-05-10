<?php
$speed=intval(ThemexCore::getOption('slider_speed', '1000'));
$pause=intval(ThemexCore::getOption('slider_pause', '0'));
$query=new WP_Query(array(
	'post_type' =>'slide',
	'showposts' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
));

if($query->have_posts()) {
?>
<div class="slider-wrap">
	<div class="element-slider header-slider" data-effect="slide" data-pause="<?php echo $pause; ?>" data-speed="<?php echo $speed; ?>">
		<ul>
			<?php 
			while($query->have_posts()) { 
			$query->the_post(); 
			?>
			<li>
				<div class="container">
					<?php the_content(); ?>
				</div>							
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<!-- /slider -->
<?php } ?>