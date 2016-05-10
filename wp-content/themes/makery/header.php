<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?></title>
	
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo THEME_URI; ?>js/html5.js"></script>
	<![endif]-->
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="site-wrap">
		<div class="header-wrap clearfix">
			<header class="site-header container">
				<div class="header-logo left">
					<a href="<?php echo SITE_URL; ?>" rel="home">
						<img src="<?php echo ThemexCore::getOption('site_logo', THEME_URI.'images/logo.png'); ?>" alt="<?php bloginfo('name'); ?>" />
					</a>
				</div>
				<!-- /logo -->
				<div class="header-options right clearfix">
					<?php if(is_user_logged_in()) { ?>
					<a href="<?php echo wp_logout_url(SITE_URL); ?>" class="element-button opaque"><?php _e('Sign Out','makery'); ?></a>
					<a href="<?php echo ThemexUser::$data['current']['links']['profile']['url']; ?>" class="element-button primary"><?php _e('My Account','makery'); ?></a>
					<?php } else { ?>
					<a href="#login_form" class="element-button element-colorbox opaque"><?php _e('Sign In','makery'); ?></a>
					<?php if(get_option('users_can_register')) { ?>
					<a href="<?php echo ThemexCore::getURL('register'); ?>" class="element-button primary"><?php _e('Register','makery'); ?></a>
					<?php } ?>					
					<div class="site-popups hidden">
						<div id="login_form">
							<div class="site-popup small">
								<form class="site-form element-form" method="POST" action="<?php echo AJAX_URL; ?>">
									<div class="field-wrap">
										<input type="text" name="user_login" value="" placeholder="<?php _e('Username', 'makery'); ?>" />
									</div>
									<div class="field-wrap">
										<input type="password" name="user_password" value="" placeholder="<?php _e('Password', 'makery'); ?>" />
									</div>
									<a href="#" class="element-button element-submit primary"><?php _e('Sign In', 'makery'); ?></a>
									<?php if(ThemexFacebook::isActive()) { ?>
									<a href="<?php echo home_url('?facebook_login=1'); ?>" class="element-button element-facebook square facebook" title="<?php _e('Sign in with Facebook', 'makery'); ?>"><span class="fa fa-facebook"></span></a>
									<?php } ?>
									<a href="#password_form" class="element-button element-colorbox square" title="<?php _e('Password Recovery', 'makery'); ?>"><span class="fa fa-life-ring"></span></a>
									<input type="hidden" name="user_action" value="login_user" />
									<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
									<input type="submit" class="hidden" value="" />
								</form>
							</div>
						</div>
						<div id="password_form">
							<div class="site-popup small">
								<form class="site-form element-form" method="POST" action="<?php echo AJAX_URL; ?>">
									<div class="field-wrap">
										<input type="text" name="user_email" value="" placeholder="<?php _e('Email', 'makery'); ?>" />
									</div>
									<a href="#" class="element-button element-submit primary"><?php _e('Reset Password', 'makery'); ?></a>
									<input type="hidden" name="user_action" value="reset_user" />
									<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
									<input type="submit" class="hidden" value="" />
								</form>
							</div>
						</div>
					</div>
					<!-- /popups -->
					<?php } ?>
					<?php if(ThemexWoo::isActive()) { ?>
					<a href="<?php echo ThemexWoo::$woocommerce->cart->get_cart_url(); ?>" class="element-button cart-button square" title="<?php _e('Cart', 'makery'); ?>">
						<span class="fa fa-shopping-cart large"></span>
					</a>
					<?php } ?>
				</div>
				<!-- /options -->
			</header>
			<!-- /header -->
			<div class="site-toolbar container">
				<nav class="header-menu element-menu left">
					<?php wp_nav_menu(array('theme_location' => 'main_menu','container_class' => 'menu')); ?>
				</nav>
				<div class="select-menu element-select redirect medium">
					<span></span>
					<?php ThemexInterface::renderDropdownMenu('main_menu'); ?>
				</div>
				<!-- /menu -->
				<?php if(ThemexWoo::isActive()) { ?>
				<div class="header-cart right">
					<a href="<?php echo ThemexWoo::$woocommerce->cart->get_cart_url(); ?>" class="cart-amount">
						<span class="fa fa-shopping-cart"></span>
						<?php echo ThemexWoo::$woocommerce->cart->get_cart_total(); ?>
					</a>
					<div class="cart-quantity"><?php echo ThemexWoo::$woocommerce->cart->cart_contents_count; ?></div>
				</div>
				<!-- /cart -->
				<?php } ?>
				<div class="header-search right">
					<form role="search" method="GET" action="<?php echo SITE_URL; ?>">
						<input type="text" value="<?php the_search_query(); ?>" name="s" />
						<?php if(ThemexWoo::isActive()) { ?>
						<input type="hidden" name="post_type" value="product">
						<?php } ?>
					</form>
					<span class="fa fa-search"></span>
				</div>
				<!-- /search -->
			</div>
			<!-- /toolbar -->
			<?php if(is_front_page() && is_page()) { ?>
				<?php get_template_part('module', 'slider'); ?>
			<?php } else if(is_singular('shop')) { ?>
				<div class="featured-wrap">
					<section class="site-featured container clearfix">
						<?php get_template_part('template', 'shop'); ?>
					</section>
				</div>
				<!-- /featured -->
			<?php } else { ?>
				<div class="site-title">
					<div class="container">
						<h1><?php ThemexInterface::renderPageTitle(); ?></h1>
					</div>
				</div>
				<!-- /title -->
			<?php } ?>
		</div>
		<div class="content-wrap">
			<section class="site-content container">