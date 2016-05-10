<?php
/**
 * Themex Shop
 *
 * Handles shops data
 *
 * @class ThemexShop
 * @author Themex
 */
 
class ThemexShop {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
		
		//update actions
		add_action('wp', array(__CLASS__, 'update'), 3);
		add_action('wp_ajax_themex_update_shop', array(__CLASS__, 'update'));
		add_action('pending_to_publish', array(__CLASS__, 'updateShop'));
		add_action('before_delete_post', array(__CLASS__, 'deleteShop'));
		add_action('pre_get_posts', array(__CLASS__, 'filterShops'));
		
		//update withdrawal
		add_action('save_post', array(__CLASS__, 'updateWithdrawal'));
		add_action('delete_post', array(__CLASS__, 'updateWithdrawal'));
		
		//update ratings
		add_action('comment_post', array(__CLASS__, 'updateRating'));
		add_action('wp_set_comment_status', array(__CLASS__, 'updateRating'));
		add_action('delete_comment', array(__CLASS__, 'updateRating'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public	 
     * @return void
     */
	public static function refresh($ID=0, $extended=false) {
		self::$data=self::getShop($ID, $extended);
	}
	
	/**
	 * Updates module data
     *
     * @access public	 
     * @return void
     */
	public static function update() {
		$data=$_POST;
		if(isset($_POST['data'])) {
			parse_str($_POST['data'], $data);
		}
		
		if(isset($data['shop_action']) && is_user_logged_in()) {
			$action=sanitize_title($data['shop_action']);
			$redirect=false;
			
			$ID=intval(themex_value('shop_id', $data));
			$author=intval(get_post_field('post_author', $ID));
			$user=get_current_user_id();			
			
			if((empty($ID) || $user==$author) && !ThemexCore::checkOption('shop_multiple')) {
				switch($action) {
					case 'update_image':
						self::updateImage($ID, themex_array('shop_image', $_FILES));
						$redirect=true;
					break;
					
					case 'update_profile':
						self::updateProfile($ID, $data);
					break;
					
					case 'update_shipping':
						self::updateShipping($ID, $data);
					break;
					
					case 'remove_shop':						
						self::removeShop($ID);
					break;
				}
			}
			
			switch($action) {
				case 'add_withdrawal':
					self::addWithdrawal($data);
				break;
				
				case 'remove_withdrawal':
					self::removeWithdrawal(themex_value('withdrawal_id', $data));
				break;
				
				case 'submit_question':
					self::submitQuestion($data);
				break;
				
				case 'submit_report':
					self::submitReport($data);
				break;
			}
			
			if($redirect || empty(ThemexInterface::$messages)) {
				wp_redirect(themex_url());
				exit();
			}
		}
	}
	
	/**
	 * Gets shop
     *
     * @access public
	 * @param int $ID
	 * @param bool $extended
     * @return array
     */
	public static function getShop($ID, $extended=false) {
		$shop=array();
		if(empty($ID)) {
			$shop=array(
				'ID' => '',
				'status' => 'draft',
				'author' => '',
				'profile' => array(
					'title' => '',
					'category' => '',
					'content' => '',
					'about' => '',
					'policy' => '',
				),
				'sales' => '',
				'admirers' => '',
				'rating' => '',
				'ratings' => '',
				'rate' => '',
				'revenue' => '',
				'products' => array(),
				'orders' => array(),	
				'handlers' => array(),
				'reviews' => array(),
				'withdrawals' => array(),
			);
		} else {
			$shop['ID']=$ID;
			$shop['status']=get_post_status($ID);
			
			$shop['author']=self::getAuthor($ID);
			$shop['profile']=self::getProfile($ID);			
			
			$shop['sales']=absint(ThemexCore::getPostMeta($ID, 'sales'));
			$shop['admirers']=absint(ThemexCore::getPostMeta($ID, 'admirers'));			
			
			$shop['rating']=absint(ThemexCore::getPostMeta($ID, 'rating'));
			$shop['ratings']=absint(ThemexCore::getPostMeta($ID, 'ratings'));
			
			if($extended) {
				$shop['orders']=ThemexWoo::getOrders($shop['author']);
				$shop['withdrawals']=self::getWithdrawals($shop['author']);
				
				$shop['products']=ThemexWoo::getProducts($shop['author'], array(
					'post_status' => array('publish', 'draft'),
				));
				
				$shop['reviews']=ThemexWoo::getProducts($shop['author'], array(
					'post_status' => 'pending',
				));
			
				$shop['handlers']=count(ThemexWoo::getOrders($shop['author'], array(
					'post_status' => 'wc-processing',
				)));
				
				$shop['rate']=self::getRate($shop['author']);
				$shop['revenue']=round(floatval(ThemexCore::getUserMeta($shop['author'], 'revenue')), 2);
			} else {
				$shop['products']=ThemexWoo::getProducts($shop['author']);
			}
		}
		
		return $shop;
	}
	
	/**
	 * Adds shop
     *
     * @access public
     * @return bool
     */
	public static function addShop() {
		$user=get_current_user_id();
		$args=array(
			'post_type' => 'shop',
			'post_status' => 'draft',
			'post_author' => $user,
			'post_title' => __('Untitled', 'makery'),
		);
		
		$shop=wp_insert_post($args);
		if(!empty($shop)) {
			add_post_meta($shop, '_'.THEMEX_PREFIX.'hidden', '1', true);
			add_post_meta($shop, '_'.THEMEX_PREFIX.'rating', '0', true);
			add_post_meta($shop, '_'.THEMEX_PREFIX.'sales', '0', true);
			add_post_meta($shop, '_'.THEMEX_PREFIX.'admirers', '0', true);
			
			ThemexUser::$data['current']['shop']=$shop;
			return $shop;
		}
		
		return false;
	}
	
	/**
	 * Updates shop status
     *
     * @access public
	 * @param mixed $post
     * @return void
     */
	public static function updateShop($post) {
		if(isset($post) && in_array($post->post_type, array('shop', 'product', 'withdrawal'))) {
			$content=ThemexCore::getOption('email_product_approved');
			if($post->post_type=='shop') {
				$content=ThemexCore::getOption('email_shop_approved');
			} else if($post->post_type=='withdrawal') {
				$content=ThemexCore::getOption('email_withdrawal_processed');
			}
			
			if(!empty($content)) {
				$user=get_userdata($post->post_author);
				$subject=__('Item Approval', 'makery');
				if($post->post_type=='shop') {
					$subject=__('Shop Approval', 'makery');
				} else if($post->post_type=='withdrawal') {
					$subject=__('Processed Withdrawal', 'makery');
				}
				
				$link='<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
				$keywords=array(
					'username' => $user->user_login,
					'shop' => $link,
					'product' => $link,
				);
				
				if($post->post_type=='withdrawal') {
					$method=themex_value(ThemexCore::getPostMeta($post->ID, 'method'), ThemexCore::$components['forms']['withdrawal']['method']['options']);
					$amount=ThemexWoo::getPrice(ThemexCore::getPostMeta($post->ID, 'amount'));
					
					$keywords=array_merge(array(
						'method' => $method,
						'amount' => $amount,
					), $keywords);
				}
				
				$content=themex_keywords($content, $keywords);
				themex_mail($user->user_email, $subject, $content);
			}
		}
	}
	
	/**
	 * Removes shop
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function removeShop($ID) {
		$user=get_current_user_id();
		wp_update_post(array(
			'ID' => $ID,
			'post_status' => 'trash',
		));
		
		$products=ThemexWoo::getProducts($user, array(
			'post_status' => array('publish', 'pending', 'draft'),
		));
		
		foreach($products as $product) {
			wp_update_post(array(
				'ID' => $product,
				'post_status' => 'trash',
			));
		}
		
		wp_redirect(get_author_posts_url($user));
		exit();
	}
	
	/**
	 * Deletes shop
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function deleteShop($ID) {
		global $post_type;
		
		if(!empty($ID) && in_array($post_type, array('shop', 'product'))) {
			$attachments=get_posts(array(
				'post_type' => 'attachment',
				'post_parent' => $ID,
				'posts_per_page' => -1,
				'fields' => 'ids',
			));
			
			foreach($attachments as $attachment) {
				wp_delete_attachment($attachment);
			}
		}
	}
	
	/**
	 * Renders shop options
     *
     * @access public
	 * @param int $ID
     * @return string
     */
	public static function renderShop($ID) {
		$out='';
		
		$shop=self::getShop($ID, true);
		$profit=round(floatval(ThemexCore::getUserMeta($shop['author'], 'profit')), 2);		
		$balance=round(floatval(ThemexCore::getUserMeta($shop['author'], 'balance')), 2);
		
		$nickname=get_user_meta($shop['author'], 'nickname', true);
		$fields['author']=array(
			'label' => __('Author', 'makery'),
			'value' => '<a href="'.get_edit_user_link($shop['author']).'">'.$nickname.'</a>',
		);
		
		if(ThemexWoo::isActive()) {
			$products=count($shop['products']);
			$fields['products']=array(
				'label' => __('Products', 'makery'),
				'value' => '<a href="'.admin_url('edit.php?post_type=product&author='.$shop['author']).'">'.$products.'</a>',
			);
			
			$orders=count($shop['orders']);
			$fields['orders']=array(
				'label' => __('Orders', 'makery'),
				'value' => '<a href="'.admin_url('edit.php?post_type=shop_order&author='.$shop['author']).'">'.$orders.'</a>',
			);
		}
		
		$fields['revenue']=array(
			'label' => __('Total Revenue', 'makery'),
			'value' => ThemexWoo::getPrice($shop['revenue']),
		);
		
		$fields['profit']=array(
			'label' => __('Total Profit', 'makery'),
			'value' => ThemexWoo::getPrice($profit),
		);
		
		$fields['balance']=array(
			'label' => __('Current Balance', 'makery'),
			'value' => ThemexWoo::getPrice($balance),
		);
		
		$fields['rate']=array(
			'label' => __('Current Rate', 'makery'),
			'value' => $shop['rate'].'%',
		);
		
		foreach($fields as $field) {
			$out.='<tr><th><h4 class="themex-meta-title">'.$field['label'].'</h4></th><td class="themex-meta-value">'.$field['value'].'</td></tr>';
		}
		
		return $out;
	}
	
	/**
	 * Queries shops
     *
     * @access public	 
     * @return void
     */
	public static function queryShops() {
		global $wp_query;
		
		$args=array(
			'post_type' =>'shop',
			'paged' => themex_paged(),
			'posts_per_page' => ThemexCore::getOption('shops_per_page', 6),
		);
		
		if(ThemexCore::checkOption('shops_empty')) {
			$args['meta_query']=array(
				array(
					'key' => '_'.THEMEX_PREFIX.'hidden',
					'compare' => '!=',
					'value' => '1',
				),
			);
		}
		
		$order=ThemexCore::getOption('shops_order', 'date');
		if($order!='date') {
			$args['orderby']='meta_value_num';
			$args['meta_key']='_'.THEMEX_PREFIX.$order;
		}
		
		query_posts($args);
	}
	
	/**
	 * Filters shops query
     *
     * @access public
	 * @param mixed $query
     * @return mixed
     */
	public static function filterShops($query) {
		if(!is_admin() && $query->is_main_query()) {
			if(is_post_type_archive('shop') || $query->is_tax('shop_category') || themex_search('shop')) {
				$number=intval(ThemexCore::getOption('shops_per_page', 6));
				$query->set('posts_per_page', $number);
				
				if(ThemexCore::checkOption('shops_empty')) {
					$meta=$query->get('meta_query');
					
					$meta[]=array(
						'key' => '_'.THEMEX_PREFIX.'hidden',
						'compare' => '!=',
						'value' => '1',
					);
					
					$query->set('meta_query', $meta);
				}
				
				$order=ThemexCore::getOption('shops_order', 'date');
				if($order!='date') {
					$query->set('orderby', 'meta_value_num');
					$query->set('meta_key', '_'.THEMEX_PREFIX.$order);
				}
			}
			
			if(themex_search('shop')) {
				$meta=$query->get('meta_query');
				
				//category
				$category=intval(themex_array('category', $_GET));
				if(!empty($category)) {
					$query->set('tax_query', array(
						array(
							'taxonomy' => 'shop_category',
							'terms' => $category,
						),
					));
				}
				
				//country
				$country=sanitize_text_field(themex_array('country', $_GET));
				if(!empty($country)) {
					$meta[]=array(
						'key' => '_'.THEMEX_PREFIX.'country',
						'value' => $country,
					);
				}
				
				//city
				$city=sanitize_text_field(themex_array('city', $_GET));
				if(!empty($city)) {
					$meta[]=array(
						'key' => '_'.THEMEX_PREFIX.'city',
						'value' => $city,
					);
				}
				
				$query->set('meta_query', $meta);
			} else if(themex_search('post')) {
				$query->set('post_type', 'post');
			}
		}
		
		return $query;
	}
	
	/**
	 * Gets shop author
     *
     * @access public
	 * @param int $ID
     * @return int
     */
	public static function getAuthor($ID) {
		global $wpdb;
		
		$author=intval($wpdb->get_var($wpdb->prepare("
			SELECT post_author FROM {$wpdb->posts} 
			WHERE ID = %d 
		", intval($ID))));
		
		return $author;
	}
	
	/**
	 * Gets shop rate
     *
     * @access public
	 * @param int $user
     * @return int
     */
	public static function getRate($user) {
		$rate_min=absint(ThemexCore::getOption('shop_rate_min', 50));
		$rate_max=absint(ThemexCore::getOption('shop_rate_max', 70));
		
		$rate=$rate_min;
		if($rate_max>$rate_min) {
			$rate=absint(ThemexCore::getUserMeta($user, 'rate', $rate_min));
		}
		
		return $rate;
	}
	
	/**
	 * Filters shop rate
     *
     * @access public
	 * @param int $ID
	 * @param int $rate
     * @return int
     */
	public static function filterRate($ID, $rate) {
		$default=absint(ThemexCore::getPostMeta($ID, 'rate'));		
		if(!empty($default)) {
			$rate=$default;
		}
		
		return $rate;
	}
	
	/**
	 * Updates shop image
     *
     * @access public
	 * @param int $ID
	 * @param mixed $file
     * @return void
     */
	public static function updateImage($ID, $file) {
		if(empty($ID)) {
			$ID=self::addShop();
		}
		
		ThemexCore::updateImage($ID, $file);
		wp_redirect(ThemexCore::getURL('shop-settings'));
		exit();
	}
	
	/**
	 * Gets shop profile
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getProfile($ID) {
		$profile=array();
		
		$profile['title']=get_the_title($ID);
		if($profile['title']==__('Untitled', 'makery')) {
			$profile['title']='';
		}
		
		$profile['category']=0;
		$categories=get_the_terms($ID, 'shop_category');
		if(is_array($categories) && !empty($categories)) {
			if(ThemexCore::checkOption('shop_category')) {
				$profile['category']=wp_list_pluck($categories, 'term_id');
			} else {
				$category=reset($categories);
				$profile['category']=$category->term_id;
			}
		}
		
		$profile['content']=get_post_field('post_content', $ID);
		$profile['about']=ThemexCore::getPostMeta($ID, 'about');
		$profile['policy']=ThemexCore::getPostMeta($ID, 'policy', ThemexCore::getOption('shop_policy'));
		
		return $profile;
	}
	
	/**
	 * Updates shop profile
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateProfile($ID, $data) {

		$redirect=false;
		if(empty($ID)) {
			$ID=self::addShop();
			$redirect=true;			
		}
		
		$args=array(
			'ID' => $ID,
		);
		
		//title
		$title=trim(themex_value('title', $data));
		if(empty($title)) {
			ThemexInterface::$messages[]=__('Shop name field is required', 'makery');
		} else {
			$args['post_title']=$title;
		}
		
		//category
		if(themex_taxonomy('shop_category')) {
			if(ThemexCore::checkOption('shop_category')) {
				$categories=themex_array('category', $data);
				
				if(!empty($categories) && is_array($categories)) {
					wp_set_object_terms($ID, '', 'shop_category');
					
					foreach($categories as $category) {
						$term=get_term($category, 'shop_category');
						
						if(empty($term)) {
							ThemexInterface::$messages[]=__('This shop category does not exist', 'makery');
						} else {
							wp_set_object_terms($ID, $term->name, 'shop_category', true);
						}
					}
				} else {
					ThemexInterface::$messages[]=__('Shop category field is required', 'makery');
				}
			} else {
				$category=intval(themex_value('category', $data));
				
				if(empty($category)) {
					ThemexInterface::$messages[]=__('Shop category field is required', 'makery');
				} else {
					$term=get_term($category, 'shop_category');
					
					if(empty($term)) {
						ThemexInterface::$messages[]=__('This shop category does not exist', 'makery');
					} else {
						wp_set_object_terms($ID, $term->name, 'shop_category');
					}
				}
			}			
		}
		
		//content
		$content=trim(themex_value('content', $data));
		if(empty($content)) {
			ThemexInterface::$messages[]=__('Shop description field is required', 'makery');
		} else {
			$args['post_content']=wp_kses($content, array(
				'strong' => array(),
				'em' => array(),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'p' => array(),
				'br' => array(),
			));
		}
		
		ThemexCore::updatePostMeta($ID, 'about', themex_value('about', $data));
		ThemexCore::updatePostMeta($ID, 'policy', themex_value('policy', $data));
		
		if(empty(ThemexInterface::$messages)) {
			$status=get_post_status($ID);
			if($status=='draft') {
				$args['post_status']='pending';
				if(ThemexCore::checkOption('shop_approve')) {
					$args['post_status']='publish';
				}
				
				$redirect=true;
			} else {
				ThemexInterface::$messages[]=__('Shop has been successfully saved', 'makery');
				$_POST['success']=true;
			}
		}
		
		//update post
		wp_update_post($args);
		
		if($redirect && empty(ThemexInterface::$messages)) {
			wp_redirect(ThemexCore::getURL('shop-settings'));
			exit();
		}
	}
	
	/**
	 * Gets shop withdrawals
     *
     * @access public
	 * @param int $user
	 * @param array $args
     * @return array
     */
	public static function getWithdrawals($user, $args=array()) {
		$withdrawals=get_posts(array_merge(array(
			'post_type' => 'withdrawal',
			'post_status' => 'pending',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'author' => $user,
		), $args));
		
		return $withdrawals;
	}
	
	/**
	 * Gets shop withdrawal
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getWithdrawal($ID) {
		$post=get_post($ID);
		$data=get_post_meta($ID);
		
		$withdrawal['ID']=$ID;
		$withdrawal['date']=$post->post_date;
		$withdrawal['amount']=abs(floatval(themex_value('_'.THEMEX_PREFIX.'amount', $data)));
		
		$method=themex_value('_'.THEMEX_PREFIX.'method', $data);
		$withdrawal['method']=array(
			'name' => $method,
			'label' => themex_value($method, ThemexCore::$components['forms']['withdrawal']['method']['options']),
		);
		
		if(isset(ThemexCore::$components['forms']['withdrawal'][$method])) {
			foreach(ThemexCore::$components['forms']['withdrawal'][$method] as $field) {
				$withdrawal[$field['name']]=themex_value('_'.THEMEX_PREFIX.$field['name'], $data);
			}
		}
		
		return $withdrawal;
	}
	
	/**
	 * Adds shop withdrawal
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function addWithdrawal($data) {
		$user=get_current_user_id();
		self::updateBalance($user);
		
		$args=array(
			'post_type' => 'withdrawal',
			'post_status' => 'pending',
			'post_author' => $user,
		);
		
		$amount=ThemexWoo::formatPrice(themex_value('amount', $data), false);
		if(empty($amount)) {
			ThemexInterface::$messages[]=__('"Amount" field is required', 'makery');
		} else {
			$balance=round(floatval(ThemexCore::getUserMeta($user, 'balance')), 2);
			if($amount>$balance) {
				ThemexInterface::$messages[]=__('Amount is larger than balance', 'makery');
			}
			
			$minimum=intval(ThemexCore::getOption('withdrawal_min', 50));
			if($amount<$minimum) {
				ThemexInterface::$messages[]=__('Amount is too small for withdrawal', 'makery');
			}
		}		
		
		$method=sanitize_title(themex_value('method', $data));
		if(!isset(ThemexCore::$components['forms']['withdrawal'][$method])) {
			ThemexInterface::$messages[]=__('"Method" field is required', 'makery');
		}
		
		$recipient=array();
		if(isset(ThemexCore::$components['forms']['withdrawal'][$method])) {
			foreach(ThemexCore::$components['forms']['withdrawal'][$method] as $field) {
				$value=trim(sanitize_text_field(themex_value($field['name'], $data)));
				
				if($field['type']=='select') {
					$value=themex_value($value, $field['options']);
				} else if($field['type']=='select_country') {
					$value=themex_value($value, ThemexCore::$components['countries']);
				}
				
				if(empty($value)) {
					ThemexInterface::$messages[]='"'.$field['label'].'" '.__('field is required', 'makery');
				} else if($field['type']=='email' && !sanitize_email($value)) {
					ThemexInterface::$messages[]=__('Email address is invalid', 'makery');
				}
				
				$recipient[$field['name']]=$value;
			}
		}
		
		if(empty(ThemexInterface::$messages)) {
			$withdrawal=wp_insert_post($args);
			if(!empty($withdrawal)) {
			
				//title
				wp_update_post(array(
					'ID' => $withdrawal,
					'post_title' => '#'.$withdrawal,
				));
				
				//amount			
				ThemexCore::updatePostMeta($withdrawal, 'amount', $amount);				
				
				//method						
				ThemexCore::updatePostMeta($withdrawal, 'method', $method);
				
				//recipient
				foreach(ThemexCore::$components['forms']['withdrawal'][$method] as $field) {
					ThemexCore::updatePostMeta($withdrawal, $field['name'], $recipient[$field['name']]);
				}
				
				self::updateBalance($user);
			}
		}
	}
	
	/**
	 * Updates shop withdrawal
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function updateWithdrawal($ID) {
		$type=get_post_type($ID);
		
		if($type=='shop') {
			add_post_meta($ID, '_'.THEMEX_PREFIX.'hidden', '1', true);
			add_post_meta($ID, '_'.THEMEX_PREFIX.'rating', '0', true);
			add_post_meta($ID, '_'.THEMEX_PREFIX.'sales', '0', true);
			add_post_meta($ID, '_'.THEMEX_PREFIX.'admirers', '0', true);
		} else if($type=='product') {
			add_post_meta($ID, '_'.THEMEX_PREFIX.'hidden', '0', true);
		} else if($type=='withdrawal') {
			self::updateBalance(get_post_field('post_author', $ID));
		}
	}
	
	/**
	 * Removes shop withdrawal
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function removeWithdrawal($ID) {
		$user=get_current_user_id();
		
		wp_delete_post($ID, true);
		self::updateBalance($user);
	}
	
	/**
	 * Renders withdrawal options
     *
     * @access public
	 * @param int $ID
     * @return string
     */
	public static function renderWithdrawal($ID) {
		$out='';
		
		$author=self::getAuthor($ID);
		$withdrawal=self::getWithdrawal($ID);
		$nickname=get_user_meta($author, 'nickname', true);
		$fields['author']=array(
			'label' => __('Author', 'makery'),
			'value' => '<a href="'.get_edit_user_link($author).'">'.$nickname.'</a>',
		);
		
		$fields['date']=array(
			'label' => __('Date', 'makery'),
			'value' => date('Y/m/d', strtotime($withdrawal['date'])),
		);
		
		$fields['amount']=array(
			'label' => __('Amount', 'makery'),
			'value' => ThemexWoo::getPrice($withdrawal['amount']),
		);
		
		$fields['method']=array(
			'label' => __('Method', 'makery'),
			'value' => $withdrawal['method']['label'],
		);
		
		if(isset(ThemexCore::$components['forms']['withdrawal'][$withdrawal['method']['name']])) {
			foreach(ThemexCore::$components['forms']['withdrawal'][$withdrawal['method']['name']] as $field) {
				$fields[$field['name']]=array(
					'label' => $field['label'],
					'value' => $withdrawal[$field['name']],
				);
			}
		}
		
		foreach($fields as $field) {
			$out.='<tr><th><h4 class="themex-meta-title">'.$field['label'].'</h4></th><td class="themex-meta-value">'.$field['value'].'</td></tr>';
		}
		
		return $out;
	}
	
	/**
	 * Gets shipping settings
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getShipping($ID) {
		$shipping=ThemexCore::getPostMeta($ID, 'shipping');
		
		if(ThemexCore::checkOption('shop_shipping') || empty($shipping) || !is_array($shipping)) {
			$shipping=array(
				'free_shipping' => array(),
				'flat_rate' => array(),
				'international_delivery' => array(),
				'local_delivery' => array(),
				'local_pickup' => array(),
			);
		}
		
		return $shipping;
	}
	
	/**
	 * Updates shipping settings
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateShipping($ID, $data) {
		$shipping=array();
		$methods=ThemexWoo::getShippingMethods();
		$defaults=array(
			'free_shipping', 
			'flat_rate',
			'international_delivery',
			'local_delivery',
			'local_pickup',
		);
		
		foreach($defaults as $default) {
			if(isset($methods[$default])) {
				$shipping[$default]['enabled']=sanitize_title(themex_value($default.'_enabled', $data));
				$shipping[$default]['availability']=sanitize_title(themex_value($default.'_availability', $data));
				
				$countries=themex_array($default.'_countries', $data);
				if(is_array($countries)) {
					foreach($countries as &$country) {
						if(is_array($country) && !empty($country)) {
							$country=reset($country);
						}
					}
				}
				
				$shipping[$default]['countries']=$countries;				
				if($default=='free_shipping') {
					$shipping[$default]['min_amount']=ThemexWoo::formatPrice(themex_value($default.'_min_amount', $data), false);
				} else if($default=='local_delivery') {
					$shipping[$default]['cost']=ThemexWoo::formatPrice(themex_value($default.'_cost', $data), false);
				} else if(in_array($default, array('international_delivery', 'flat_rate'))) {
					$shipping[$default]['default_cost']=ThemexWoo::formatPrice(themex_value($default.'_default_cost', $data), false);
					
					$costs=themex_array($default.'_cost', $data);
					if(is_array($costs)) {
						foreach($costs as &$cost) {
							$cost=ThemexWoo::formatPrice($cost, false);
						}
					}					
					
					$classes=themex_array($default.'_class', $data);
					$shipping[$default]['costs']=array();
					
					if(is_array($classes)) {
						$classes=array_map('sanitize_title', $classes);
						foreach($classes as $index => $class) {
							if(isset($costs[$index]) && !empty($costs[$index])) {
								$shipping[$default]['costs'][$class]=$costs[$index];
							}
						}
					}
				}
			}
		}
		
		ThemexCore::updatePostMeta($ID, 'shipping', $shipping);
		ThemexInterface::$messages[]=__('Changes have been successfully saved', 'makery');
		$_POST['success']=true;
	}
	
	/**
	 * Updates shop balance
     *
     * @access public
	 * @param int $user
	 * @param array $data
     * @return void
     */
	public static function updateBalance($user, $data=array()) {
		$shop=ThemexUser::getShop($user);
		
		//values
		$revenue=0;
		$profit=0;
		$balance=0;
		$sales=0;
		
		//rates
		$rate_min=absint(ThemexCore::getOption('shop_rate_min', 50));
		$rate_max=absint(ThemexCore::getOption('shop_rate_max', 70));
		$rate_amount=absint(ThemexCore::getOption('shop_rate_amount', 1000));
		
		if(isset($data['order'])) {
			$rate=$rate_min;
			if($rate_max>$rate_min) {
				$rate=absint(ThemexCore::getUserMeta($user, 'rate', $rate_min));				
			}
			
			$rate=self::filterRate($shop, $rate);
			ThemexCore::updatePostMeta($data['order'], 'rate', $rate);
		}
		
		//orders
		$orders=ThemexWoo::getOrders($user, array(
			'post_status' => 'wc-completed',
		));
		
		foreach($orders as $order) {
			$object=wc_get_order($order);
			$rate=absint(ThemexCore::getPostMeta($order, 'rate', $rate_min));
			$total=$object->get_total()-$object->get_total_refunded();
			$amount=$total*$rate/100;
			
			$revenue=$revenue+$total;
			$profit=$profit+$amount;
			
			if($object->payment_method!='paypal-adaptive-payments') {
				$balance=$balance+$amount;
			}
			
			$sales=$sales+$object->get_item_count();
		}
		
		//referrals
		$rate=absint(ThemexCore::getOption('shop_rate_referral', '30'));
		$referrals=ThemexWoo::getReferrals($user, array(
			'post_status' => 'wc-completed',
		));

		foreach($referrals as $referral) {
			$object=wc_get_order($referral);
			$total=$object->get_total()-$object->get_total_refunded();
			$amount=$total*$rate/100;
			
			$profit=$profit+$amount;
			$balance=$balance+$amount;
		}
		
		//withdrawals
		$withdrawals=self::getWithdrawals($user, array(
			'post_status' => array('pending', 'publish'),
		));
		
		foreach($withdrawals as $withdrawal) {
			$amount=abs(floatval(ThemexCore::getPostMeta($withdrawal, 'amount')));
			$balance=$balance-$amount;
		}
		
		//rate		
		if($rate_max>$rate_min) {
			$rate=absint($rate_min+$revenue/($rate_amount/($rate_max-$rate_min)));
			ThemexCore::updateUserMeta($user, 'rate', $rate);
		}
		
		ThemexCore::updateUserMeta($user, 'revenue', $revenue);
		ThemexCore::updateUserMeta($user, 'profit', $profit);
		ThemexCore::updateUserMeta($user, 'balance', $balance);		
		
		ThemexCore::updatePostMeta($shop, 'sales', $sales);
	}
	
	/**
	 * Updates shop rating
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function updateRating($ID) {
		$rating=get_comment_meta($ID, 'rating', true);
		if(!empty($rating)) {
			$comment=get_comment($ID);
			$user=get_post_field('post_author', $comment->comment_post_ID);
			$shop=ThemexUser::getShop($user);			
			$rating=ThemexWoo::getRating($user);			
			
			ThemexCore::updatePostMeta($shop, 'rating', $rating['rating']);
			ThemexCore::updatePostMeta($shop, 'ratings', $rating['ratings']);
		}
	}
	
	/**
	 * Submits shop question
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function submitQuestion($data) {
		
		$type='product';
		if(isset($data['shop_id'])) {
			$type='shop';
		}
		
		$ID=intval(themex_value($type.'_id', $data));
		if(!empty($ID)) {
			$question=sanitize_text_field(themex_value('question', $data));
			if(empty($question)) {
				ThemexInterface::$messages[]='"'.__('Question', 'makery').'" '.__('field is required', 'makery');
			}
			
			if(empty(ThemexInterface::$messages)) {
				$user=get_userdata(get_current_user_id());
				$author=get_userdata(self::getAuthor($ID));
				
				$subject=__('Item Question', 'makery');
				$content=ThemexCore::getOption('email_product_question', 'Sender: %user%<br />Item: %product%<br />Question: %question%');				
				if($type=='shop') {
					$subject=__('Shop Question', 'makery');
					$content=ThemexCore::getOption('email_shop_question', 'Sender: %user%<br />Shop: %shop%<br />Question: %question%');
				}
				
				$link='<a href="'.get_permalink($ID).'">'.get_the_title($ID).'</a>';
				$keywords=array(
					'user' => '<a href="'.get_author_posts_url($user->ID).'">'.$user->user_login.'</a>',
					'shop' => $link,
					'product' => $link,
					'question' => wpautop($question),
				);
				
				$content=themex_keywords($content, $keywords);
				themex_mail($author->user_email, $subject, $content, $user->user_email);
				
				ThemexInterface::$messages[]=__('Question has been successfully sent', 'makery');
				ThemexInterface::renderMessages(true);
			} else {
				ThemexInterface::renderMessages();
			}
		}
		
		die();
	}
	
	/**
	 * Submits shop report
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function submitReport($data) {
		$shop=intval(themex_value('shop_id', $data));
		
		if(!empty($shop)) {
			$reason=sanitize_text_field(themex_value('reason', $data));
			if(empty($reason)) {
				ThemexInterface::$messages[]='"'.__('Reason', 'makery').'" '.__('field is required', 'makery');
			}
			
			if(empty(ThemexInterface::$messages)) {
				$subject=__('Shop Report', 'makery');
				$content=ThemexCore::getOption('email_shop_report', 'Sender: %user%<br />Shop: %shop%<br />Reason: %reason%');
				$user=get_userdata(get_current_user_id());
				
				$keywords=array(
					'user' => '<a href="'.get_author_posts_url($user->ID).'">'.$user->user_login.'</a>',
					'shop' => '<a href="'.get_permalink($shop).'">'.get_the_title($shop).'</a>',
					'reason' => wpautop($reason),
				);
				
				$content=themex_keywords($content, $keywords);
				themex_mail(get_option('admin_email'), $subject, $content, $user->user_email);
				
				ThemexInterface::$messages[]=__('Report has been successfully sent', 'makery');
				ThemexInterface::renderMessages(true);
			} else {
				ThemexInterface::renderMessages();
			}
		}
		
		die();
	}
}