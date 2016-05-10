<div class="widget sidebar-widget">
	<div class="widget-title">
		<h4><?php _e('Owner', 'makery'); ?></h4>
	</div>
	<div class="widget-content">
		<?php $author=ThemexUser::getUser(ThemexShop::$data['author'], true); ?>
		<div class="shop-author small clearfix">
			<div class="author-image">
				<div class="image-wrap">
					<a href="<?php echo esc_url($author['links']['profile']['url']); ?>">
						<?php echo get_avatar($author['ID'], 150); ?>	
					</a>
				</div>
			</div>
			<div class="author-details">
				<h4 class="author-name">
					<a href="<?php echo esc_url($author['links']['profile']['url']); ?>">
						<?php echo $author['profile']['full_name']; ?>
					</a>
				</h4>
				<?php if(!empty($author['profile']['location']) && !ThemexCore::checkOption('profile_location')) { ?>
				<div class="shop-attributes">
					<ul>
						<li>
							<span class="fa fa-map-marker"></span>
							<span><?php echo $author['profile']['location']; ?></span>
						</li>
					</ul>										
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php if(!empty(ThemexShop::$data['profile']['about']) || !empty(ThemexShop::$data['profile']['policy'])) { ?>
<div class="site-popups hidden">
	<div id="about_page">
		<div class="site-popup">
			<?php echo apply_filters('the_content', ThemexShop::$data['profile']['about']); ?>
		</div>
	</div>
	<div id="policy_page">
		<div class="site-popup">
			<?php echo apply_filters('the_content', ThemexShop::$data['profile']['policy']); ?>
		</div>
	</div>
</div>
<!-- /popups -->
<div class="widget sidebar-widget">
	<div class="widget-title">
		<h4><?php _e('Pages', 'makery'); ?></h4>
	</div>
	<ul>
		<?php if(!empty(ThemexShop::$data['profile']['about'])) { ?>
		<li><a href="#about_page" class="element-colorbox"><?php _e('About Shop', 'makery'); ?></a></li>
		<?php } ?>
		<?php if(!empty(ThemexShop::$data['profile']['policy'])) { ?>
		<li><a href="#policy_page" class="element-colorbox"><?php _e('Shop Policies', 'makery'); ?></a></li>
		<?php } ?>
	</ul>		
</div>
<?php } ?>
<?php ThemexSidebar::renderSidebar('shop'); ?>