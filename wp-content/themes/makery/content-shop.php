<?php ThemexShop::refresh($post->ID); ?>
<?php $owner=ThemexUser::getUser(ThemexShop::$data['author']); ?>
<div class="shop-preview">
	<div class="shop-images clearfix">
	<?php if(count(ThemexShop::$data['products'])==1) { ?>
		<?php $product=reset(ThemexShop::$data['products']); ?>
		<div class="item-image fullwidth">
			<div class="image-wrap">
				<a href="<?php echo get_permalink($product); ?>" title="<?php echo get_the_title($product); ?>">
					<?php echo ThemexCore::getImage($product, 420, THEME_URI.'images/product.png'); ?>
				</a>
			</div>
		</div>
	<?php } else { ?>
		<?php for($index=0; $index<4; $index++) { ?>
		<div class="item-image">
			<div class="image-wrap">
				<?php if(isset(ThemexShop::$data['products'][$index])) { ?>
				<a href="<?php echo get_permalink(ThemexShop::$data['products'][$index]); ?>" title="<?php echo get_the_title(ThemexShop::$data['products'][$index]); ?>">
					<?php echo ThemexCore::getImage(ThemexShop::$data['products'][$index], 200, THEME_URI.'images/product.png'); ?>
				</a>
				<?php } else { ?>
				<a href="<?php the_permalink(); ?>">
					<img src="<?php echo THEME_URI.'images/product.png'; ?>" alt="" />
				</a>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	<?php } ?>
	</div>
	<footer class="shop-footer">
		<div class="shop-author clearfix">
			<div class="author-image">
				<div class="image-wrap">
					<a href="<?php the_permalink(); ?>">
						<?php echo ThemexCore::getImage(ThemexShop::$data['ID'], 150, THEME_URI.'images/shop.png'); ?>
					</a>
				</div>									
			</div>
			<div class="author-details">
				<h3 class="author-name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<div class="shop-attributes">
					<ul>						
						<li>
							<?php if(!empty($owner['profile']['location']) && !ThemexCore::checkOption('profile_location')) { ?>
							<span class="fa fa-map-marker"></span>
							<span><?php echo $owner['profile']['location']; ?></span>
							<?php } else if(!ThemexCore::checkOption('shop_favorites')) { ?>
							<span class="fa fa-heart"></span>
							<span><?php echo sprintf(_n('%d Admirer', '%d Admirers', ThemexShop::$data['admirers'], 'makery'), ThemexShop::$data['admirers']);?></span>
							<?php } else if(!ThemexCore::checkOption('shop_sales')) { ?>
							<span class="fa fa-tags"></span>
							<span><?php echo sprintf(_n('%d Sale', '%d Sales', ThemexShop::$data['sales'], 'makery'), ThemexShop::$data['sales']);?></span>
							<?php } ?>
						</li>						
					</ul>
				</div>
				<?php if(!ThemexCore::checkOption('shop_rating')) { ?>
				<div class="shop-rating" title="<?php echo sprintf(_n('%d Rating', '%d Ratings', ThemexShop::$data['ratings'], 'makery'), ThemexShop::$data['ratings']);?>">
					<div class="element-rating" data-score="<?php echo ThemexShop::$data['rating']; ?>"></div>
				</div>
				<?php } ?>
			</div>
		</div>
	</footer>
</div>