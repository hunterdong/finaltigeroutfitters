<aside class="column threecol">
	<?php if(!ThemexUser::isProfile()) { ?>
	<div class="profile-preview sidebar-widget">
		<div class="profile-image">
			<div class="image-wrap">
				<?php echo get_avatar(ThemexUser::$data['active']['ID'], 200); ?>															
			</div>
		</div>
		<?php if((!empty(ThemexUser::$data['active']['profile']['location']) && !ThemexCore::checkOption('profile_location')) || !ThemexCore::checkOption('profile_date')) { ?>
		<footer class="profile-footer">
			<div class="shop-attributes">
				<ul>
					<?php if(!empty(ThemexUser::$data['active']['profile']['location']) && !ThemexCore::checkOption('profile_location')) { ?>
					<li>
						<span class="fa fa-map-marker"></span>
						<span><?php echo ThemexUser::$data['active']['profile']['location']; ?></span>
					</li>
					<?php } ?>
					<?php if(!ThemexCore::checkOption('profile_date')) { ?>
					<li>
						<span class="fa fa-male"></span>
						<span><?php echo date_i18n(get_option('date_format'), strtotime(themex_value('date', ThemexUser::$data['active']))); ?></span>
					</li>
					<?php } ?>
				</ul>										
			</div>
		</footer>
		<?php } ?>
	</div>
	<?php } else { ?>
	<div class="profile-preview sidebar-widget">
		<div class="profile-image">
			<?php 
			if(get_query_var('shop-product') && !ThemexCore::checkOption('shop_multiple')) {
			ThemexWoo::$data['product']=ThemexWoo::getProduct(get_query_var('shop-product'));
			?>
				<div class="image-wrap">
					<?php echo ThemexCore::getImage(themex_value('ID', ThemexWoo::$data['product']), 200, THEME_URI.'images/product.png'); ?>
				</div>
				<?php if(!empty(ThemexWoo::$data['product'])) { ?>
				<div class="profile-upload">
					<form action="" enctype="multipart/form-data" method="POST">
						<label for="product_thumb" class="element-button square" title="<?php _e('Upload Image', 'makery'); ?>">
							<span class="fa fa-upload"></span>
						</label>
						<input type="file" id="product_thumb" name="product_image" class="element-upload shifted" />
						<input type="hidden" name="product_id" value="<?php echo ThemexWoo::$data['product']['ID']; ?>" />
						<input type="hidden" name="woo_action" value="update_image" />
					</form>
				</div>
				<?php 
				}
			} else if(ThemexUser::isShop()) {
			ThemexShop::refresh(ThemexUser::$data['current']['shop'], true);
			?>
			<div class="image-wrap">
				<?php echo ThemexCore::getImage(themex_value('ID', ThemexShop::$data), 200, THEME_URI.'images/shop.png'); ?>
			</div>
			<div class="profile-upload">
				<form action="" enctype="multipart/form-data" method="POST">
					<label for="shop_thumb" class="element-button square" title="<?php _e('Upload Image', 'makery'); ?>">
						<span class="fa fa-upload"></span>
					</label>
					<input type="file" id="shop_thumb" name="shop_image" class="element-upload shifted" />
					<input type="hidden" name="shop_id" value="<?php echo ThemexShop::$data['ID']; ?>" />
					<input type="hidden" name="shop_action" value="update_image" />
				</form>
			</div>
			<?php } else { ?>
			<div class="image-wrap">
				<?php echo get_avatar(ThemexUser::$data['current']['ID'], 200); ?>															
			</div>
			<div class="profile-upload">
				<form action="" enctype="multipart/form-data" method="POST">
					<label for="user_avatar" class="element-button square" title="<?php _e('Upload Image', 'makery'); ?>">
						<span class="fa fa-upload"></span>
					</label>
					<input type="file" id="user_avatar" name="user_avatar" class="element-upload shifted" />
					<input type="hidden" name="user_action" value="update_avatar" />
				</form>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="profile-menu sidebar-widget">
		<ul>
			<?php foreach(ThemexUser::$data['current']['links'] as $link) { ?>
			<li class="<?php if($link['current']) { ?>current<?php } ?> <?php if(!empty($link['child'])) { ?>parent<?php } ?> clearfix">
				<a href="<?php echo esc_url($link['url']); ?>"><?php echo $link['name']; ?></a>
				<?php if(isset($link['child']) && !empty($link['child'])) { ?>
				<ul>
					<?php foreach($link['child'] as $key => $child) { ?>
					<li class="<?php if($child['current']) { ?>current<?php } ?> clearfix">
						<a href="<?php echo esc_url($child['url']); ?>"><?php echo $child['name']; ?></a>						
						<?php if($key=='orders' && !empty(ThemexShop::$data['handlers'])) { ?>
						<span><?php echo ThemexShop::$data['handlers']; ?></span>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
			<?php } ?>
		</ul>
	</div>	
	<?php } ?>
</aside>