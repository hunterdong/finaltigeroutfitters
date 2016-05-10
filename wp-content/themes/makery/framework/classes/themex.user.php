<?php
/**
 * Themex User
 *
 * Handles users data
 *
 * @class ThemexUser
 * @author Themex
 */
 
class ThemexUser {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//refresh module data
		add_action('wp', array(__CLASS__, 'refresh'), 2);
		
		//update user actions
		add_action('wp', array(__CLASS__, 'updateUser'), 1);
		add_action('wp_ajax_themex_update_user', array(__CLASS__, 'updateUser'));
		add_action('wp_ajax_nopriv_themex_update_user', array(__CLASS__, 'updateUser'));
		add_action('template_redirect', array(__CLASS__, 'activateUser'));
		
		//membership actions
		add_filter('save_post',  array(__CLASS__, 'updateMembership'));
		add_action('template_redirect', array(__CLASS__, 'filterMembership'));
		add_action('save_post', array(__CLASS__, 'triggerMembership'));
		add_action('delete_post', array(__CLASS__, 'triggerMembership'));
		
		//add avatar filter
		add_filter('get_avatar', array(__CLASS__, 'getAvatar'), 10, 5);
		
		//render admin profile
		add_filter('show_user_profile', array(__CLASS__,'renderAdminProfile'));
		add_filter('edit_user_profile', array(__CLASS__,'renderAdminProfile'));
		
		//update admin profile
		add_action('edit_user_profile_update', array(__CLASS__,'updateAdminProfile'));
		add_action('personal_options_update', array(__CLASS__,'updateAdminProfile'));
		
		//render admin toolbar
		add_filter('show_admin_bar', array(__CLASS__,'renderToolbar'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public	 
     * @return void
     */
	public static function refresh() {
		$ID=get_current_user_id();
		self::$data['current']=self::getUser($ID, true);

		$user=0;
		if($var=get_query_var('author')) {
			$user=intval($var);
		}
		
		if($user!=0) {
			self::$data['active']=self::getUser($user, true);
		} else {
			self::$data['active']=self::$data['current'];
		}
	}
	
	/**
	 * Updates user
     *
     * @access public	 
     * @return void
     */
	public static function updateUser() {		
		
		$data=$_POST;
		if(isset($_POST['data'])) {
			parse_str($_POST['data'], $data);
		}
		
		if(isset($data['user_action'])) {
			$action=sanitize_title($data['user_action']);
			$ID=get_current_user_id();
			$redirect=false;

			if(!empty($ID)) {
				switch($action) {
					case 'update_avatar':
						self::updateAvatar($ID, themex_array('user_avatar', $_FILES));
						$redirect=true;
					break;
					
					case 'update_profile':
						self::updateProfile($ID, $data);
					break;
					
					case 'update_settings':
						self::updateSettings($ID, $data);
					break;
					
					case 'add_membership':
						self::addMembership($ID, themex_array('membership_id', $data));
					break;
					
					case 'add_relation':
						self::addRelation($ID, $data);
					break;
					
					case 'remove_relation':
						self::removeRelation($ID, $data);
					break;
					
					case 'submit_message':
						self::submitMessage($ID, $data);
					break;
				}
			}
			
			switch($action) {			
				case 'register_user':
					self::registerUser($data);
				break;
				
				case 'login_user':
					self::loginUser($data);
				break;
				
				case 'reset_user':
					self::resetUser($data);
				break;
			}
			
			if($redirect || empty(ThemexInterface::$messages)) {
				wp_redirect(themex_url());
				exit();
			}
		}
	}
	
	/**
	 * Gets user
     *
     * @access public
	 * @param int $ID
	 * @param bool $extended
     * @return array
     */
	public static function getUser($ID, $extended=false) {
		$data=get_userdata($ID);
		if($data!=false) {
			$user['login']=$data->user_login;
			$user['email']=$data->user_email;
			$user['date']=$data->user_registered;
		}
	
		$user['ID']=$ID;
		$user['profile']=self::getProfile($ID);
		$user['settings']=self::getSettings($ID);
		
		$user['shop']=self::getShop($ID);
		$user['favorites']=array_reverse(ThemexCore::getUserRelations($ID, 0, 'product'));
		$user['shops']=ThemexCore::getUserRelations($ID, 0, 'shop');
		$user['updates']=ThemexWoo::getRelations($user['shops']);
		$user['clicks']=intval(ThemexCore::getUserMeta($ID, 'clicks'));
		$user['profit']=round(floatval(ThemexCore::getUserMeta($ID, 'profit')), 2);		
		$user['balance']=round(floatval(ThemexCore::getUserMeta($ID, 'balance')), 2);
		
		if($extended) {
			$user['links']=self::getLinks($ID, array(
				'shops' => !ThemexCore::checkOption('shop_multiple'),
				'shop' => themex_status($user['shop'])=='publish',
				'referrals' => !ThemexCore::checkOption('shop_referrals'),
				'woocommerce' => ThemexWoo::isActive(),
				'address' => !ThemexCore::checkOption('profile_address'),
				'links' => !ThemexCore::checkOption('profile_links'),
				'shipping' => !ThemexCore::checkOption('shop_shipping') && ThemexWoo::isShipping(),
				'membership' => ThemexCore::checkOption('membership_free'),
			));
		}
		
		return $user;
	}
	
	/**
	 * Gets user profile
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getProfile($ID) {
		$profile=array();
		$data=get_user_meta($ID);

		foreach(ThemexCore::$components['forms'] as $fields) {
			foreach($fields as $field) {
				if(!is_array(reset($field))) {
					if(!isset($field['prefix']) || $field['prefix']) {
						$profile[$field['name']]=themex_value('_'.THEMEX_PREFIX.$field['name'], $data);
					} else {
						$profile[$field['name']]=themex_value($field['name'], $data);
					}
				}
			}
		}
		
		//get name
		$profile['nickname']=themex_value('nickname', $data);
		$profile['description']=themex_value('description', $data);
		
		if(empty($profile['first_name'])) {
			$profile['first_name']=$profile['nickname'];
			$profile['last_name']='';
			$profile['full_name']=$profile['first_name'];
		} else {
			$profile['last_name']=themex_value('last_name', $data);
			$profile['full_name']=trim($profile['first_name'].' '.$profile['last_name']);
		}
		
		//get location
		$profile['location']=$profile['billing_city'];
		if(!empty($profile['billing_country'])) {
			if(!empty($profile['location'])) {
				$profile['location'].=', ';
			}
			
			$profile['location'].=themex_value($profile['billing_country'], ThemexCore::$components['countries']);
		}
		
		//get fields
		if(ThemexForm::isActive('profile')) {
			foreach(ThemexForm::$data['profile']['fields'] as $field) {
				$name=themex_sanitize_key($field['name']);
				if(!isset($profile[$name])) {
					$profile[$name]='';					
					if(isset($data['_'.THEMEX_PREFIX.$name][0])) {
						$profile[$name]=$data['_'.THEMEX_PREFIX.$name][0];
					}
				}
			}
		}
		
		return $profile;
	}
	
	/**
	 * Updates user profile
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateProfile($ID, $data) {
	
		$shop=self::getShop($ID);
		if(!empty($shop)) {
			if(isset($data['billing_country'])) {
				$country=trim(sanitize_text_field($data['billing_country']));
				ThemexCore::updatePostMeta($shop, 'country', $country);
			}
			
			if(isset($data['billing_city'])) {
				$city=trim(sanitize_text_field($data['billing_city']));
				$city=ucwords(strtolower($city));
				
				ThemexCore::updatePostMeta($shop, 'city', $city);
			}
		}
		
		foreach(ThemexCore::$components['forms'] as $form=>$fields) {
			if($form=='profile' && ThemexForm::isActive('profile')) {
				$customs=ThemexForm::$data['profile']['fields'];
				foreach($customs as &$custom) {
					$custom['name']=themex_sanitize_key($custom['name']);
				}
				
				$fields=array_merge($fields, $customs);
			}
			
			foreach($fields as $field) {
				if(isset($field['name']) && isset($data[$field['name']])) {
					$name=$field['name'];
					$alias=themex_value('alias', $field);
					$type=themex_value('type', $field);
					$value=$data[$name];
					
					if($type=='text') {
						$value=trim(sanitize_text_field($value));
					} else if($type=='number') {
						$value=intval($value);
					}
					
					if($name=='billing_city') {
						$value=ucwords(strtolower($value));
					}
					
					if(isset($field['required']) && $field['required'] && ($value=='' || ($type=='select' && $value=='0'))) {
						ThemexInterface::$messages[]='"'.$field['label'].'" '.__('field is required', 'makery');
					} else {
						if(!isset($field['prefix']) || $field['prefix']) {
							ThemexCore::updateUserMeta($ID, $name, $value);
						} else {
							update_user_meta($ID, $name, $value);							
							if(!empty($alias)) {
								update_user_meta($ID, $alias, $value);
							}
						}
					}
				}
			}
		}
		
		if(isset($data['description'])) {
			$data['description']=trim($data['description']);
			$data['description']=wp_kses($data['description'], array(
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

			update_user_meta($ID, 'description', $data['description']);
		}
		
		if(empty(ThemexInterface::$messages)) {
			ThemexInterface::$messages[]=__('Profile has been successfully saved', 'makery');
			$_POST['success']=true;
		}
	}
	
	/**
	 * Filters default avatar
     *
     * @access public
	 * @param string $avatar
	 * @param int $user_id
	 * @param int $size
	 * @param string $default
	 * @param string $alt
     * @return string
     */
	public static function getAvatar($avatar, $user, $size, $default, $alt) {
		if(isset($user->user_id)) {
			$user=$user->user_id;
		}
		
		$avatar_id=ThemexCore::getUserMeta($user, 'avatar');
		$default=wp_get_attachment_url($avatar_id);
		$image=THEME_URI.'images/avatar.png';
		
		if($default!==false) {
			$thumbnail=themex_resize($default, $size, $size);
			if(!empty($thumbnail)) {
				$image=$thumbnail;
			}
		}
		
		$out='<img src="'.$image.'" class="avatar" width="'.$size.'" alt="'.$alt.'" />';
		return $out;
	}
	
	/**
	 * Updates user avatar
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function updateAvatar($ID, $file) {		
		$avatar=intval(ThemexCore::getUserMeta($ID, 'avatar'));
		$attachment=ThemexCore::addFile($file);
		wp_delete_attachment($avatar, true);
		
		if(isset($attachment['ID']) && $attachment['ID']!=0) {
			ThemexCore::updateUserMeta($ID, 'avatar', $attachment['ID']);
		}
	}
	
	/**
	 * Gets user settings
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getSettings($ID) {
		$settings['notices']=ThemexCore::getUserMeta($ID, 'notices', '1');
		
		return $settings;
	}
	
	/**
	 * Updates user settings
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateSettings($ID, $data) {
		$current=get_user_by('id', $ID);
		$facebook=ThemexFacebook::isUpdated($ID);
		$required=false;
		
		$user=array(
			'ID' => $ID,
		);
		
		//password
		$new_password=trim(themex_value('new_password', $data));
		if(!empty($new_password)) {
			$confirm_password=trim(themex_value('confirm_password', $data));
			$user['user_pass']=$new_password;
			$required=true;
			
			if(strlen($new_password)<4) {
				ThemexInterface::$messages[]=__('Password must be at least 4 characters long', 'makery');
			} else if(strlen($new_password)>16) {
				ThemexInterface::$messages[]=__('Password must be not more than 16 characters long', 'makery');
			} else if(preg_match("/^([a-zA-Z0-9@#-_$%^&+=!?]{1,20})$/", $new_password)==0) {
				ThemexInterface::$messages[]=__('Password contains invalid characters', 'makery');
			} else if($new_password!=$confirm_password) {
				ThemexInterface::$messages[]=__('Passwords do not match', 'makery');
			}
		}
		
		//email
		$email=trim(themex_value('email', $data));
		if($email!=$current->user_email) {
			$user['user_email']=$email;
			$required=true;
			
			if(!is_email($email)) {
				ThemexInterface::$messages[]=__('Email address is invalid', 'makery');
			}
		}
		
		//notices
		$notices=themex_value('notices', $data);
		ThemexCore::updateUserMeta($ID, 'notices', $notices);
		
		$current_password=trim(themex_value('current_password', $data));
		if($required && $facebook && !wp_check_password($current_password, $current->user_pass, $current->ID)) {
			ThemexInterface::$messages[]=__('Current password is incorrect', 'makery');
		}
		
		if(empty(ThemexInterface::$messages)) {
			wp_update_user($user);			
			if(isset($user['user_email'])) {
				update_user_meta($ID, 'billing_email', $user['user_email']);
			}
			
			if(isset($user['user_pass']) && !$facebook) {
				ThemexFacebook::updateUser($ID);
			}
			
			ThemexInterface::$messages[]=__('Settings have been successfully saved', 'makery');
			$_POST['success']=true;
		}
	}
	
	/**
	 * Updates user membership
     *
	 * @param int $ID
     * @access public
     * @return void
     */
	public static function updateMembership($ID) {
		if(current_user_can('edit_posts') && isset($_POST['post_type']) && $_POST['post_type']=='membership') {
			if(isset($_POST['add_user']) && isset($_POST['add_user_id'])) {
				self::addMembership(intval($_POST['add_user_id']), $ID, false);
			} else if(isset($_POST['remove_user']) && isset($_POST['remove_user_id'])) {
				self::removeMembership(intval($_POST['remove_user_id']));
			}
		}
	}
	
	/**
	 * Adds user membership
     *
     * @access public
	 * @param int $ID
	 * @param int $membership
	 * @param bool $checkout
     * @return void
     */
	public static function addMembership($ID, $membership, $checkout=true) {
		$membership=intval($membership);
		
		if($checkout && ThemexWoo::isActive()) {
			$product=intval(ThemexCore::getPostMeta($membership, 'product'));
			if($product!=0) {
				ThemexWoo::checkoutProduct($product);
			}
		} else {
			if($membership==0) {
				$period=0;
			} else {
				$period=absint(ThemexCore::getPostMeta($membership, 'period'));							
			}
			
			$date=$period*86400+current_time('timestamp');
			if($period==0) {
				$date=0;
			}
			
			ThemexCore::updateUserMeta($ID, 'membership', strval($membership));
			ThemexCore::updateUserMeta($ID, 'membership_date', strval($date));
		}
		
		self::countMembership($ID);
	}
	
	/**
	 * Removes user membership
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function removeMembership($ID) {
		self::addMembership($ID, 0, false);
		self::countMembership($ID);
	}
	
	/**
	 * Gets user membership
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getMembership($ID) {	
		$membership=array();
		
		$membership['ID']=ThemexCore::getUserMeta($ID, 'membership');
		if($membership['ID']!=='') {
			$membership['ID']=intval($membership['ID']);
		}
		
		$membership['title']=get_the_title($membership['ID']);
		if(empty($membership['ID'])) {
			$membership['title']=__('Free', 'makery');
		}
		
		$date=intval(ThemexCore::getUserMeta($ID, 'membership_date'));
		
		$subscriptions=array();
		if(function_exists('wcs_get_users_subscriptions')) {
			$subscriptions=wcs_get_users_subscriptions($ID);
		}
		
		if(is_array($subscriptions) && !empty($subscriptions)) {
			$product=intval(ThemexCore::getPostMeta($membership['ID'], 'product'));
			
			if($product!=0) {
				foreach($subscriptions as $subscription) {
					$first=reset($subscription->order->get_items());
					
					if(is_array($first) && isset($first['product_id']) && $first['product_id']==$product) {
						$date=strtotime($subscription->end_date);
						
						if(empty($date)) {
							$date=strtotime($subscription->next_payment_date);
						}
						
						break;
					}
				}
			}
		}
		
		$membership['date']=date(get_option('date_format'), $date);
		if($date<current_time('timestamp')) {
			$membership['date']='&ndash;';
		}
		
		$membership['products']=absint(ThemexCore::getPostMeta($membership['ID'], 'products'));
		if($membership['ID']===0) {
			$membership['products']=0;			
		} else if($membership['ID']==='') {
			$membership['products']=absint(ThemexCore::getOption('membership_products'));
		}
		
		if($membership['products']<0) {
			$membership['products']=0;
		}
		
		return $membership;
	}
	
	/**
	 * Filters user membership
     *
     * @access public
     * @return void
     */
	public static function filterMembership() {
		if(ThemexCore::checkOption('membership_free') && is_user_logged_in()) {
			$user=get_current_user_id();
			$shop=self::getShop($user);
			
			if(!empty($shop)) {
				$membership=ThemexCore::getUserMeta($user, 'membership');
				if($membership!=='') {
					$membership=intval($membership);
				}
				
				$subscriptions=array();
				if(function_exists('wcs_get_users_subscriptions')) {
					$subscriptions=wcs_get_users_subscriptions($user);
				}
				
				if($membership!==0) {
					$date=ThemexCore::getUserMeta($user, 'membership_date');
					
					if(is_array($subscriptions) && !empty($subscriptions)) {
						$product=intval(ThemexCore::getPostMeta($membership, 'product'));
						
						if($product!=0) {
							foreach($subscriptions as $subscription) {
								$first=reset($subscription->order->get_items());
								
								if(is_array($first) && isset($first['product_id']) && $first['product_id']==$product) {
									$date=strtotime($subscription->end_date);
									
									if(empty($date)) {
										$date=strtotime($subscription->next_payment_date);
									}
									
									break;
								}
							}
						}
					}
					
					if($date==='') {
						$period=absint(ThemexCore::getOption('membership_period', 31));
						$date=$period*86400+current_time('timestamp');
						if($period==0) {
							$date=0;
						}
						
						ThemexCore::updateUserMeta($user, 'membership_date', strval($date));
					}
					
					$date=intval($date);
					if($date<current_time('timestamp') && $date!=0) {
						self::removeMembership($user);
					}
				}
			}
		}
	}
	
	/**
	 * Triggers user membership
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function triggerMembership($ID) {
		if(get_post_type($ID)=='product') {
			self::countMembership(get_post_field('post_author', $ID));
		}
	}
	
	/**
	 * Counts user membership
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function countMembership($ID) {
		global $wpdb;
		
		$results=$wpdb->get_results($wpdb->prepare("
			SELECT ID AS products FROM ".$wpdb->posts." 
			WHERE post_type = 'product' 
			AND  post_status NOT IN('trash') 
			AND post_author = %d", $ID));
		
		$results=wp_list_pluck($results, 'products');		
		$products=count($results);		
		
		if($products==1) {
			$first=reset($results);		
			
			if(get_post_status($first)=='draft') {
				$products=0;
			}			
		}
		
		$shop=self::getShop($ID);
		if(ThemexCore::checkOption('shops_empty')) {			
			if($products==0) {
				ThemexCore::updatePostMeta($shop, 'hidden', '1');
			} else {
				ThemexCore::updatePostMeta($shop, 'hidden', '0');
			}
		}
		
		if(ThemexCore::checkOption('membership_free')) {
			$membership=ThemexCore::getUserMeta($ID, 'membership');
			if($membership!=='') {
				$membership=intval($membership);
			}
			
			if($membership===0) {
				$limit=0;
			} else if($membership==='') {
				$limit=absint(ThemexCore::getOption('membership_products'));
			} else {			
				$limit=absint(ThemexCore::getPostMeta($membership, 'products'));
			}
			
			$hidden='0';
			if($products>$limit) {
				$hidden='1';
			}				
			
			ThemexCore::updateUserMeta($ID, 'hidden', $hidden);		
			if(!ThemexCore::checkOption('shops_empty') || $products!=0) {
				ThemexCore::updatePostMeta($shop, 'hidden', $hidden);	
			}
			
			foreach($results as $product) {
				ThemexCore::updatePostMeta($product, 'hidden', $hidden);
			}		
		}
	}
	
	/**
	 * Checks user membership
     *
     * @access public
	 * @param int $ID
     * @return bool
     */
	public static function isMember($ID) {
		if(ThemexCore::checkOption('membership_free')) {
			$hidden=intval(ThemexCore::getUserMeta($ID, 'hidden'));
			if($hidden==1) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Gets membership period
     *
     * @access public
	 * @param int $membership
     * @return string
     */
	public static function getPeriod($membership) {
		$price='';
		
		$product=intval(ThemexCore::getPostMeta($membership, 'product'));
		if(!empty($product)) {
			$period=intval(ThemexCore::getPostMeta($membership, 'period'));
			$price=ThemexWoo::getPeriod($product, $period);
		}
		
		return $price;
	}
	
	/**
	 * Adds user relation
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function addRelation($ID, $data) {
		$relation=themex_value('relation_id', $data);
		$type=themex_value('relation_type', $data);
		
		if(in_array($type, array('shop', 'product'))) {
			ThemexCore::addUserRelation($ID, $relation, $type);
			
			if($type=='shop') {
				$relations=count(ThemexCore::getUserRelations(0, $relation, 'shop'));
				ThemexCore::updatePostMeta($relation, 'admirers', $relations);
			}
		}
		
		die();
	}
	
	/**
	 * Removes user relation
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function removeRelation($ID, $data) {
		$relation=themex_value('relation_id', $data);
		$type=themex_value('relation_type', $data);
		
		if(in_array($type, array('shop', 'product'))) {
			ThemexCore::removeUserRelation($ID, $relation, $type);
			
			if($type=='shop') {
				$relations=count(ThemexCore::getUserRelations(0, $relation, 'shop'));
				ThemexCore::updatePostMeta($relation, 'admirers', $relations);
			}
		}
		
		die();
	}
	
	/**
	 * Gets user shop
     *
     * @access public
	 * @param int $ID
     * @return int
     */
	public static function getShop($ID) {
		global $wpdb;
		
		$shop=intval($wpdb->get_var($wpdb->prepare("
			SELECT ID FROM {$wpdb->posts} 
			WHERE post_type = 'shop' 
			AND post_status IN ('publish', 'pending', 'draft') 
			AND post_author = %d 
		", intval($ID))));
		
		return $shop;
	}
	
	/**
	 * Gets user links
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return array
     */
	public static function getLinks($ID, $data=array()) {
		$links=array(
			'profile' => array(
				'name' => __('My Profile', 'makery'),
				'url' => get_author_posts_url($ID),
				'child' => array(
					'address' => array(
						'name' => __('Address', 'makery'),
						'url' => ThemexCore::getURL('profile-address'),
						'visible' => $data['woocommerce'] && $data['address'],
					),
					'links' => array(
						'name' => __('Links', 'makery'),
						'url' => ThemexCore::getURL('profile-links'),
						'visible' => $data['links'],
					),
				),
			),
			'orders' => array(
				'name' => __('My Orders', 'makery'),
				'url' => get_permalink(get_option('woocommerce_myaccount_page_id')),
				'visible' => $data['woocommerce'],
			),
			'settings' => array(
				'name' => __('My Settings', 'makery'),
				'url' => ThemexCore::getURL('profile-settings'),
			),
			'referrals' => array(
				'name' => __('My Referrals', 'makery'),
				'url' => ThemexCore::getURL('profile-referrals'),
				'visible' => $data['woocommerce'] && $data['referrals'],
			),
			'shop' => array(
				'name' => __('My Shop', 'makery'),
				'url' => ThemexCore::getURL('shop-settings'),
				'visible' => $data['shops'],
				'child' => array(
					'products' => array(
						'name' => __('Items', 'makery'),
						'url' => ThemexCore::getURL('shop-products'),
						'visible' => $data['shop'] && $data['woocommerce'],
					),		

					'orders' => array(
						'name' => __('Orders', 'makery'),
						'url' => ThemexCore::getURL('shop-orders'),
						'visible' => $data['shop'] && $data['woocommerce'],
					),
					
					'shipping' => array(
						'name' => __('Shipping', 'makery'),
						'url' => ThemexCore::getURL('shop-shipping'),
						'visible' => $data['shop'] && $data['woocommerce'] && $data['shipping'],
					),
					
					'membership' => array(
						'name' => __('Membership', 'makery'),
						'url' => ThemexCore::getURL('shop-membership'),
						'visible' => $data['shop'] && $data['membership'],
					),
				),
			),
			'earnings' => array(
				'name' => __('My Earnings', 'makery'),
				'url' => ThemexCore::getURL('profile-earnings'),
				'visible' => (($data['shop'] && $data['shops']) || $data['referrals']) && $data['woocommerce'],
			),
		);
		
		//custom links
		if(get_query_var('shop-product')) {
			$links['shop']['child']['products']['current']=true;
		}
		
		if(get_query_var('shop-order')) {
			$links['shop']['child']['orders']['current']=true;
		}
		
		//default links
		$current=themex_url();
		foreach($links as $link_key => &$link) {
			if(isset($link['visible']) && !$link['visible']) {
				unset($links[$link_key]);
			} else {
				$link['current']=false;
				if(in_array($current, array($link['url'], $link['url'].'/'))) {
					$link['current']=true;
				}
				
				if(isset($link['child'])) {
					foreach($link['child'] as $child_key => &$child) {
						if(isset($child['visible']) && !$child['visible']) {
							unset($link['child'][$child_key]);
						} else {
							if(!isset($child['current'])) {
								$child['current']=false;
								if(in_array($current, array($child['url'], $child['url'].'/'))) {
									$child['current']=true;
								}
							}
							
							if($child['current']) {
								$link['current']=true;
							}
						}
					}
				}
			}
		}
		
		return $links;
	}
	
	/**
	 * Registers user
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function registerUser($data) {
		if(ThemexCore::checkOption('user_captcha')) {
			session_start();
			if(isset($data['captcha']) && isset($_SESSION['captcha'])) {
				$posted_code=md5($data['captcha']);
				$session_code=$_SESSION['captcha'];
				
				if($session_code!=$posted_code) {
					ThemexInterface::$messages[]=__('Verification code is incorrect', 'makery');
				}
			} else {
				ThemexInterface::$messages[]=__('Verification code is empty', 'makery');
			}
		}
		
		if(empty($data['user_email']) || empty($data['user_login']) || empty($data['user_password']) || empty($data['user_password_repeat'])) {
			ThemexInterface::$messages[]=__('Please fill in all fields', 'makery');
		} else {
			if(!is_email($data['user_email'])) {
				ThemexInterface::$messages[]=__('Invalid email address', 'makery');
			} else if(email_exists($data['user_email'])) {
				ThemexInterface::$messages[]=__('This email is already in use', 'makery');
			}
			
			if(!validate_username($data['user_login']) || is_email($data['user_login']) || preg_match('/\s/', $data['user_login'])) {
				ThemexInterface::$messages[]=__('Invalid character used in username', 'makery');
			} else	if(username_exists($data['user_login'])) {
				ThemexInterface::$messages[]=__('This username is already taken', 'makery');
			}
			
			if(strlen($data['user_password'])<4) {
				ThemexInterface::$messages[]=__('Password must be at least 4 characters long', 'makery');
			} else if(strlen($data['user_password'])>16) {
				ThemexInterface::$messages[]=__('Password must be not more than 16 characters long', 'makery');
			} else if(preg_match("/^([a-zA-Z0-9@#-_$%^&+=!?]{1,20})$/", $data['user_password'])==0) {
				ThemexInterface::$messages[]=__('Password contains invalid characters', 'makery');
			} else if($data['user_password']!=$data['user_password_repeat']) {
				ThemexInterface::$messages[]=__('Passwords do not match', 'makery');
			}
		}
		
		if(empty(ThemexInterface::$messages)){
			$user=wp_create_user($data['user_login'], $data['user_password'], $data['user_email']);			
			$content=ThemexCore::getOption('email_registration', 'Hi, %username%! Welcome to '.get_bloginfo('name').'.');
			wp_new_user_notification($user);
			
			$keywords=array(
				'username' => $data['user_login'],
				'password' => $data['user_password'],
			);
			
			if(ThemexCore::checkOption('user_activation')) {
				ThemexInterface::$messages[]=__('Registration complete! Check your mailbox to activate the account', 'makery');
				$subject=__('Account Confirmation', 'makery');
				$activation_key=md5(uniqid(rand(), 1));
				
				$user=new WP_User($user);
				$user->remove_role(get_option('default_role'));
				$user->add_role('inactive');
				ThemexCore::updateUserMeta($user->ID, 'activation_key', $activation_key);
				
				if(strpos($content, '%link%')===false) {
					$content.=' Click this link to activate your account %link%';
				}

				$link=ThemexCore::getURL('register');
				if(intval(substr($link, -1))==1) {
					$link.='&';
				} else {
					$link.='?';
				}
				
				$keywords['link']=$link.'activate='.urlencode($activation_key).'&user='.$user->ID;
			} else {
				$object=new WP_User($user);
				$object->remove_role(get_option('default_role'));
				$object->add_role('contributor');
				
				wp_signon($data, false);
				$subject=__('Registration Complete', 'makery');
				ThemexInterface::$messages[]='<a href="'.get_author_posts_url($user).'" class="redirect"></a>';
			}
			
			$content=themex_keywords($content, $keywords);
			themex_mail($data['user_email'], $subject, $content);
			ThemexInterface::renderMessages(true);
		} else {
			ThemexInterface::renderMessages();
		}
					
		die();
	}
	
	/**
	 * Logins user
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function loginUser($data) {
		$data['remember']=true;		
		$user=wp_signon($data, false);
		
		if(is_wp_error($user) || empty($data['user_login']) || empty($data['user_password'])){
			ThemexInterface::$messages[]=__('Incorrect username or password', 'makery');
		} else {
			$role=array_shift($user->roles);
			if($role=='inactive') {
				ThemexInterface::$messages[]=__('This account is not activated', 'makery');
			}
		}
		
		if(empty(ThemexInterface::$messages)) {
			ThemexInterface::$messages[]='<a href="'.get_author_posts_url($user->ID).'" class="redirect"></a>';
		} else {
			wp_logout();
		}
			
		ThemexInterface::renderMessages();
		die();
	}
	
	/**
	 * Activates user
     *
     * @access public
     * @return void
     */
	public static function activateUser() {
		if(isset($_GET['activate']) && isset($_GET['user']) && intval($_GET['user'])!=0) {
			$users=get_users(array(
				'meta_key' => '_'.THEMEX_PREFIX.'activation_key',
				'meta_value' => sanitize_text_field($_GET['activate']),
				'include' => intval($_GET['user']),
			));
			
			if(!empty($users)) {
				$user=reset($users);
				$user=new WP_User($user->ID);
				$user->remove_role('inactive');
				$user->add_role('contributor');
				wp_set_auth_cookie($user->ID, true);
				ThemexCore::updateUserMeta($user->ID, 'activation_key', '');				
				
				wp_redirect(get_author_posts_url($user->ID));
				exit();
			}
		}
	}
	
	/**
	 * Resets password
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function resetUser($data) {
		global $wpdb, $wp_hasher;
		
		$success=false;		
		if(email_exists(sanitize_email($data['user_email']))) {
			$user=get_user_by('email', sanitize_email($data['user_email']));
			do_action('lostpassword_post');
			
			$login=$user->user_login;
			$email=$user->user_email;
			
			do_action('retrieve_password', $login);
			$allow=apply_filters('allow_password_reset', true, $user->ID);
			
			if(!$allow || is_wp_error($allow)) {
				ThemexInterface::$messages[]=__('Password recovery not allowed', 'makery');
			} else {
				$key=wp_generate_password(20, false);
				do_action('retrieve_password_key', $login, $key);
				
				if(empty($wp_hasher)){
					require_once ABSPATH.'wp-includes/class-phpass.php';
					$wp_hasher=new PasswordHash(8, true);
				}

				$hashed=time().':'.$wp_hasher->HashPassword($key);
				$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $login), array('%s'), array('%s'));
				
				$link=network_site_url('wp-login.php?action=rp&key='.$key.'&login='.rawurlencode($login), 'login');				
				$content=ThemexCore::getOption('email_password', 'Hi, %username%! To reset your password, visit the following link: %link%.');
				$keywords=array(
					'username' => $user->user_login,
					'link' => $link,
				);
				
				$content=themex_keywords($content, $keywords);
				if(themex_mail($email, __('Password Recovery', 'makery'), $content)) {
					ThemexInterface::$messages[]=__('Password reset link is sent', 'makery');
					$success=true;
				} else {
					ThemexInterface::$messages[]=__('Error sending email', 'makery');
				}
			}
		} else {
			ThemexInterface::$messages[]=__('Invalid email address', 'makery');		
		}
		
		ThemexInterface::renderMessages($success);	
		die();
	}
	
	/**
	 * Submits user message
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function submitMessage($ID, $data) {
		$user=intval(themex_value('user_id', $data));
		
		if(!empty($user)) {
			$message=sanitize_text_field(themex_value('message', $data));
			if(empty($message)) {
				ThemexInterface::$messages[]='"'.__('Message', 'makery').'" '.__('field is required', 'makery');
			}
			
			if(empty(ThemexInterface::$messages)) {
				$subject=__('New Message', 'makery');
				$content=ThemexCore::getOption('email_message', 'Sender: %user%<br />Message: %message%');
				
				$receiver=get_userdata($user);
				$sender=get_userdata($ID);				
				
				$keywords=array(
					'user' => '<a href="'.get_author_posts_url($sender->ID).'">'.$sender->user_login.'</a>',
					'message' => wpautop($message),
				);
				
				$content=themex_keywords($content, $keywords);
				themex_mail($receiver->user_email, $subject, $content, $sender->user_email);
				
				ThemexInterface::$messages[]=__('Message has been successfully sent', 'makery');
				ThemexInterface::renderMessages(true);
			} else {
				ThemexInterface::renderMessages();
			}
		}
		
		die();
	}
	
	/**
	 * Renders admin profile
     *
     * @access public
	 * @param mixed $user
     * @return void
     */
	public static function renderAdminProfile($user) {
		$profile=self::getProfile($user->ID);
		$out='<table class="form-table themex-profile"><tbody>';
		
		if(current_user_can('edit_users')) {
			$out.='<tr><th><label for="avatar">'.__('Profile Photo', 'makery').'</label></th>';
			$out.='<td><div class="themex-image-uploader">';
			$out.=get_avatar($user->ID);
			$out.=ThemexInterface::renderOption(array(
				'id' => 'avatar',
				'type' => 'uploader',
				'value' => '',
				'wrap' => false,				
			));
			$out.='</div></td></tr>';
		}
		
		ob_start();
		ThemexForm::renderData('profile', array(
			'edit' => true,
			'placeholder' => false,
			'before_title' => '<tr><th><label>',
			'after_title' => '</label></th>',
			'before_content' => '<td>',
			'after_content' => '</td></tr>',
		), $profile);
		$out.=ob_get_contents();
		ob_end_clean();
		
		$out.='<tr><th><label>'.__('Profile Text', 'makery').'</label></th><td>';		
		ob_start();
		ThemexInterface::renderEditor('description', themex_array('description', $profile));
		$out.=ob_get_contents();
		ob_end_clean();
		$out.='</td></tr>';
		
		$out.='</tbody></table>';		
		echo $out;
	}
	
	/**
	 * Updates admin profile
     *
     * @access public
	 * @param mixed $user
     * @return void
     */
	public static function updateAdminProfile($user) {
		global $wpdb;
		self::updateProfile($user, $_POST);
		
		if(current_user_can('edit_users') && isset($_POST['avatar']) && !empty($_POST['avatar'])) {
			$query="SELECT ID FROM ".$wpdb->posts." WHERE guid = '".esc_url($_POST['avatar'])."'";
			$avatar=$wpdb->get_var($query);
			
			if(!empty($avatar)) {
				ThemexCore::updateUserMeta($user, 'avatar', $avatar);
			}
		}
	}
	
	/**
	 * Renders user toolbar
     *
     * @access public
     * @return bool
     */
	public static function renderToolbar() {
		if(current_user_can('publish_posts') && get_user_option('show_admin_bar_front', get_current_user_id())!='false') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks profile page
     *
     * @access public
     * @return bool
     */
	public static function isProfile() {
		if(is_user_logged_in() && self::$data['active']['ID']==self::$data['current']['ID']) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks shop page
     *
     * @access public
     * @return bool
     */
	public static function isShop() {
		if(isset(self::$data['current']['links']['shop']) && self::$data['current']['links']['shop']['current']) {
			return true;
		}
		
		return false;
	}
}