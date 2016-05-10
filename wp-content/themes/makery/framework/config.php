<?php
//Theme Configuration
$config = array (
	
	//Theme Modules
	'modules' => array(
		'ThemexInterface',
		'ThemexShortcode',
		'ThemexSidebar',
		'ThemexForm',
		'ThemexStyle',
		'ThemexUser',
		'ThemexShop',
		'ThemexWoo',
		'ThemexFacebook',
	),
	
	//Theme Components
	'components' => array(
	
		//Supports
		'supports' => array (
			'automatic-feed-links',
			'post-thumbnails',
			'woocommerce',
		),
		
		//Rewrite Rules
		'rewrite_rules' => array (
			'register' => array(
				'title' => __('Registration', 'makery'),
				'name' => 'register',
				'rule' => 'register/?',
				'rewrite' => 'index.php?register=1',
				'position' => 'top',
				'private' => false,
			),
			
			'profile' => array(
				'name' => 'profile',
				'rule' => 'author_base',
				'rewrite' => 'profile',
				'position' => 'top',
				'replace' => true,
				'dynamic' => true,
			),
			
			'profile-address' => array(
				'title' => __('My Account', 'makery'),
				'name' => 'profile-address',
				'rule' => 'profile-address/?',
				'rewrite' => 'index.php?profile-address=1',
				'position' => 'top',
				'private' => true,
			),
			
			'profile-links' => array(
				'title' => __('My Account', 'makery'),
				'name' => 'profile-links',
				'rule' => 'profile-links/?',
				'rewrite' => 'index.php?profile-links=1',
				'position' => 'top',
				'private' => true,
			),
			
			'profile-settings' => array(
				'title' => __('My Account', 'makery'),
				'name' => 'profile-settings',
				'rule' => 'profile-settings/?',
				'rewrite' => 'index.php?profile-settings=1',
				'position' => 'top',
				'private' => true,
			),
			
			'profile-referrals' => array(
				'title' => __('My Account', 'makery'),
				'name' => 'profile-referrals',
				'rule' => 'profile-referrals/?',
				'rewrite' => 'index.php?profile-referrals=1',
				'position' => 'top',
				'private' => true,
			),
			
			'profile-earnings' => array(
				'title' => __('My Account', 'makery'),
				'name' => 'profile-earnings',
				'rule' => 'profile-earnings/?',
				'rewrite' => 'index.php?profile-earnings=1',
				'position' => 'top',
				'private' => true,
			),
			
			'shop-settings' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-settings',
				'rule' => 'shop-settings/?',
				'rewrite' => 'index.php?shop-settings=1',
				'position' => 'top',
				'private' => true,
			),
			
			'shop-products' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-products',
				'rule' => 'shop-products/?',
				'rewrite' => 'index.php?shop-products=1',
				'position' => 'top',
				'private' => true,
			),
			
			'shop-product' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-product',
				'rule' => 'shop-product/([^/]+)',
				'rewrite' => 'index.php?shop-product=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'private' => true,
			),
			
			'shop-orders' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-orders',
				'rule' => 'shop-orders/?',
				'rewrite' => 'index.php?shop-orders=1',
				'position' => 'top',
				'private' => true,
			),
			
			'shop-order' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-order',
				'rule' => 'shop-order/([^/]+)',
				'rewrite' => 'index.php?shop-order=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'private' => true,
			),
			
			'shop-shipping' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-shipping',
				'rule' => 'shop-shipping/?',
				'rewrite' => 'index.php?shop-shipping=1',
				'position' => 'top',
				'private' => true,
			),
			
			'shop-membership' => array(
				'title' => __('My Shop', 'makery'),
				'name' => 'shop-membership',
				'rule' => 'shop-membership/?',
				'rewrite' => 'index.php?shop-membership=1',
				'position' => 'top',
				'private' => true,
			),
		),
	
		//User Roles
		'user_roles' => array(
			array(
				'role' => 'inactive',
				'name' => __('Inactive', 'makery'),
				'capabilities' => array(),
			),
		),
		
		//Custom Menus
		'custom_menus' => array(
			array(
				'slug' => 'main_menu',
				'name' => __('Main Menu', 'makery'),
			),
			
			array(
				'slug' => 'footer_menu',
				'name' => __('Footer Menu', 'makery'),
			),
		),
		
		//Image Sizes
		'image_sizes' => array (
			array(
				'name' => 'small',
				'width' => 200,
				'height' => 200,
				'crop' => true,
			),
			
			array(
				'name' => 'normal',
				'width' => 420,
				'height' => 9999,
				'crop' => false,
			),
			
			array(
				'name' => 'extended',
				'width' => 738,
				'height' => 9999,
				'crop' => false,
			),	
		),
		
		//Editor styles
		'editor_styles' => array(

		),
		
		//Admin Styles
		'admin_styles' => array(
			
			//colorpicker
			array(
				'name' => 'wp-color-picker',
			),
			
			//thickbox
			array(	
				'name' => 'thickbox',
			),
			
			//interface
			array(	
				'name' => 'themex-style',
				'uri' => THEMEX_URI.'assets/css/style.css'
			),			
		),
		
		//Admin Scripts
		'admin_scripts' => array(
			
			//colorpicker
			array(
				'name' => 'wp-color-picker',
			),
			
			//thickbox
			array(	
				'name' => 'thickbox',
			),
			
			//uploader
			array(	
				'name' => 'media-upload',
			),
			
			//slider
			array(	
				'name' => 'jquery-ui-slider',
			),
			
			//popup
			array(
				'name' => 'themex-popup',
				'uri' => THEMEX_URI.'assets/js/themex.popup.js',
			),
			
			//interface
			array(
				'name' => 'themex-interface',
				'uri' => THEMEX_URI.'assets/js/themex.interface.js',
			),
		),
		
		//User Styles
		'user_styles' => array(
		
			//colorbox
			array(	
				'name' => 'colorbox',
				'uri' => THEME_URI.'js/colorbox/colorbox.css',
			),
		
			//general
			array(	
				'name' => 'general',
				'uri' => CHILD_URI.'style.css',				
			),

		),
		
		//User Scripts
		'user_scripts' => array(
			
			//jquery
			array(	
				'name' => 'jquery',
			),
			
			//comment reply
			array(	
				'name' => 'comment-reply',
			),
			
			//hover intent
			array(	
				'name' => 'hover-intent',
				'uri' => THEME_URI.'js/jquery.hoverIntent.min.js',
			),
			
			//colorbox
			array(	
				'name' => 'colorbox',
				'uri' => THEME_URI.'js/colorbox/jquery.colorbox.min.js',
			),
			
			//placeholder
			array(	
				'name' => 'placeholder',
				'uri' => THEME_URI.'js/jquery.placeholder.min.js',
			),
			
			//slider
			array(	
				'name' => 'themex-slider',
				'uri' => THEME_URI.'js/jquery.themexSlider.js',
			),
			
			//autosave
			array(	
				'name' => 'themex-autosave',
				'uri' => THEME_URI.'js/jquery.themexAutosave.js',
			),
			
			//raty
			array(	
				'name' => 'raty',
				'uri' => THEME_URI.'js/jquery.raty.min.js',
				'options' => array(
					'templateDirectory' => THEME_URI,
				),
			),

			//general
			array(
				'name' => 'general',
				'uri' => THEME_URI.'js/general.js',
				'options' => array(
					'templateDirectory' => THEME_URI,
				),
			),
		),
		
		//Widget Settings
		'widget_settings' => array (
			'before_widget' => '<div class="widget sidebar-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><h4>',
			'after_title' => '</h4></div>',
		),
		
		//Widget Areas
		'widget_areas' => array (
		
			array(
				'id' => 'profile',
				'name' => __('Profile', 'makery'),
				'before_widget' => '<div class="widget sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h4>',
				'after_title' => '</h4></div>',				
			),
			
			array(
				'id' => 'shops',
				'name' => __('Shops', 'makery'),
				'before_widget' => '<div class="widget sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h4>',
				'after_title' => '</h4></div>',				
			),
			
			array(
				'id' => 'shop',
				'name' => __('Shop', 'makery'),
				'before_widget' => '<div class="widget sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h4>',
				'after_title' => '</h4></div>',				
			),
			
			array(
				'id' => 'products',
				'name' => __('Products', 'makery'),
				'before_widget' => '<div class="widget sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h4>',
				'after_title' => '</h4></div>',				
			),
			
			array(
				'id' => 'product',
				'name' => __('Product', 'makery'),
				'before_widget' => '<div class="widget sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h4>',
				'after_title' => '</h4></div>',				
			),
			
			array(
				'id' => 'footer',
				'name' => __('Footer', 'makery'),
				'before_widget' => '<div class="fourcol column"><div class="widget footer-widget %2$s">',
				'after_widget' => '</div></div>',
				'before_title' => '<div class="widget-title"><h4>',
				'after_title' => '</h4></div>',				
			),
		),
		
		//Widgets
		'widgets' => array (
			'ThemexSearch',
		),
		
		//Post Types
		'post_types' => array (
		
			//Shop
			array (
				'id' => 'shop',
				'labels' => array (
					'name' => __('Shops', 'makery'),
					'singular_name' => __('Shop', 'makery' ),
					'add_new' => __('Add New', 'makery'),
					'add_new_item' => __('Add New Shop', 'makery'),
					'edit_item' => __('Edit Shop', 'makery'),
					'new_item' => __('New Shop', 'makery'),
					'view_item' => __('View Shop', 'makery'),
					'search_items' => __('Search Shops', 'makery'),
					'not_found' =>  __('No Shops Found', 'makery'),
					'not_found_in_trash' => __('No Shops Found in Trash', 'makery'),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'thumbnail', 'author', 'revisions'),
				'rewrite' => array('slug' => __('shop', 'makery')),
			),
			
			//Membership
			array (
				'id' => 'membership',
				'labels' => array (
					'name' => __('Memberships', 'makery'),
					'singular_name' => __( 'Membership', 'makery' ),
					'add_new' => __('Add New', 'makery'),
					'add_new_item' => __('Add New Membership', 'makery'),
					'edit_item' => __('Edit Membership', 'makery'),
					'new_item' => __('New Membership', 'makery'),
					'view_item' => __('View Membership', 'makery'),
					'search_items' => __('Search Memberships', 'makery'),
					'not_found' =>  __('No Memberships Found', 'makery'),
					'not_found_in_trash' => __('No Memberships Found in Trash', 'makery'),
				 ),
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => 'edit.php?post_type=shop',
				'exclude_from_search' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'page-attributes'),
				'rewrite' => array('slug' => __('membership', 'makery')),
			),
			
			//Withdrawal
			array (
				'id' => 'withdrawal',
				'labels' => array (
					'name' => __('Withdrawals', 'makery'),
					'singular_name' => __( 'Withdrawal', 'makery' ),
					'add_new' => __('Add New', 'makery'),
					'add_new_item' => __('Add New Withdrawal', 'makery'),
					'edit_item' => __('Edit Withdrawal', 'makery'),
					'new_item' => __('New Withdrawal', 'makery'),
					'view_item' => __('View Withdrawal', 'makery'),
					'search_items' => __('Search Withdrawals', 'makery'),
					'not_found' =>  __('No Withdrawals Found', 'makery'),
					'not_found_in_trash' => __('No Withdrawals Found in Trash', 'makery'),
				 ),
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'exclude_from_search' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'author'),
				'rewrite' => array('slug' => __('withdrawal', 'makery')),
			),
			
			//Testimonial
			array (
				'id' => 'testimonial',
				'labels' => array (
					'name' => __('Testimonials', 'makery'),
					'singular_name' => __( 'Testimonial', 'makery' ),
					'add_new' => __('Add New', 'makery'),
					'add_new_item' => __('Add New Testimonial', 'makery'),
					'edit_item' => __('Edit Testimonial', 'makery'),
					'new_item' => __('New Testimonial', 'makery'),
					'view_item' => __('View Testimonial', 'makery'),
					'search_items' => __('Search Testimonials', 'makery'),
					'not_found' =>  __('No Testimonials Found', 'makery'),
					'not_found_in_trash' => __('No Testimonials Found in Trash', 'makery'),
				 ),
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'exclude_from_search' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'thumbnail'),
				'rewrite' => array('slug' => __('testimonial', 'makery')),
			),
			
			//Slide
			array (
				'id' => 'slide',
				'labels' => array (
					'name' => __('Slides', 'makery'),
					'singular_name' => __( 'Slide', 'makery' ),
					'add_new' => __('Add New', 'makery'),
					'add_new_item' => __('Add New Slide', 'makery'),
					'edit_item' => __('Edit Slide', 'makery'),
					'new_item' => __('New Slide', 'makery'),
					'view_item' => __('View Slide', 'makery'),
					'search_items' => __('Search Slides', 'makery'),
					'not_found' =>  __('No Slides Found', 'makery'),
					'not_found_in_trash' => __('No Slides Found in Trash', 'makery'),
				 ),
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'exclude_from_search' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'page-attributes'),
				'rewrite' => array('slug' => __('slide', 'makery')),
			),
		),
		
		//Taxonomies
		'taxonomies' => array (
		
			//Shop Category
			array(
				'taxonomy' => 'shop_category',
				'object_type' => array('shop'),
				'settings' => array(
					'hierarchical' => true,
					'show_in_nav_menus' => true,
					'labels' => array(
	                    'name' => __( 'Shop Categories', 'makery'),
	                    'singular_name' => __( 'Shop Category', 'makery'),
						'menu_name' => __( 'Categories', 'makery' ),
	                    'search_items' => __( 'Search Shop Categories', 'makery'),
	                    'all_items' => __( 'All Shop Categories', 'makery'),
	                    'parent_item' => __( 'Parent Shop Category', 'makery'),
	                    'parent_item_colon' => __( 'Parent Shop Category', 'makery'),
	                    'edit_item' => __( 'Edit Shop Category', 'makery'),
	                    'update_item' => __( 'Update Shop Category', 'makery'),
	                    'add_new_item' => __( 'Add New Shop Category', 'makery'),
	                    'new_item_name' => __( 'New Shop Category Name', 'makery'),
	            	),
					'rewrite' => array(
						'slug' => __('shops', 'makery'),
						'hierarchical' => true,
					),
				),
			),
			
			//Testimonial Category
			array(
				'taxonomy' => 'testimonial_category',
				'object_type' => array('testimonial'),
				'settings' => array(
					'hierarchical' => true,
					'show_in_nav_menus' => true,			
					'labels' => array(
	                    'name' => __( 'Testimonial Categories', 'makery'),
	                    'singular_name' => __( 'Testimonial Category', 'makery'),
						'menu_name' => __( 'Categories', 'makery' ),
	                    'search_items' => __( 'Search Testimonial Categories', 'makery'),
	                    'all_items' => __( 'All Testimonial Categories', 'makery'),
	                    'parent_item' => __( 'Parent Testimonial Category', 'makery'),
	                    'parent_item_colon' => __( 'Parent Testimonial Category', 'makery'),
	                    'edit_item' => __( 'Edit Testimonial Category', 'makery'),
	                    'update_item' => __( 'Update Testimonial Category', 'makery'),
	                    'add_new_item' => __( 'Add New Testimonial Category', 'makery'),
	                    'new_item_name' => __( 'New Testimonial Category Name', 'makery'),
	            	),
					'rewrite' => array(
						'slug' => __('testimonials', 'makery'),
						'hierarchical' => true,
					),
				),
			),
		),
		
		//Meta Boxes
		'meta_boxes' => array(
		
			//Shop
			array(
				'id' => 'shop_details_metabox',
				'title' =>  __('Shop Details', 'makery'),
				'page' => 'shop',
				'context' => 'normal',
				'priority' => 'high',
				'callback' => array('ThemexShop', 'renderShop'),
				'options' => array(
					array(	
						'name' => __('Custom Rate', 'makery'),
						'id' => 'rate',
						'type' => 'number',
					),
				),
			),
			
			array(
				'id' => 'shop_pages_metabox',
				'title' =>  __('Shop Pages', 'makery'),
				'page' => 'shop',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('About', 'makery'),
						'id' => 'about',
						'type' => 'editor',						
					),
					
					array(	
						'name' => __('Policies', 'makery'),
						'id' => 'policy',
						'type' => 'editor',						
					),
				),
			),
			
			//Membership
			array(
				'id' => 'membership_metabox',
				'title' =>  __('Membership Options', 'makery'),
				'page' => 'membership',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Product', 'makery'),
						'id' => 'product',
						'type' => 'select_post',
						'post_type' => 'product',
					),
					
					array(
						'name' => __('Period', 'makery'),
						'id' => 'period',
						'type' => 'select',
						'options' => array(
							'0' => __('None', 'makery'),
							'7' => __('Week', 'makery'),
							'31' => __('Month', 'makery'),
							'93' => __('3 Months', 'makery'),
							'186' => __('6 Months', 'makery'),
							'279' => __('9 Months', 'makery'),
							'365' => __('Year', 'makery'),
						),
					),
					
					array(
						'name' => __('Products','makery'),
						'id' => 'products',
						'type' => 'number',
						'default' => '0',
					),
					
					array(
						'name' => __('Members', 'makery'),
						'id' => 'users',
						'type' => 'users',
					),					
				),
			),
			
			//Withdrawal
			array(
				'id' => 'withdrawal_metabox',
				'title' =>  __('Withdrawal Details', 'makery'),
				'page' => 'withdrawal',
				'context' => 'normal',
				'priority' => 'high',
				'callback' => array('ThemexShop', 'renderWithdrawal'),
				'options' => array(
					
				),
			),
		),
		
		//Custom Forms
		'forms' => array(
			'profile' => array(
				array(
					'label' => __('First Name', 'makery'),
					'name' => 'first_name',
					'alias' => 'billing_first_name',
					'type' => 'text',
					'prefix' => false,
				),
				array(
					'label' => __('Last Name', 'makery'),
					'name' => 'last_name',
					'alias' => 'billing_last_name',
					'type' => 'text',
					'prefix' => false,
				),
				array(
					'label' => __('Country', 'makery'),
					'name' => 'billing_country',
					'type' => 'select_country',
					'prefix' => false,
				),
				array(
					'label' => __('City', 'makery'),
					'name' => 'billing_city',
					'type' => 'text',
					'prefix' => false,
				),
			),
			'links' => array(
				array(
					'label' => __('Facebook', 'makery'),
					'name' => 'facebook',
					'type' => 'text',
				),
				array(
					'label' => __('Twitter', 'makery'),
					'name' => 'twitter',
					'type' => 'text',
				),
				array(
					'label' => __('Google', 'makery'),
					'name' => 'google',
					'type' => 'text',
				),
				array(
					'label' => __('Tumblr', 'makery'),
					'name' => 'tumblr',
					'type' => 'text',
				),
				array(
					'label' => __('Pinterest', 'makery'),
					'name' => 'pinterest',
					'type' => 'text',
				),
				array(
					'label' => __('Instagram', 'makery'),
					'name' => 'instagram',
					'type' => 'text',
				),
				array(
					'label' => __('YouTube', 'makery'),
					'name' => 'youtube',
					'type' => 'text',
				),
			),
			'address' => array(
				
			),
			'withdrawal' => array(
				'amount' => array(
					'label' => __('Withdrawal Amount', 'makery'),
					'name' => 'amount',
					'type' => 'text',
				),
				'method' => array(
					'label' => __('Withdrawal Method', 'makery'),
					'name' => 'method',
					'type' => 'select',
					'options' => array(
						'paypal' => __('PayPal', 'makery'),
						'skrill' => __('Skrill', 'makery'),
						'swift' => __('SWIFT', 'makery'),
					),
				),
				'paypal' => array(
					array(
						'label' => __('PayPal Email', 'makery'),
						'name' => 'paypal',
						'type' => 'email',
					),					
				),
				'skrill' => array(
					array(
						'label' => __('Skrill Email', 'makery'),
						'name' => 'skrill',
						'type' => 'email',
					),
				),
				'swift' => array(
					'account_name' => array(
						'label' => __('Account Holder Name', 'makery'),
						'name' => 'account_name',
						'type' => 'text',
					),
					'account_number' => array(
						'label' => __('Account Number/IBAN', 'makery'),
						'name' => 'account_number',
						'type' => 'text',
					),
					'swift_code' => array(
						'label' => __('SWIFT Code', 'makery'),
						'name' => 'swift_code',
						'type' => 'text',
					),
					'bank_name' => array(
						'label' => __('Bank Name', 'makery'),
						'name' => 'bank_name',
						'type' => 'text',
					),
					'bank_country' => array(
						'label' => __('Bank Country', 'makery'),
						'name' => 'bank_country',
						'type' => 'select_country',
					),
				),
			),
		),
		
		//Shortcodes
		'shortcodes' => array(
			
			//Button
			array(
				'id' => 'button',
				'name' => __('Button', 'makery'),
				'shortcode' => '[button color="{{color}}" size="{{size}}" url="{{url}}" target="{{target}}"]{{content}}[/button]',
				'options' => array(
					array(
						'id' => 'color',
						'name' => __('Color', 'makery'),						
						'type' => 'select',
						'options' => array(
							'default' => __('Default', 'makery'),
							'primary' => __('Primary', 'makery'),
							'secondary' => __('Secondary', 'makery'),
							'opaque' => __('Opaque', 'makery'),
						),
					),
				
					array(			
						'id' => 'size',
						'name' => __('Size', 'makery'),						
						'type' => 'select',
						'options' => array(
							'small' => __('Small', 'makery'),
							'medium' => __('Medium', 'makery'),
							'large' => __('Large', 'makery'),
						),
					),
					
					array(			
						'id' => 'url',
						'name' => __('Link', 'makery'),			
						'type' => 'text',
					),
					
					array(			
						'id' => 'target',
						'name' => __('Target', 'makery'),			
						'type' => 'select',
						'options' => array(
							'self' => __('Current Tab', 'makery'),
							'blank' => __('New Tab', 'makery'),
						),
					),
					
					array(
						'id' => 'content',
						'name' => __('Text', 'makery'),						
						'type' => 'text',
					),
				),
			),
			
			//Columns
			array(
				'id' => 'column',
				'name' => __('Columns', 'makery'),
				'shortcode' => '{{clone}}',
				'clone' => array(
					'shortcode' => '[{{column}}]{{content}}[/{{column}}]',
					'options' => array(
						array(
							'id' => 'column',
							'name' => __('Width', 'makery'),
							'type' => 'select',
							'options' => array(
								'one_sixth' => __('One Sixth', 'makery'),
								'one_sixth_last' => __('One Sixth Last', 'makery'),
								'one_fourth' => __('One Fourth', 'makery'),
								'one_fourth_last' => __('One Fourth Last', 'makery'),
								'one_third' => __('One Third', 'makery'),
								'one_third_last' => __('One Third Last', 'makery'),
								'five_twelfths' => __('Five Twelfths', 'makery'),
								'five_twelfths_last' => __('Five Twelfths Last', 'makery'),
								'one_half' => __('One Half', 'makery'),
								'one_half_last' => __('One Half Last', 'makery'),
								'seven_twelfths' => __('Seven Twelfths', 'makery'),
								'seven_twelfths_last' => __('Seven Twelfths Last', 'makery'),
								'two_thirds' => __('Two Thirds', 'makery'),
								'two_thirds_last' => __('Two Thirds Last', 'makery'),
								'three_fourths' => __('Three Fourths', 'makery'),
								'three_fourths_last' => __('Three Fourths Last', 'makery'),
							),
						),
						
						array(
							'id' => 'content',
							'name' => __('Text', 'makery'),						
							'type' => 'textarea',
						),
					),
				),
			),

			//Section
			array(
				'id' => 'section',
				'name' => __('Section', 'makery'),
				'shortcode' => '[section background="{{background}}"]{{content}}[/section]',
				'options' => array(
					array(			
						'id' => 'background',
						'name' => __('Background', 'makery'),						
						'type' => 'text',
					),				
			
					array(
						'id' => 'content',
						'name' => __('Text', 'makery'),						
						'type' => 'textarea',
					),				
				),
			),
			
			//Shops
			array(
				'id' => 'shops',
				'name' => __('Shops', 'makery'),
				'shortcode' => '[shops number="{{number}}" columns="{{columns}}" order="{{order}}" category="{{category}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'makery'),
						'value' => '3',
						'type' => 'number',
					),

					array(
						'id' => 'columns',
						'name' => __('Columns', 'makery'),
						'value' => '3',
						'type' => 'select',
						'options' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
						),
					),
					
					array(			
						'id' => 'order',
						'name' => __('Order', 'makery'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'makery'),
							'title' => __('Title', 'makery'),
							'rating' => __('Rating', 'makery'),
							'sales' => __('Sales', 'makery'),
							'admirers' => __('Admirers', 'makery'),							
							'random' => __('Random', 'makery'),
						),
					),
					
					array(
						'id' => 'category',
						'name' => __('Category', 'makery'),			
						'type' => 'select_category',
						'taxonomy' => 'shop_category',
					),
				),
			),
			
			//Testimonials
			array(
				'id' => 'testimonials',
				'name' => __('Testimonials', 'makery'),
				'shortcode' => '[testimonials number="{{number}}" order="{{order}}" category="{{category}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'makery'),
						'value' => '3',
						'type' => 'number',
					),		
					
					array(		
						'id' => 'order',
						'name' => __('Order', 'makery'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'makery'),
							'random' => __('Random', 'makery'),
						),
					),
					
					array(			
						'id' => 'category',
						'name' => __('Category', 'makery'),		
						'type' => 'select_category',
						'taxonomy' => 'testimonial_category',
					),
					
					array(
						'id' => 'pause',
						'name' => __('Pause', 'makery'),
						'value' => '0',
						'type' => 'number',
					),
					
					array(
						'id' => 'speed',
						'name' => __('Speed', 'makery'),
						'value' => '900',
						'type' => 'number',
					),
				),
			),
			
			//Title
			array(
				'id' => 'title',
				'name' => __('Title', 'makery'),
				'shortcode' => '[title indent="{{indent}}"]{{content}}[/title]',
				'options' => array(
					array(
						'id' => 'content',
						'name' => __('Title', 'makery'),
						'type' => 'text',
					),
					
					array(			
						'id' => 'indent',
						'name' => __('Indent', 'makery'),			
						'type' => 'number',
						'value' => '0',
					),	
				),
			),
			
			//Users
			array(
				'id' => 'users',
				'name' => __('Users', 'makery'),
				'shortcode' => '[users number="{{number}}" columns="{{columns}}" order="{{order}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'makery'),
						'value' => '3',
						'type' => 'number',
					),
					
					array(
						'id' => 'columns',
						'name' => __('Columns', 'makery'),
						'value' => '3',
						'type' => 'select',
						'options' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
						),
					),
					
					array(
						'id' => 'order',
						'name' => __('Order', 'makery'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'makery'),
							'name' => __('Name', 'makery'),
							'activity' => __('Activity', 'makery'),
						),
					),			
				),
			),
		),
		
		//Custom Styles
		'custom_styles' => array(
			array(
				'elements' => '.header-wrap,.section-wrap,.footer-wrap,.widget-title,.header-menu ul ul',
				'attributes' => array(
					array(
						'name' => 'background-image',
						'option' => 'background_image',
					),
				),
			),
			
			array(
				'elements' => '.header-wrap,.section-wrap,.footer-wrap',
				'attributes' => array(
					array(
						'name' => 'background-size',
						'option' => 'background_type',
					),
				),
			),
			
			array(
				'elements' => 'body, input, select, textarea',
				'attributes' => array(
					array(
						'name' => 'font-family',
						'option' => 'content_font',
					),
				),
			),
			
			array(
				'elements' => 'h1,h2,h3,h4,h5,h6',
				'attributes' => array(
					array(
						'name' => 'font-family',
						'option' => 'heading_font',
					),
				),
			),
			
			array(
				'elements' => '.item-preview .item-options a.primary.active,.item-preview .item-options a.primary:hover,.item-sale,.element-button.primary,.element-button.active,.woocommerce #review_form #respond .form-submit input,.woocommerce #review_form #respond .form-submit input:hover,.woocommerce .widget_shopping_cart .button.checkout,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,.woocommerce #content input.button.alt,.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce #content input.button.alt:hover',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.item-preview .item-options a.primary.active,.item-preview .item-options a.primary:hover,.element-button.primary,.element-button.active,.woocommerce #review_form #respond .form-submit input,.woocommerce #review_form #respond .form-submit input:hover,.woocommerce .widget_shopping_cart .button.checkout,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,.woocommerce #content input.button.alt,.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce #content input.button.alt:hover',
				'attributes' => array(
					array(
						'name' => 'border-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.element-button,.header-cart,.items-toolbar a.active,.pagination > span,.item-preview .item-options a.active,.item-preview .item-options a.added,.item-preview .item-options a.loading,.item-preview .item-options a:hover,.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce #content input.button:hover',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'secondary_color',
					),
				),
			),
			
			array(
				'elements' => '.element-button,.items-toolbar a.active,.pagination > span,.item-preview .item-options a.active,.item-preview .item-options a.added,.item-preview .item-options a.loading,.item-preview .item-options a:hover,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce #content input.button:hover',
				'attributes' => array(
					array(
						'name' => 'border-color',
						'option' => 'secondary_color',
					),
				),
			),
		),
		
		//Fonts
		'fonts' => array(
			'ABeeZee' => 'ABeeZee',
			'Abel' => 'Abel',
			'Abril Fatface' => 'Abril Fatface',
			'Aclonica' => 'Aclonica',
			'Acme' => 'Acme',
			'Actor' => 'Actor',
			'Adamina' => 'Adamina',
			'Advent Pro' => 'Advent Pro',
			'Aguafina Script' => 'Aguafina Script',
			'Aladin' => 'Aladin',
			'Aldrich' => 'Aldrich',
			'Alegreya' => 'Alegreya',
			'Alegreya SC' => 'Alegreya SC',
			'Alex Brush' => 'Alex Brush',
			'Alfa Slab One' => 'Alfa Slab One',
			'Alice' => 'Alice',
			'Alike' => 'Alike',
			'Alike Angular' => 'Alike Angular',
			'Allan' => 'Allan',
			'Allerta' => 'Allerta',
			'Allerta Stencil' => 'Allerta Stencil',
			'Allura' => 'Allura',
			'Almendra' => 'Almendra',
			'Almendra SC' => 'Almendra SC',
			'Amaranth' => 'Amaranth',
			'Amatic SC' => 'Amatic SC',
			'Amethysta' => 'Amethysta',
			'Andada' => 'Andada',
			'Andika' => 'Andika',
			'Angkor' => 'Angkor',
			'Annie Use Your Telescope' => 'Annie Use Your Telescope',
			'Anonymous Pro' => 'Anonymous Pro',
			'Antic' => 'Antic',
			'Antic Didone' => 'Antic Didone',
			'Antic Slab' => 'Antic Slab',
			'Anton' => 'Anton',
			'Arapey' => 'Arapey',
			'Arbutus' => 'Arbutus',
			'Architects Daughter' => 'Architects Daughter',
			'Arimo' => 'Arimo',
			'Arizonia' => 'Arizonia',
			'Armata' => 'Armata',
			'Artifika' => 'Artifika',
			'Arvo' => 'Arvo',
			'Asap' => 'Asap',
			'Asset' => 'Asset',
			'Astloch' => 'Astloch',
			'Asul' => 'Asul',
			'Atomic Age' => 'Atomic Age',
			'Aubrey' => 'Aubrey',
			'Audiowide' => 'Audiowide',
			'Average' => 'Average',
			'Averia Gruesa Libre' => 'Averia Gruesa Libre',
			'Averia Libre' => 'Averia Libre',
			'Averia Sans Libre' => 'Averia Sans Libre',
			'Averia Serif Libre' => 'Averia Serif Libre',
			'Bad Script' => 'Bad Script',
			'Balthazar' => 'Balthazar',
			'Bangers' => 'Bangers',
			'Basic' => 'Basic',
			'Battambang' => 'Battambang',
			'Baumans' => 'Baumans',
			'Bayon' => 'Bayon',
			'Belgrano' => 'Belgrano',
			'Belleza' => 'Belleza',
			'Bentham' => 'Bentham',
			'Berkshire Swash' => 'Berkshire Swash',
			'Bevan' => 'Bevan',
			'Bigshot One' => 'Bigshot One',
			'Bilbo' => 'Bilbo',
			'Bilbo Swash Caps' => 'Bilbo Swash Caps',
			'Bitter' => 'Bitter',
			'Black Ops One' => 'Black Ops One',
			'Bokor' => 'Bokor',
			'Bonbon' => 'Bonbon',
			'Boogaloo' => 'Boogaloo',
			'Bowlby One' => 'Bowlby One',
			'Bowlby One SC' => 'Bowlby One SC',
			'Brawler' => 'Brawler',
			'Bree Serif' => 'Bree Serif',
			'Bubblegum Sans' => 'Bubblegum Sans',
			'Buda' => 'Buda',
			'Buenard' => 'Buenard',
			'Butcherman' => 'Butcherman',
			'Butterfly Kids' => 'Butterfly Kids',
			'Cabin' => 'Cabin',
			'Cabin Condensed' => 'Cabin Condensed',
			'Cabin Sketch' => 'Cabin Sketch',
			'Caesar Dressing' => 'Caesar Dressing',
			'Cagliostro' => 'Cagliostro',
			'Calligraffitti' => 'Calligraffitti',
			'Cambo' => 'Cambo',
			'Candal' => 'Candal',
			'Cantarell' => 'Cantarell',
			'Cantata One' => 'Cantata One',
			'Cardo' => 'Cardo',
			'Carme' => 'Carme',
			'Carter One' => 'Carter One',
			'Caudex' => 'Caudex',
			'Cedarville Cursive' => 'Cedarville Cursive',
			'Ceviche One' => 'Ceviche One',
			'Changa One' => 'Changa One',
			'Chango' => 'Chango',
			'Chau Philomene One' => 'Chau Philomene One',
			'Chelsea Market' => 'Chelsea Market',
			'Chenla' => 'Chenla',
			'Cherry Cream Soda' => 'Cherry Cream Soda',
			'Chewy' => 'Chewy',
			'Chicle' => 'Chicle',
			'Chivo' => 'Chivo',
			'Coda' => 'Coda',
			'Coda Caption' => 'Coda Caption',
			'Codystar' => 'Codystar',
			'Comfortaa' => 'Comfortaa',
			'Coming Soon' => 'Coming Soon',
			'Concert One' => 'Concert One',
			'Condiment' => 'Condiment',
			'Content' => 'Content',
			'Contrail One' => 'Contrail One',
			'Convergence' => 'Convergence',
			'Cookie' => 'Cookie',
			'Copse' => 'Copse',
			'Corben' => 'Corben',
			'Cousine' => 'Cousine',
			'Coustard' => 'Coustard',
			'Covered By Your Grace' => 'Covered By Your Grace',
			'Crafty Girls' => 'Crafty Girls',
			'Creepster' => 'Creepster',
			'Crete Round' => 'Crete Round',
			'Crimson Text' => 'Crimson Text',
			'Crushed' => 'Crushed',
			'Cuprum' => 'Cuprum',
			'Cutive' => 'Cutive',
			'Damion' => 'Damion',
			'Dancing Script' => 'Dancing Script',
			'Dangrek' => 'Dangrek',
			'Dawning of a New Day' => 'Dawning of a New Day',
			'Days One' => 'Days One',
			'Delius' => 'Delius',
			'Delius Swash Caps' => 'Delius Swash Caps',
			'Delius Unicase' => 'Delius Unicase',
			'Della Respira' => 'Della Respira',
			'Devonshire' => 'Devonshire',
			'Didact Gothic' => 'Didact Gothic',
			'Diplomata' => 'Diplomata',
			'Diplomata SC' => 'Diplomata SC',
			'Doppio One' => 'Doppio One',
			'Dorsa' => 'Dorsa',
			'Dosis' => 'Dosis',
			'Dr Sugiyama' => 'Dr Sugiyama',
			'Droid Sans' => 'Droid Sans',
			'Droid Sans Mono' => 'Droid Sans Mono',
			'Droid Serif' => 'Droid Serif',
			'Duru Sans' => 'Duru Sans',
			'Dynalight' => 'Dynalight',
			'EB Garamond' => 'EB Garamond',
			'Eater' => 'Eater',
			'Economica' => 'Economica',
			'Electrolize' => 'Electrolize',
			'Emblema One' => 'Emblema One',
			'Emilys Candy' => 'Emilys Candy',
			'Engagement' => 'Engagement',
			'Enriqueta' => 'Enriqueta',
			'Erica One' => 'Erica One',
			'Esteban' => 'Esteban',
			'Euphoria Script' => 'Euphoria Script',
			'Ewert' => 'Ewert',
			'Exo' => 'Exo',
			'Expletus Sans' => 'Expletus Sans',
			'Fanwood Text' => 'Fanwood Text',
			'Fascinate' => 'Fascinate',
			'Fascinate Inline' => 'Fascinate Inline',
			'Federant' => 'Federant',
			'Federo' => 'Federo',
			'Felipa' => 'Felipa',
			'Fjord One' => 'Fjord One',
			'Flamenco' => 'Flamenco',
			'Flavors' => 'Flavors',
			'Fondamento' => 'Fondamento',
			'Fontdiner Swanky' => 'Fontdiner Swanky',
			'Forum' => 'Forum',
			'Francois One' => 'Francois One',
			'Fredericka the Great' => 'Fredericka the Great',
			'Fredoka One' => 'Fredoka One',
			'Freehand' => 'Freehand',
			'Fresca' => 'Fresca',
			'Frijole' => 'Frijole',
			'Fugaz One' => 'Fugaz One',
			'GFS Didot' => 'GFS Didot',
			'GFS Neohellenic' => 'GFS Neohellenic',
			'Galdeano' => 'Galdeano',
			'Gentium Basic' => 'Gentium Basic',
			'Gentium Book Basic' => 'Gentium Book Basic',
			'Geo' => 'Geo',
			'Geostar' => 'Geostar',
			'Geostar Fill' => 'Geostar Fill',
			'Germania One' => 'Germania One',
			'Give You Glory' => 'Give You Glory',
			'Glass Antiqua' => 'Glass Antiqua',
			'Glegoo' => 'Glegoo',
			'Gloria Hallelujah' => 'Gloria Hallelujah',
			'Goblin One' => 'Goblin One',
			'Gochi Hand' => 'Gochi Hand',
			'Gorditas' => 'Gorditas',
			'Goudy Bookletter 1911' => 'Goudy Bookletter 1911',
			'Graduate' => 'Graduate',
			'Gravitas One' => 'Gravitas One',
			'Great Vibes' => 'Great Vibes',
			'Gruppo' => 'Gruppo',
			'Gudea' => 'Gudea',
			'Habibi' => 'Habibi',
			'Hammersmith One' => 'Hammersmith One',
			'Handlee' => 'Handlee',
			'Hanuman' => 'Hanuman',
			'Happy Monkey' => 'Happy Monkey',
			'Henny Penny' => 'Henny Penny',
			'Herr Von Muellerhoff' => 'Herr Von Muellerhoff',
			'Holtwood One SC' => 'Holtwood One SC',
			'Homemade Apple' => 'Homemade Apple',
			'Homenaje' => 'Homenaje',
			'IM Fell DW Pica' => 'IM Fell DW Pica',
			'IM Fell DW Pica SC' => 'IM Fell DW Pica SC',
			'IM Fell Double Pica' => 'IM Fell Double Pica',
			'IM Fell Double Pica SC' => 'IM Fell Double Pica SC',
			'IM Fell English' => 'IM Fell English',
			'IM Fell English SC' => 'IM Fell English SC',
			'IM Fell French Canon' => 'IM Fell French Canon',
			'IM Fell French Canon SC' => 'IM Fell French Canon SC',
			'IM Fell Great Primer' => 'IM Fell Great Primer',
			'IM Fell Great Primer SC' => 'IM Fell Great Primer SC',
			'Iceberg' => 'Iceberg',
			'Iceland' => 'Iceland',
			'Imprima' => 'Imprima',
			'Inconsolata' => 'Inconsolata',
			'Inder' => 'Inder',
			'Indie Flower' => 'Indie Flower',
			'Inika' => 'Inika',
			'Irish Grover' => 'Irish Grover',
			'Istok Web' => 'Istok Web',
			'Italiana' => 'Italiana',
			'Italianno' => 'Italianno',
			'Jim Nightshade' => 'Jim Nightshade',
			'Jockey One' => 'Jockey One',
			'Jolly Lodger' => 'Jolly Lodger',
			'Josefin Sans' => 'Josefin Sans',
			'Josefin Slab' => 'Josefin Slab',
			'Judson' => 'Judson',
			'Julee' => 'Julee',
			'Junge' => 'Junge',
			'Jura' => 'Jura',
			'Just Another Hand' => 'Just Another Hand',
			'Just Me Again Down Here' => 'Just Me Again Down Here',
			'Kameron' => 'Kameron',
			'Karla' => 'Karla',
			'Kaushan Script' => 'Kaushan Script',
			'Kelly Slab' => 'Kelly Slab',
			'Kenia' => 'Kenia',
			'Khmer' => 'Khmer',
			'Knewave' => 'Knewave',
			'Kotta One' => 'Kotta One',
			'Koulen' => 'Koulen',
			'Kranky' => 'Kranky',
			'Kreon' => 'Kreon',
			'Kristi' => 'Kristi',
			'Krona One' => 'Krona One',
			'La Belle Aurore' => 'La Belle Aurore',
			'Lancelot' => 'Lancelot',
			'Lato' => 'Lato',
			'League Script' => 'League Script',
			'Leckerli One' => 'Leckerli One',
			'Ledger' => 'Ledger',
			'Lekton' => 'Lekton',
			'Lemon' => 'Lemon',
			'Lilita One' => 'Lilita One',
			'Limelight' => 'Limelight',
			'Linden Hill' => 'Linden Hill',
			'Lobster' => 'Lobster',
			'Lobster Two' => 'Lobster Two',
			'Londrina Outline' => 'Londrina Outline',
			'Londrina Shadow' => 'Londrina Shadow',
			'Londrina Sketch' => 'Londrina Sketch',
			'Londrina Solid' => 'Londrina Solid',
			'Lora' => 'Lora',
			'Love Ya Like A Sister' => 'Love Ya Like A Sister',
			'Loved by the King' => 'Loved by the King',
			'Lovers Quarrel' => 'Lovers Quarrel',
			'Luckiest Guy' => 'Luckiest Guy',
			'Lusitana' => 'Lusitana',
			'Lustria' => 'Lustria',
			'Macondo' => 'Macondo',
			'Macondo Swash Caps' => 'Macondo Swash Caps',
			'Magra' => 'Magra',
			'Maiden Orange' => 'Maiden Orange',
			'Mako' => 'Mako',
			'Marck Script' => 'Marck Script',
			'Marko One' => 'Marko One',
			'Marmelad' => 'Marmelad',
			'Marvel' => 'Marvel',
			'Mate' => 'Mate',
			'Mate SC' => 'Mate SC',
			'Maven Pro' => 'Maven Pro',
			'Meddon' => 'Meddon',
			'MedievalSharp' => 'MedievalSharp',
			'Medula One' => 'Medula One',
			'Megrim' => 'Megrim',
			'Merienda One' => 'Merienda One',
			'Merriweather' => 'Merriweather',
			'Metal' => 'Metal',
			'Metamorphous' => 'Metamorphous',
			'Metrophobic' => 'Metrophobic',
			'Michroma' => 'Michroma',
			'Miltonian' => 'Miltonian',
			'Miltonian Tattoo' => 'Miltonian Tattoo',
			'Miniver' => 'Miniver',
			'Miss Fajardose' => 'Miss Fajardose',
			'Modern Antiqua' => 'Modern Antiqua',
			'Molengo' => 'Molengo',
			'Monofett' => 'Monofett',
			'Monoton' => 'Monoton',
			'Monsieur La Doulaise' => 'Monsieur La Doulaise',
			'Montaga' => 'Montaga',
			'Montez' => 'Montez',
			'Montserrat' => 'Montserrat',
			'Moul' => 'Moul',
			'Moulpali' => 'Moulpali',
			'Mountains of Christmas' => 'Mountains of Christmas',
			'Mr Bedfort' => 'Mr Bedfort',
			'Mr Dafoe' => 'Mr Dafoe',
			'Mr De Haviland' => 'Mr De Haviland',
			'Mrs Saint Delafield' => 'Mrs Saint Delafield',
			'Mrs Sheppards' => 'Mrs Sheppards',
			'Muli' => 'Muli',
			'Mystery Quest' => 'Mystery Quest',
			'Neucha' => 'Neucha',
			'Neuton' => 'Neuton',
			'News Cycle' => 'News Cycle',
			'Niconne' => 'Niconne',
			'Nixie One' => 'Nixie One',
			'Nobile' => 'Nobile',
			'Nokora' => 'Nokora',
			'Norican' => 'Norican',
			'Nosifer' => 'Nosifer',
			'Nothing You Could Do' => 'Nothing You Could Do',
			'Noticia Text' => 'Noticia Text',
			'Nova Cut' => 'Nova Cut',
			'Nova Flat' => 'Nova Flat',
			'Nova Mono' => 'Nova Mono',
			'Nova Oval' => 'Nova Oval',
			'Nova Round' => 'Nova Round',
			'Nova Script' => 'Nova Script',
			'Nova Slim' => 'Nova Slim',
			'Nova Square' => 'Nova Square',
			'Numans' => 'Numans',
			'Nunito' => 'Nunito',
			'Odor Mean Chey' => 'Odor Mean Chey',
			'Old Standard TT' => 'Old Standard TT',
			'Oldenburg' => 'Oldenburg',
			'Oleo Script' => 'Oleo Script',
			'Open Sans' => 'Open Sans',
			'Open Sans Condensed' => 'Open Sans Condensed',
			'Orbitron' => 'Orbitron',
			'Original Surfer' => 'Original Surfer',
			'Oswald' => 'Oswald',
			'Over the Rainbow' => 'Over the Rainbow',
			'Overlock' => 'Overlock',
			'Overlock SC' => 'Overlock SC',
			'Ovo' => 'Ovo',
			'Oxygen' => 'Oxygen',
			'PT Mono' => 'PT Mono',
			'PT Sans' => 'PT Sans',
			'PT Sans Caption' => 'PT Sans Caption',
			'PT Sans Narrow' => 'PT Sans Narrow',
			'PT Serif' => 'PT Serif',
			'PT Serif Caption' => 'PT Serif Caption',
			'Pacifico' => 'Pacifico',
			'Parisienne' => 'Parisienne',
			'Passero One' => 'Passero One',
			'Passion One' => 'Passion One',
			'Patrick Hand' => 'Patrick Hand',
			'Patua One' => 'Patua One',
			'Paytone One' => 'Paytone One',
			'Permanent Marker' => 'Permanent Marker',
			'Petrona' => 'Petrona',
			'Philosopher' => 'Philosopher',
			'Piedra' => 'Piedra',
			'Pinyon Script' => 'Pinyon Script',
			'Plaster' => 'Plaster',
			'Play' => 'Play',
			'Playball' => 'Playball',
			'Playfair Display' => 'Playfair Display',
			'Podkova' => 'Podkova',
			'Poiret One' => 'Poiret One',
			'Poller One' => 'Poller One',
			'Poly' => 'Poly',
			'Pompiere' => 'Pompiere',
			'Pontano Sans' => 'Pontano Sans',
			'Port Lligat Sans' => 'Port Lligat Sans',
			'Port Lligat Slab' => 'Port Lligat Slab',
			'Prata' => 'Prata',
			'Preahvihear' => 'Preahvihear',
			'Press Start 2P' => 'Press Start 2P',
			'Princess Sofia' => 'Princess Sofia',
			'Prociono' => 'Prociono',
			'Prosto One' => 'Prosto One',
			'Puritan' => 'Puritan',
			'Quantico' => 'Quantico',
			'Quattrocento' => 'Quattrocento',
			'Quattrocento Sans' => 'Quattrocento Sans',
			'Questrial' => 'Questrial',
			'Quicksand' => 'Quicksand',
			'Qwigley' => 'Qwigley',
			'Radley' => 'Radley',
			'Raleway' => 'Raleway',
			'Rammetto One' => 'Rammetto One',
			'Rancho' => 'Rancho',
			'Rationale' => 'Rationale',
			'Redressed' => 'Redressed',
			'Reenie Beanie' => 'Reenie Beanie',
			'Revalia' => 'Revalia',
			'Ribeye' => 'Ribeye',
			'Ribeye Marrow' => 'Ribeye Marrow',
			'Righteous' => 'Righteous',
			'Roboto' => 'Roboto',
			'Roboto Condensed' => 'Roboto Condensed',
			'Rochester' => 'Rochester',
			'Rock Salt' => 'Rock Salt',
			'Rokkitt' => 'Rokkitt',
			'Ropa Sans' => 'Ropa Sans',
			'Rosario' => 'Rosario',
			'Rosarivo' => 'Rosarivo',
			'Rouge Script' => 'Rouge Script',
			'Ruda' => 'Ruda',
			'Ruge Boogie' => 'Ruge Boogie',
			'Ruluko' => 'Ruluko',
			'Ruslan Display' => 'Ruslan Display',
			'Russo One' => 'Russo One',
			'Ruthie' => 'Ruthie',
			'Sail' => 'Sail',
			'Salsa' => 'Salsa',
			'Sanchez' => 'Sanchez',
			'Sancreek' => 'Sancreek',
			'Sansita One' => 'Sansita One',
			'Sarina' => 'Sarina',
			'Satisfy' => 'Satisfy',
			'Schoolbell' => 'Schoolbell',
			'Seaweed Script' => 'Seaweed Script',
			'Sevillana' => 'Sevillana',
			'Shadows Into Light' => 'Shadows Into Light',
			'Shadows Into Light Two' => 'Shadows Into Light Two',
			'Shanti' => 'Shanti',
			'Share' => 'Share',
			'Shojumaru' => 'Shojumaru',
			'Short Stack' => 'Short Stack',
			'Siemreap' => 'Siemreap',
			'Sigmar One' => 'Sigmar One',
			'Signika' => 'Signika',
			'Signika Negative' => 'Signika Negative',
			'Simonetta' => 'Simonetta',
			'Sirin Stencil' => 'Sirin Stencil',
			'Six Caps' => 'Six Caps',
			'Slackey' => 'Slackey',
			'Smokum' => 'Smokum',
			'Smythe' => 'Smythe',
			'Sniglet' => 'Sniglet',
			'Snippet' => 'Snippet',
			'Sofia' => 'Sofia',
			'Sonsie One' => 'Sonsie One',
			'Sorts Mill Goudy' => 'Sorts Mill Goudy',
			'Special Elite' => 'Special Elite',
			'Spicy Rice' => 'Spicy Rice',
			'Spinnaker' => 'Spinnaker',
			'Spirax' => 'Spirax',
			'Squada One' => 'Squada One',
			'Stardos Stencil' => 'Stardos Stencil',
			'Stint Ultra Condensed' => 'Stint Ultra Condensed',
			'Stint Ultra Expanded' => 'Stint Ultra Expanded',
			'Stoke' => 'Stoke',
			'Sue Ellen Francisco' => 'Sue Ellen Francisco',
			'Sunshiney' => 'Sunshiney',
			'Supermercado One' => 'Supermercado One',
			'Suwannaphum' => 'Suwannaphum',
			'Swanky and Moo Moo' => 'Swanky and Moo Moo',
			'Syncopate' => 'Syncopate',
			'Tangerine' => 'Tangerine',
			'Taprom' => 'Taprom',
			'Telex' => 'Telex',
			'Tenor Sans' => 'Tenor Sans',
			'The Girl Next Door' => 'The Girl Next Door',
			'Tienne' => 'Tienne',
			'Tinos' => 'Tinos',
			'Titan One' => 'Titan One',
			'Trade Winds' => 'Trade Winds',
			'Trocchi' => 'Trocchi',
			'Trochut' => 'Trochut',
			'Trykker' => 'Trykker',
			'Tulpen One' => 'Tulpen One',
			'Ubuntu' => 'Ubuntu',
			'Ubuntu Condensed' => 'Ubuntu Condensed',
			'Ubuntu Mono' => 'Ubuntu Mono',
			'Ultra' => 'Ultra',
			'Uncial Antiqua' => 'Uncial Antiqua',
			'UnifrakturCook' => 'UnifrakturCook',
			'UnifrakturMaguntia' => 'UnifrakturMaguntia',
			'Unkempt' => 'Unkempt',
			'Unlock' => 'Unlock',
			'Unna' => 'Unna',
			'VT323' => 'VT323',
			'Varela' => 'Varela',
			'Varela Round' => 'Varela Round',
			'Vast Shadow' => 'Vast Shadow',
			'Vibur' => 'Vibur',
			'Vidaloka' => 'Vidaloka',
			'Viga' => 'Viga',
			'Voces' => 'Voces',
			'Volkhov' => 'Volkhov',
			'Vollkorn' => 'Vollkorn',
			'Voltaire' => 'Voltaire',
			'Waiting for the Sunrise' => 'Waiting for the Sunrise',
			'Wallpoet' => 'Wallpoet',
			'Walter Turncoat' => 'Walter Turncoat',
			'Wellfleet' => 'Wellfleet',
			'Wire One' => 'Wire One',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
			'Yellowtail' => 'Yellowtail',
			'Yeseva One' => 'Yeseva One',
			'Yesteryear' => 'Yesteryear',
			'Zeyada' => 'Zeyada',
		),
		
		'countries' => array(
			'AF'=>__('Afghanistan', 'makery'),
			'AX'=>__('&#197;land Islands', 'makery'),
			'AL'=>__('Albania', 'makery'),
			'DZ'=>__('Algeria', 'makery'),
			'AD'=>__('Andorra', 'makery'),
			'AO'=>__('Angola', 'makery'),
			'AI'=>__('Anguilla', 'makery'),
			'AQ'=>__('Antarctica', 'makery'),
			'AG'=>__('Antigua and Barbuda', 'makery'),
			'AR'=>__('Argentina', 'makery'),
			'AM'=>__('Armenia', 'makery'),
			'AW'=>__('Aruba', 'makery'),
			'AU'=>__('Australia', 'makery'),
			'AT'=>__('Austria', 'makery'),
			'AZ'=>__('Azerbaijan', 'makery'),
			'BS'=>__('Bahamas', 'makery'),
			'BH'=>__('Bahrain', 'makery'),
			'BD'=>__('Bangladesh', 'makery'),
			'BB'=>__('Barbados', 'makery'),
			'BY'=>__('Belarus', 'makery'),
			'BE'=>__('Belgium', 'makery'),
			'PW'=>__('Belau', 'makery'),
			'BZ'=>__('Belize', 'makery'),
			'BJ'=>__('Benin', 'makery'),
			'BM'=>__('Bermuda', 'makery'),
			'BT'=>__('Bhutan', 'makery'),
			'BO'=>__('Bolivia', 'makery'),
			'BQ'=>__('Bonaire, Saint Eustatius and Saba', 'makery'),
			'BA'=>__('Bosnia and Herzegovina', 'makery'),
			'BW'=>__('Botswana', 'makery'),
			'BV'=>__('Bouvet Island', 'makery'),
			'BR'=>__('Brazil', 'makery'),
			'IO'=>__('British Indian Ocean Territory', 'makery'),
			'VG'=>__('British Virgin Islands', 'makery'),
			'BN'=>__('Brunei', 'makery'),
			'BG'=>__('Bulgaria', 'makery'),
			'BF'=>__('Burkina Faso', 'makery'),
			'BI'=>__('Burundi', 'makery'),
			'KH'=>__('Cambodia', 'makery'),
			'CM'=>__('Cameroon', 'makery'),
			'CA'=>__('Canada', 'makery'),
			'CV'=>__('Cape Verde', 'makery'),
			'KY'=>__('Cayman Islands', 'makery'),
			'CF'=>__('Central African Republic', 'makery'),
			'TD'=>__('Chad', 'makery'),
			'CL'=>__('Chile', 'makery'),
			'CN'=>__('China', 'makery'),
			'CX'=>__('Christmas Island', 'makery'),
			'CC'=>__('Cocos (Keeling) Islands', 'makery'),
			'CO'=>__('Colombia', 'makery'),
			'KM'=>__('Comoros', 'makery'),
			'CG'=>__('Congo (Brazzaville)', 'makery'),
			'CD'=>__('Congo (Kinshasa)', 'makery'),
			'CK'=>__('Cook Islands', 'makery'),
			'CR'=>__('Costa Rica', 'makery'),
			'HR'=>__('Croatia', 'makery'),
			'CU'=>__('Cuba', 'makery'),
			'CW'=>__('Cura&Ccedil;ao', 'makery'),
			'CY'=>__('Cyprus', 'makery'),
			'CZ'=>__('Czech Republic', 'makery'),
			'DK'=>__('Denmark', 'makery'),
			'DJ'=>__('Djibouti', 'makery'),
			'DM'=>__('Dominica', 'makery'),
			'DO'=>__('Dominican Republic', 'makery'),
			'EC'=>__('Ecuador', 'makery'),
			'EG'=>__('Egypt', 'makery'),
			'SV'=>__('El Salvador', 'makery'),
			'GQ'=>__('Equatorial Guinea', 'makery'),
			'ER'=>__('Eritrea', 'makery'),
			'EE'=>__('Estonia', 'makery'),
			'ET'=>__('Ethiopia', 'makery'),
			'FK'=>__('Falkland Islands', 'makery'),
			'FO'=>__('Faroe Islands', 'makery'),
			'FJ'=>__('Fiji', 'makery'),
			'FI'=>__('Finland', 'makery'),
			'FR'=>__('France', 'makery'),
			'GF'=>__('French Guiana', 'makery'),
			'PF'=>__('French Polynesia', 'makery'),
			'TF'=>__('French Southern Territories', 'makery'),
			'GA'=>__('Gabon', 'makery'),
			'GM'=>__('Gambia', 'makery'),
			'GE'=>__('Georgia', 'makery'),
			'DE'=>__('Germany', 'makery'),
			'GH'=>__('Ghana', 'makery'),
			'GI'=>__('Gibraltar', 'makery'),
			'GR'=>__('Greece', 'makery'),
			'GL'=>__('Greenland', 'makery'),
			'GD'=>__('Grenada', 'makery'),
			'GP'=>__('Guadeloupe', 'makery'),
			'GT'=>__('Guatemala', 'makery'),
			'GG'=>__('Guernsey', 'makery'),
			'GN'=>__('Guinea', 'makery'),
			'GW'=>__('Guinea-Bissau', 'makery'),
			'GY'=>__('Guyana', 'makery'),
			'HT'=>__('Haiti', 'makery'),
			'HM'=>__('Heard Island and McDonald Islands', 'makery'),
			'HN'=>__('Honduras', 'makery'),
			'HK'=>__('Hong Kong', 'makery'),
			'HU'=>__('Hungary', 'makery'),
			'IS'=>__('Iceland', 'makery'),
			'IN'=>__('India', 'makery'),
			'ID'=>__('Indonesia', 'makery'),
			'IR'=>__('Iran', 'makery'),
			'IQ'=>__('Iraq', 'makery'),
			'IE'=>__('Republic of Ireland', 'makery'),
			'IM'=>__('Isle of Man', 'makery'),
			'IL'=>__('Israel', 'makery'),
			'IT'=>__('Italy', 'makery'),
			'CI'=>__('Ivory Coast', 'makery'),
			'JM'=>__('Jamaica', 'makery'),
			'JP'=>__('Japan', 'makery'),
			'JE'=>__('Jersey', 'makery'),
			'JO'=>__('Jordan', 'makery'),
			'KZ'=>__('Kazakhstan', 'makery'),
			'KE'=>__('Kenya', 'makery'),
			'KI'=>__('Kiribati', 'makery'),
			'KW'=>__('Kuwait', 'makery'),
			'KG'=>__('Kyrgyzstan', 'makery'),
			'LA'=>__('Laos', 'makery'),
			'LV'=>__('Latvia', 'makery'),
			'LB'=>__('Lebanon', 'makery'),
			'LS'=>__('Lesotho', 'makery'),
			'LR'=>__('Liberia', 'makery'),
			'LY'=>__('Libya', 'makery'),
			'LI'=>__('Liechtenstein', 'makery'),
			'LT'=>__('Lithuania', 'makery'),
			'LU'=>__('Luxembourg', 'makery'),
			'MO'=>__('Macao S.A.R., China', 'makery'),
			'MK'=>__('Macedonia', 'makery'),
			'MG'=>__('Madagascar', 'makery'),
			'MW'=>__('Malawi', 'makery'),
			'MY'=>__('Malaysia', 'makery'),
			'MV'=>__('Maldives', 'makery'),
			'ML'=>__('Mali', 'makery'),
			'MT'=>__('Malta', 'makery'),
			'MH'=>__('Marshall Islands', 'makery'),
			'MQ'=>__('Martinique', 'makery'),
			'MR'=>__('Mauritania', 'makery'),
			'MU'=>__('Mauritius', 'makery'),
			'YT'=>__('Mayotte', 'makery'),
			'MX'=>__('Mexico', 'makery'),
			'FM'=>__('Micronesia', 'makery'),
			'MD'=>__('Moldova', 'makery'),
			'MC'=>__('Monaco', 'makery'),
			'MN'=>__('Mongolia', 'makery'),
			'ME'=>__('Montenegro', 'makery'),
			'MS'=>__('Montserrat', 'makery'),
			'MA'=>__('Morocco', 'makery'),
			'MZ'=>__('Mozambique', 'makery'),
			'MM'=>__('Myanmar', 'makery'),
			'NA'=>__('Namibia', 'makery'),
			'NR'=>__('Nauru', 'makery'),
			'NP'=>__('Nepal', 'makery'),
			'NL'=>__('Netherlands', 'makery'),
			'AN'=>__('Netherlands Antilles', 'makery'),
			'NC'=>__('New Caledonia', 'makery'),
			'NZ'=>__('New Zealand', 'makery'),
			'NI'=>__('Nicaragua', 'makery'),
			'NE'=>__('Niger', 'makery'),
			'NG'=>__('Nigeria', 'makery'),
			'NU'=>__('Niue', 'makery'),
			'NF'=>__('Norfolk Island', 'makery'),
			'KP'=>__('North Korea', 'makery'),
			'NO'=>__('Norway', 'makery'),
			'OM'=>__('Oman', 'makery'),
			'PK'=>__('Pakistan', 'makery'),
			'PS'=>__('Palestinian Territory', 'makery'),
			'PA'=>__('Panama', 'makery'),
			'PG'=>__('Papua New Guinea', 'makery'),
			'PY'=>__('Paraguay', 'makery'),
			'PE'=>__('Peru', 'makery'),
			'PH'=>__('Philippines', 'makery'),
			'PN'=>__('Pitcairn', 'makery'),
			'PL'=>__('Poland', 'makery'),
			'PT'=>__('Portugal', 'makery'),
			'QA'=>__('Qatar', 'makery'),
			'RE'=>__('Reunion', 'makery'),
			'RO'=>__('Romania', 'makery'),
			'RU'=>__('Russia', 'makery'),
			'RW'=>__('Rwanda', 'makery'),
			'BL'=>__('Saint Barth&eacute;lemy', 'makery'),
			'SH'=>__('Saint Helena', 'makery'),
			'KN'=>__('Saint Kitts and Nevis', 'makery'),
			'LC'=>__('Saint Lucia', 'makery'),
			'MF'=>__('Saint Martin (French part)', 'makery'),
			'SX'=>__('Saint Martin (Dutch part)', 'makery'),
			'PM'=>__('Saint Pierre and Miquelon', 'makery'),
			'VC'=>__('Saint Vincent and the Grenadines', 'makery'),
			'SM'=>__('San Marino', 'makery'),
			'ST'=>__('S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'makery'),
			'SA'=>__('Saudi Arabia', 'makery'),
			'SN'=>__('Senegal', 'makery'),
			'RS'=>__('Serbia', 'makery'),
			'SC'=>__('Seychelles', 'makery'),
			'SL'=>__('Sierra Leone', 'makery'),
			'SG'=>__('Singapore', 'makery'),
			'SK'=>__('Slovakia', 'makery'),
			'SI'=>__('Slovenia', 'makery'),
			'SB'=>__('Solomon Islands', 'makery'),
			'SO'=>__('Somalia', 'makery'),
			'ZA'=>__('South Africa', 'makery'),
			'GS'=>__('South Georgia/Sandwich Islands', 'makery'),
			'KR'=>__('South Korea', 'makery'),
			'SS'=>__('South Sudan', 'makery'),
			'ES'=>__('Spain', 'makery'),
			'LK'=>__('Sri Lanka', 'makery'),
			'SD'=>__('Sudan', 'makery'),
			'SR'=>__('Suriname', 'makery'),
			'SJ'=>__('Svalbard and Jan Mayen', 'makery'),
			'SZ'=>__('Swaziland', 'makery'),
			'SE'=>__('Sweden', 'makery'),
			'CH'=>__('Switzerland', 'makery'),
			'SY'=>__('Syria', 'makery'),
			'TW'=>__('Taiwan', 'makery'),
			'TJ'=>__('Tajikistan', 'makery'),
			'TZ'=>__('Tanzania', 'makery'),
			'TH'=>__('Thailand', 'makery'),
			'TL'=>__('Timor-Leste', 'makery'),
			'TG'=>__('Togo', 'makery'),
			'TK'=>__('Tokelau', 'makery'),
			'TO'=>__('Tonga', 'makery'),
			'TT'=>__('Trinidad and Tobago', 'makery'),
			'TN'=>__('Tunisia', 'makery'),
			'TR'=>__('Turkey', 'makery'),
			'TM'=>__('Turkmenistan', 'makery'),
			'TC'=>__('Turks and Caicos Islands', 'makery'),
			'TV'=>__('Tuvalu', 'makery'),
			'UG'=>__('Uganda', 'makery'),
			'UA'=>__('Ukraine', 'makery'),
			'AE'=>__('United Arab Emirates', 'makery'),
			'GB'=>__('United Kingdom (UK)', 'makery'),
			'US'=>__('United States (US)', 'makery'),
			'UY'=>__('Uruguay', 'makery'),
			'UZ'=>__('Uzbekistan', 'makery'),
			'VU'=>__('Vanuatu', 'makery'),
			'VA'=>__('Vatican', 'makery'),
			'VE'=>__('Venezuela', 'makery'),
			'VN'=>__('Vietnam', 'makery'),
			'WF'=>__('Wallis and Futuna', 'makery'),
			'EH'=>__('Western Sahara', 'makery'),
			'WS'=>__('Western Samoa', 'makery'),
			'YE'=>__('Yemen', 'makery'),
			'ZM'=>__('Zambia', 'makery'),
			'ZW'=>__('Zimbabwe', 'makery')
		),
	),
	
	//Theme Options
	'options' => array(
	
		//General
		array(	
			'name' => __('General', 'makery'),
			'type' => 'section'
		),

			array(	
				'name' => __('Site Favicon', 'makery'),
				'description' => __('Choose an image to replace the default site favicon', 'makery'),
				'id' => 'favicon',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Site Logo', 'makery'),
				'description' => __('Choose an image to replace the default theme logo', 'makery'),
				'id' => 'site_logo',
				'type' => 'uploader',
			),
			
			array(	
				'name' => __('Login Logo', 'makery'),
				'description' => __('Choose an image to replace the default WordPress login logo', 'makery'),
				'id' => 'login_logo',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Copyright Text', 'makery'),
				'description' => __('Enter copyright text to show in the theme footer', 'makery'),
				'id' => 'copyright',
				'type' => 'textarea',
			),

			array(	
				'name' => __('Tracking Code', 'makery'),
				'description' => __('Enter Google Analytics code to track your site visitors', 'makery'),
				'id' => 'tracking',
				'type' => 'textarea',
			),

		//Styling
		array(
			'name' => __('Styling', 'makery'),
			'type' => 'section',
		),	

			array(	
				'name' => __('Primary Color', 'makery'),
				'default' => '#ea6254',
				'id' => 'primary_color',
				'type' => 'color',
			),

			array(	
				'name' => __('Secondary Color', 'makery'),
				'default' => '#4e8ed6',
				'id' => 'secondary_color',
				'type' => 'color',
			),
			
			array(	
				'name' => __('Background Image', 'makery'),
				'id' => 'background_image',
				'description' => __('Choose background image from WordPress media library', 'makery'),
				'type' => 'uploader',
			),
			
			array(	
				'name' => __('Background Type', 'makery'),
				'id' => 'background_type',
				'type' => 'select',
				'options' => array(
					'auto' => __('Tiled', 'makery'),
					'cover' => __('Full Width', 'makery'),					
				),
			),
			
			array(	
				'name' => __('Heading Font' ,'makery'),					
				'id' => 'heading_font',
				'default' => 'Asap',
				'type' => 'select_font',
			),

			array(	
				'name' => __('Content Font', 'makery'),
				'id' => 'content_font',
				'default' => 'Open Sans',
				'type' => 'select_font',
			),

			array(	
				'name' => __('Custom CSS', 'makery'),
				'description' => __('Enter custom CSS code to overwrite the default theme styles', 'makery'),
				'id' => 'css',
				'type' => 'textarea',
			),
			
		//Slider
		array(
			'name' => __('Slider', 'makery'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Slider Pause', 'makery'),
				'default' => '0',
				'id' => 'slider_pause',
				'min_value' => 0,
				'max_value' => 15000,
				'unit'=>'ms',
				'type' => 'slider',
			),
			
			array(	
				'name' => __('Slider Speed', 'makery'),
				'default' => '1000',
				'id' => 'slider_speed',
				'min_value' => 0,
				'max_value' => 1000,
				'unit'=>'ms',
				'type' => 'slider',
			),
			
		//Registration
		array(
			'name' => __('Registration', 'makery'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Enable Email Confirmation', 'makery'),
				'id' => 'user_activation',
				'type' => 'checkbox',
			),
		
			array(
				'name' => __('Enable Captcha Protection', 'makery'),
				'id' => 'user_captcha',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Enable Facebook Login', 'makery'),
				'id' => 'user_facebook',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Facebook Application ID', 'makery'),
				'id' => 'user_facebook_id',
				'type' => 'text',
				'parent' => array(
					'id' => 'user_facebook',
					'value' => 'true',
				),
			),
			
			array(	
				'name' => __('Facebook Application Secret', 'makery'),
				'id' => 'user_facebook_secret',
				'type' => 'text',
				'parent' => array(
					'id' => 'user_facebook',
					'value' => 'true',
				),
			),
			
			array(	
				'name' => __('Registration Email', 'makery'),
				'id' => 'email_registration',
				'description' => __('Add registration email text, you can use %username%, %password% and %link% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Password Reset Email', 'makery'),
				'id' => 'email_password',
				'description' => __('Add password reset email text, you can use %username% and %link% keywords', 'makery'),
				'type' => 'textarea',
			),
			
		//Shops
		array(
			'name' => __('Shops', 'makery'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Shops Layout', 'makery'),
				'id' => 'shops_layout',
				'type' => 'select_image',
				'options' => array(
					'full' => THEMEX_URI.'assets/images/layouts/layout-full.png',
					'left' => THEMEX_URI.'assets/images/layouts/layout-left.png',
					'right' => THEMEX_URI.'assets/images/layouts/layout-right.png',				
				),
			),
			
			array(	
				'name' => __('Shops Order', 'makery'),
				'id' => 'shops_order',
				'type' => 'select',
				'options' => array(
					'date' => __('Date', 'makery'),
					'rating' => __('Rating', 'makery'),
					'sales' => __('Sales', 'makery'),	
					'admirers' => __('Popularity', 'makery'),
				),
			),
			
			array(	
				'name' => __('Shops Per Page', 'makery'),
				'id' => 'shops_per_page',
				'type' => 'number',
				'default' => '6',
			),
			
			array(
				'name' => __('Disable Multiple Shops', 'makery'),
				'id' => 'shop_multiple',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Disable Shop Referrals', 'makery'),
				'id' => 'shop_referrals',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Disable Approving Shops', 'makery'),
				'id' => 'shop_approve',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Disable Custom Shipping', 'makery'),
				'id' => 'shop_shipping',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Enable Multiple Categories', 'makery'),
				'id' => 'shop_category',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Empty Shops', 'makery'),
				'id' => 'shops_empty',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Rating', 'makery'),
				'id' => 'shop_rating',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Sales', 'makery'),
				'id' => 'shop_sales',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Favorites', 'makery'),
				'id' => 'shop_favorites',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Questions', 'makery'),
				'id' => 'shop_questions',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Reports', 'makery'),
				'id' => 'shop_reports',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Shop Policies', 'makery'),
				'id' => 'shop_policy',
				'type' => 'textarea',
			),
		
		//Products
		array(
			'name' => __('Products', 'makery'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Products Layout', 'makery'),
				'id' => 'products_layout',
				'type' => 'select_image',
				'options' => array(
					'full' => THEMEX_URI.'assets/images/layouts/layout-full.png',
					'left' => THEMEX_URI.'assets/images/layouts/layout-left.png',
					'right' => THEMEX_URI.'assets/images/layouts/layout-right.png',				
				),
			),
			
			array(	
				'name' => __('Products View', 'makery'),
				'id' => 'products_view',
				'type' => 'select',
				'options' => array(
					'grid' => __('Grid', 'makery'),
					'list' => __('List', 'makery'),
				),
			),
			
			array(	
				'name' => __('Products Per Page', 'makery'),
				'id' => 'products_per_page',
				'type' => 'number',
				'default' => '9',
			),
			
			array(	
				'name' => __('Related Products Number', 'makery'),
				'id' => 'product_related_number',
				'type' => 'number',
				'default' => '4',
			),
			
			array(	
				'name' => __('Upsell Products Number', 'makery'),
				'id' => 'product_upsell_number',
				'type' => 'number',
				'default' => '4',
			),
			
			array(
				'name' => __('Product Types', 'makery'),
				'id' => 'product_type',
				'type' => 'select',
				'options' => array(
					'all' => __('All Types', 'makery'),
					'virtual' => __('Virtual', 'makery'),
					'physical' => __('Physical', 'makery'),
				),
			),
			
			array(	
				'name' => __('File Extensions', 'makery'),
				'id' => 'product_extensions',
				'description' => __('Enter comma separated file extensions for products', 'makery'),
				'type' => 'text',
				'default' => 'zip',
			),
			
			array(
				'name' => __('Disable Approving Products', 'makery'),
				'id' => 'product_approve',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Enable Multiple Categories', 'makery'),
				'id' => 'product_category',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Tags', 'makery'),
				'id' => 'product_tags',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Price', 'makery'),
				'id' => 'product_price',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Attributes', 'makery'),
				'id' => 'product_attributes',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Variations', 'makery'),
				'id' => 'product_variations',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Favorites', 'makery'),
				'id' => 'product_favorites',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Questions', 'makery'),
				'id' => 'product_questions',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Order Note', 'makery'),
				'id' => 'order_note',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Order Address', 'makery'),
				'id' => 'order_address',
				'type' => 'checkbox',
			),
		
		//Posts
		array(
			'name' => __('Posts', 'makery'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Posts Layout', 'makery'),
				'id' => 'posts_layout',
				'type' => 'select_image',
				'options' => array(
					'left' => THEMEX_URI.'assets/images/layouts/layout-left.png',
					'right' => THEMEX_URI.'assets/images/layouts/layout-right.png',				
				),
			),
			
			array(
				'name' => __('Hide Post Author', 'makery'),
				'id' => 'post_author',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Post Date', 'makery'),
				'id' => 'post_date',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Post Image', 'makery'),
				'id' => 'post_image',
				'type' => 'checkbox',
			),
			
		//Profiles
		array(
			'name' => __('Profiles', 'makery'),
			'type' => 'section',
		),
		
			array(
				'name' => __('Hide Name', 'makery'),
				'id' => 'profile_name',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Location', 'makery'),
				'id' => 'profile_location',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Date', 'makery'),
				'id' => 'profile_date',
				'type' => 'checkbox',
			),			
			
			array(
				'name' => __('Hide Links', 'makery'),
				'id' => 'profile_links',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Address', 'makery'),
				'id' => 'profile_address',
				'type' => 'checkbox',
			),
			
			array(
				'id' => 'ThemexForm',
				'slug' => 'profile',
				'type' => 'module',
			),
			
		//Payments
		array(
			'name' => __('Payments', 'makery'),
			'type' => 'section',
		),
		
			array(
				'name' => __('Referral Rate', 'makery'),
				'id' => 'shop_rate_referral',
				'description' => __('Enter affiliate commission rate earned for referred orders', 'makery'),
				'type' => 'number',
				'default' => '30',
			),
		
			array(	
				'name' => __('Minimum Rate', 'makery'),
				'id' => 'shop_rate_min',
				'description' => __('Enter minimum shop commission rate per order', 'makery'),
				'type' => 'number',
				'default' => '50',
			),
			
			array(	
				'name' => __('Maximum Rate', 'makery'),
				'id' => 'shop_rate_max',
				'description' => __('Enter maximum shop commission rate per order', 'makery'),
				'type' => 'number',
				'default' => '70',
			),
			
			array(	
				'name' => __('Maximum Revenue', 'makery'),
				'id' => 'shop_rate_amount',
				'description' => __('Enter revenue amount for the maximum commission rate', 'makery'),
				'type' => 'number',
				'default' => '1000',
			),
			
			array(	
				'name' => __('Minimum Withdrawal', 'makery'),
				'id' => 'withdrawal_min',
				'description' => __('Enter minimum withdrawal amount per request', 'makery'),
				'type' => 'number',
				'default' => '50',
			),
			
			array(	
				'name' => __('Withdrawal Methods', 'makery'),
				'id' => 'withdrawal_methods',
				'description' => __('Select available withdrawal methods, hold the CTRL or CMD key to select multiple items', 'makery'),
				'type' => 'select',
				'options' => array(
					'paypal' => __('PayPal', 'makery'),
					'skrill' => __('Skrill', 'makery'),
					'swift' => __('SWIFT', 'makery'),
				),
				'attributes' => array(
					'multiple' => 'multiple',
				),
				'default' => array(
					'paypal',
					'skrill',
					'swift',
				),
			),
			
		//Membership
		array(
			'name' => __('Membership', 'makery'),
			'type' => 'section',
		),
		
			array(
				'name' => __('Disable Free Membership', 'makery'),
				'id' => 'membership_free',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Free Membership Duration','makery'),
				'id' => 'membership_period',
				'type' => 'number',
				'default' => '31',
			),
			
			array(	
				'name' => __('Free Products Number','makery'),
				'id' => 'membership_products',
				'type' => 'number',
				'default' => '10',
			),
			
		//Notifications
		array(
			'name' => __('Notifications', 'makery'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('New Message Email', 'makery'),
				'id' => 'email_message',
				'description' => __('Add new message email text, you can use %user% and %message% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(
				'name' => __('New Order Email', 'makery'),
				'id' => 'email_order_received',
				'description' => __('Add new order email text, you can use %username% and %order% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(
				'name' => __('New Referral Email', 'makery'),
				'id' => 'email_order_referral',
				'description' => __('Add new referral email text, you can use %username% and %order% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(
				'name' => __('Shop Question Email', 'makery'),
				'id' => 'email_shop_question',
				'description' => __('Add shop question email text, you can use %user%, %shop% and %question% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Shop Report Email', 'makery'),
				'id' => 'email_shop_report',
				'description' => __('Add shop report email text, you can use %user%, %shop% and %reason% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Shop Approval Email', 'makery'),
				'id' => 'email_shop_approved',
				'description' => __('Add shop approval email text, you can use %username% and %shop% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Product Question Email', 'makery'),
				'id' => 'email_product_question',
				'description' => __('Add product question email text, you can use %user%, %product% and %question% keywords', 'makery'),
				'type' => 'textarea',
			),

			array(
				'name' => __('Product Approval Email', 'makery'),
				'id' => 'email_product_approved',
				'description' => __('Add product approval email text, you can use %username% and %product% keywords', 'makery'),
				'type' => 'textarea',
			),
			
			array(
				'name' => __('Processed Withdrawal Email', 'makery'),
				'id' => 'email_withdrawal_processed',
				'description' => __('Add processed withdrawal email text, you can use %username%, %method% and %amount% keywords', 'makery'),
				'type' => 'textarea',
			),
	),
);