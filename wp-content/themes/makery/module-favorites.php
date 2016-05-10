<div class="widget widget-slider sidebar-widget">
	<div class="widget-title">
		<h4><?php _e('Favorites', 'makery'); ?></h4>
	</div>
	<?php if(empty(ThemexUser::$data['active']['favorites'])) { ?>
	<span class="secondary"><?php _e('No items favorited yet.', 'makery'); ?></span>
	<?php } else { ?>
	<div class="element-slider" data-effect="slide" data-pause="0" data-speed="0">
		<ul>
			<?php
			$counter=0;
			foreach(ThemexUser::$data['active']['favorites'] as $product) {
				$status=get_post_status($product);
				$attachment=get_post_thumbnail_id($product);
				$thumbnail=wp_get_attachment_url($attachment);					
				if($status=='publish' && $thumbnail!==false) {
					$counter++;
					if($counter==1) {
					?>
					<li class="clearfix">
					<?php } ?>
						<div class="column fourcol static <?php if($counter==3) { ?>last<?php } ?>">
							<div class="image-border small">
								<div class="image-wrap">
									<a href="<?php echo get_permalink($product); ?>" title="<?php echo get_the_title($product); ?>">
										<img src="<?php echo themex_resize($thumbnail, 150, 150); ?>" alt="" />
									</a>
								</div>
							</div>
						</div>
					<?php 
					if($counter==3) {
					$counter=0;
					?>
					</li>
					<?php 
					}
				}
			}
			if($counter!==0) {
			?>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</div>