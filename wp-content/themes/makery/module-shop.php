<div class="widget sidebar-widget">
	<div class="widget-title">
		<h4><?php _e('Shop', 'makery'); ?></h4>
	</div>
	<?php if(ThemexShop::$data['status']!='publish') { ?>
	<span class="secondary"><?php _e('No shop created yet.', 'makery'); ?></span>
	<?php } else { ?>
	<div class="shop-author clearfix">		
		<div class="author-image">
			<div class="image-wrap">
				<a href="<?php echo get_permalink(ThemexShop::$data['ID']); ?>">
					<?php echo ThemexCore::getImage(ThemexShop::$data['ID'], 150, THEME_URI.'images/shop.png'); ?>
				</a>
			</div>									
		</div>
		<div class="author-details">
			<h4 class="author-name">
				<a href="<?php echo get_permalink(ThemexShop::$data['ID']); ?>"><?php echo ThemexShop::$data['profile']['title']; ?></a>
			</h4>
			<div class="shop-attributes">
				<ul>
					<?php if(!ThemexCore::checkOption('shop_sales')) { ?>
					<li>
						<span class="fa fa-tags"></span>
						<span><?php echo sprintf(_n('%d Sale', '%d Sales', ThemexShop::$data['sales'], 'makery'), ThemexShop::$data['sales']);?></span>
					</li>
					<?php } ?>
					<?php if(!ThemexCore::checkOption('shop_favorites')) { ?>
					<li>
						<span class="fa fa-heart"></span>
						<span><?php echo sprintf(_n('%d Admirer', '%d Admirers', ThemexShop::$data['admirers'], 'makery'), ThemexShop::$data['admirers']);?></span>
					</li>
					<?php } ?>
				</ul>										
			</div>
		</div>
	</div>
	<?php } ?>
</div>