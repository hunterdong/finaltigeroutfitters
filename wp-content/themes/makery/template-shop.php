<?php the_post(); ?>
<?php ThemexShop::refresh($post->ID); ?>
<div class="column threecol">
	<div class="shop-preview">
		<div class="shop-images single clearfix">
			<div class="image-wrap">
				<?php echo ThemexCore::getImage(ThemexShop::$data['ID'], 300, THEME_URI.'images/shop.png'); ?>
			</div>
		</div>
		<?php if(!ThemexCore::checkOption('shop_sales') || !ThemexCore::checkOption('shop_favorites')) { ?>
		<footer class="shop-footer">
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
		</footer>
		<?php } ?>
	</div>
</div>
<div class="column sixcol">
	<div class="shop-details widget wrapped">
		<div class="widget-content">
			<div class="element-title shop-title">
				<h1><?php the_title(); ?></h1>
				<?php if(!ThemexCore::checkOption('shop_rating')) { ?>
				<div class="title-option right">
					<div class="shop-rating" title="<?php echo sprintf(_n('%d Rating', '%d Ratings', ThemexShop::$data['ratings'], 'makery'), ThemexShop::$data['ratings']);?>">
						<div class="element-rating" data-score="<?php echo ThemexShop::$data['rating']; ?>"></div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="shop-content">
				<?php the_content(); ?>
			</div>
			<div class="shop-options clearfix">
				<?php if(is_user_logged_in()) { ?>
				<form class="element-form" method="POST" action="<?php echo AJAX_URL; ?>">
					<?php if(!ThemexCore::checkOption('shop_favorites')) { ?>
					<?php if(in_array(ThemexShop::$data['ID'], ThemexUser::$data['current']['shops'])) { ?>
					<a href="#" class="element-button element-submit" data-title="<?php _e('Favorite', 'makery'); ?>"><?php _e('Unfavorite', 'makery'); ?></a>
					<input type="hidden" name="user_action" class="toggle" value="remove_relation" data-value="add_relation" />
					<?php } else { ?>
					<a href="#" class="element-button element-submit active" data-title="<?php _e('Unfavorite', 'makery'); ?>"><?php _e('Favorite', 'makery'); ?></a>
					<input type="hidden" name="user_action" class="toggle" value="add_relation" data-value="remove_relation" />
					<?php } ?>
					<?php } ?>
					<?php if(!ThemexCore::checkOption('shop_questions')) { ?>
					<a href="#contact_form" class="element-button element-colorbox square secondary" title="<?php _e('Ask a Question', 'makery'); ?>"><span class="fa fa-comment"></span></a>
					<?php } ?>
					<?php if(!ThemexCore::checkOption('shop_reports')) { ?>
					<a href="#report_form" class="element-button element-colorbox square secondary" title="<?php _e('Send a Report', 'makery'); ?>"><span class="fa fa-flag"></span></a>
					<?php } ?>
					<input type="hidden" name="relation_type" value="shop" />
					<input type="hidden" name="relation_id" value="<?php echo ThemexShop::$data['ID']; ?>" />					
					<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
				</form>
				<div class="site-popups hidden">
					<?php if(!ThemexCore::checkOption('shop_questions')) { ?>
					<div id="contact_form">
						<div class="site-popup medium">
							<form class="site-form element-form" method="POST" action="<?php echo AJAX_URL; ?>">
								<div class="field-wrap">
									<input type="text" name="email" readonly="readonly" value="<?php echo ThemexUser::$data['current']['email']; ?>" />
								</div>
								<div class="field-wrap">
									<textarea name="question" cols="30" rows="5" placeholder="<?php _e('Question', 'makery'); ?>"></textarea>
								</div>
								<a href="#" class="element-button element-submit primary"><?php _e('Send Question', 'makery'); ?></a>				
								<input type="hidden" name="shop_id" value="<?php echo ThemexShop::$data['ID']; ?>" />
								<input type="hidden" name="shop_action" value="submit_question" />
								<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_shop" />
							</form>
						</div>
					</div>
					<?php } ?>
					<?php if(!ThemexCore::checkOption('shop_reports')) { ?>
					<div id="report_form">
						<div class="site-popup medium">
							<form class="site-form element-form" method="POST" action="<?php echo AJAX_URL; ?>">
								<div class="message"></div>
								<div class="field-wrap">
									<input type="text" name="email" readonly="readonly" value="<?php echo esc_attr(ThemexUser::$data['current']['email']); ?>" />
								</div>
								<div class="field-wrap">
									<textarea name="reason" cols="30" rows="5" placeholder="<?php _e('Reason', 'makery'); ?>"></textarea>
								</div>
								<a href="#" class="element-button element-submit primary"><?php _e('Send Report', 'makery'); ?></a>
								<input type="hidden" name="shop_id" value="<?php echo ThemexShop::$data['ID']; ?>" />
								<input type="hidden" name="shop_action" value="submit_report" />
								<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_shop" />
							</form>
						</div>
					</div>
					<?php } ?>
				</div>
				<!-- /popups -->
				<?php } else { ?>
				<?php if(!ThemexCore::checkOption('shop_favorites')) { ?>
				<a href="<?php echo ThemexCore::getURL('register'); ?>" class="element-button active"><?php _e('Favorite', 'makery'); ?></a>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('shop_questions')) { ?>
				<a href="<?php echo ThemexCore::getURL('register'); ?>" class="element-button square secondary" title="<?php _e('Ask a Question', 'makery'); ?>"><span class="fa fa-comment"></span></a>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('shop_reports')) { ?>
				<a href="<?php echo ThemexCore::getURL('register'); ?>" class="element-button square secondary" title="<?php _e('Send a Report', 'makery'); ?>"><span class="fa fa-flag"></span></a>
				<?php } ?>
				<?php } ?>				
			</div>
		</div>
	</div>
</div>
<aside class="column threecol last">
	<?php get_sidebar('shop-right'); ?>
</aside>