<?php
/**
 * Themex Woo
 *
 * Handles WooCommerce data
 *
 * @class ThemexWoo
 * @author Themex
 */
 
class ThemexWoo {

	/** @var array Contains module data. */
	public static $data;
	
	/** @var mixed Contains plugin instance. */
	public static $woocommerce;
	
	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
		
		if(self::isActive()) {
		
			//get plugin instance
			self::$woocommerce=$GLOBALS['woocommerce'];
			
			//add authors support
			add_action('init', array(__CLASS__, 'addPosts'), 999);
			
			//update actions
			add_action('wp', array(__CLASS__, 'update'), 3);

			//order actions
			add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'addOrder'), 999);
			add_filter('woocommerce_paypal_ap_payment_args', array(__CLASS__, 'splitOrder'), 10, 2);			
			add_action('woocommerce_order_status_changed', array(__CLASS__, 'updateOrder'), 10, 3);
			
			//add checkout fields
			add_action('init', array(__CLASS__, 'addFields'), 999);
			
			//add custom columns
			add_filter('manage_edit-shop_order_columns', array(__CLASS__, 'addColumns'), 15);

			//enqueue scripts
			add_action('wp_enqueue_scripts', array(__CLASS__, 'addScripts'));
			
			//update cart actions
			add_filter('add_to_cart_fragments', array(__CLASS__, 'filterCart'));
			add_action('woocommerce_add_to_cart', array(__CLASS__, 'addCart'), 10, 1);
			add_action('woocommerce_cart_item_restored', array(__CLASS__, 'addCart'), 10, 1);
			add_action('woocommerce_thankyou', array(__CLASS__, 'removeCart'), 20);
			add_action('woocommerce_cart_item_removed', array(__CLASS__, 'removeCart'), 10);
			add_action('woocommerce_before_cart_item_quantity_zero', array(__CLASS__, 'removeCart'), 10);
			
			//shipping actions
			add_filter('woocommerce_package_rates', array(__CLASS__, 'updateShipping'), 100, 2);
			
			//set products limit
			add_filter('loop_shop_per_page', array(__CLASS__, 'updateProductsLimit'), 20);
			add_filter('woocommerce_output_related_products_args', array(__CLASS__, 'updateRelatedLimit'));
			add_action('woocommerce_after_single_product_summary', array(__CLASS__, 'updateUpsellLimit'), 15);
			
			//get product reviews
			add_filter('woocommerce_product_review_list_args', array(__CLASS__, 'getReviews'));
			
			//filter products
			add_filter('woocommerce_shortcode_products_query', array(__CLASS__, 'filterShortcodes'), 10);
			add_action('pre_get_posts', array(__CLASS__, 'filterProducts'));
			
			//remove actions
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
			remove_action('woocommerce_before_shop_loop', 'wc_print_notices', 10);
			remove_action('template_redirect', 'wc_disable_author_archives_for_customers');
			
			//add actions
			add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 10);
			add_action('woocommerce_single_product_summary', 'comments_template', 60);
		}
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
		
		//affiliate
		if(isset($_GET['ref'])) {
			self::setAffiliate($_GET['ref']);
		}
		
		//actions
		if(isset($data['woo_action'])) {
			$action=sanitize_title($data['woo_action']);
			$redirect=false;
			
			$ID=intval(themex_value('product_id', $data));
			$order=intval(themex_value('order_id', $data));
			$author=intval(get_post_field('post_author', $ID));
			$user=get_current_user_id();
			
			if((empty($ID) || $user==$author) && !ThemexCore::checkOption('shop_multiple')) {
				switch($action) {
					case 'update_product':
						self::updateProduct($ID, $data);
					break;
					
					case 'remove_product':
						self::removeProduct($ID, $user);
					break;
					
					case 'add_image':
						self::addImage($ID, themex_array('product_image', $_FILES));
						$redirect=true;
					break;
					
					case 'update_image':
						self::updateImage($ID, themex_array('product_image', $_FILES));
						$redirect=true;
					break;
					
					case 'remove_image':
						self::removeImage($ID, themex_value('image_id', $data));
					break;
				}
			}
			
			if(!empty($order)) {
				$author=intval(get_post_field('post_author', $order));
				
				if($user==$author) {
					switch($action) {
						case 'complete_order':
							self::completeOrder($order, $data);
						break;
					}
				}				
			}
			
			switch($action) {
				case 'update_cart':
					self::updateCart($data);
					$redirect=true;
				break;
			}
			
			if($redirect || empty(ThemexInterface::$messages)) {
				wp_redirect(themex_url());
				exit();
			}
		}
	}
	
	/**
	 * Gets products
     *
     * @access public
	 * @param int $user
	 * @param array $args
     * @return array
     */
	public static function getProducts($user, $args=array()) {
		$products=array();
		if(self::isActive()) {
			$products=get_posts(array_merge(array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'author' => $user,
			), $args));
		}
		
		return $products;
	}
	
	/**
	 * Queries products
     *
     * @access public	 
     * @return void
     */
	public static function queryProducts() {
		global $wp_query, $post;
		
		$limit=intval(themex_value('limit', $_GET, ThemexCore::getOption('products_per_page', 9)));
		$order=ThemexWoo::getSorting();
		
		$args=array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'paged' => themex_paged(),
			'posts_per_page' => $limit,
			'orderby' => $order['orderby'],
			'order' => $order['order'],
			'meta_key' => $order['meta_key'],
		);
		
		if(is_singular('shop')) {
			$args['author']=$post->post_author;
		}
		
		if(ThemexCore::checkOption('membership_free')) {
			$args['meta_query']=array(
				array(
					'key' => '_'.THEMEX_PREFIX.'hidden',
					'compare' => '!=',
					'value' => '1',
				),
			);
		}
		
		query_posts($args);
	}
	
	/**
	 * Filters products query
     *
     * @access public
	 * @param mixed $query
     * @return mixed
     */
	public static function filterProducts($query) {
		if(!is_admin() && $query->is_main_query() && (is_post_type_archive('product') || $query->is_tax('product_cat') || themex_search('product'))) {
			if(ThemexCore::checkOption('membership_free')) {
				$meta=$query->get('meta_query');
			
				$meta[]=array(
					'key' => '_'.THEMEX_PREFIX.'hidden',
					'compare' => '!=',
					'value' => '1',
				);
				
				$query->set('meta_query', $meta);
			}				
			
			if(themex_search('product')) {
				$author=intval(themex_array('search_author', $_GET));
				if(!empty($author)) {
					$query->set('author', $author);
				}
			}
		}
		
		return $query;
	}
	
	/**
	 * Filters shortcodes query
     *
     * @access public
	 * @param array $args
     * @return array
     */
	public static function filterShortcodes($args) {
		
		if(ThemexCore::checkOption('membership_free')) {
			$args['meta_query']=themex_array('meta_query', $args);
			$args['meta_query'][]=array(
				'key' => '_'.THEMEX_PREFIX.'hidden',
				'compare' => '!=',
				'value' => '1',
			);
		}

		return $args;
	}
	
	/**
	 * Gets product
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getProduct($ID) {
		$product=array();		
		if(self::isActive()) {
			if($ID==1) {
				$product=array(
					'ID' => '',
					'author' => '',
					'status' => 'draft',
					'form' => '',
					'title' => '',
					'content' => '',
					'type' => '',					
					'category' => '',
					'tags' => '',
					'regular_price' => '',
					'sale_price' => '',
					'shipping_class' => '',
					'stock' => '',
					'file' => '',
					'images' => array(),
					'attributes' => array(),
				);
			} else {
				$object=wc_get_product($ID);
				$data=get_post_meta($ID);
				
				if(!empty($object)) {
					$product['ID']=$ID;
					$product['object']=$object;
					$product['status']=$object->post->post_status;
					$product['author']=$object->post->post_author;
					
					$product['content']=$object->post->post_content;
					$product['title']=$object->post->post_title;
					if($object->post->post_title==__('Untitled', 'makery')) {
						$product['title']='';
					}
				
					$product['form']='simple';
					$forms=get_the_terms($ID, 'product_type');
					if(is_array($forms) && !empty($forms)) {
						$form=reset($forms);
						$product['form']=$form->slug;
					}
					
					$product['type']='physical';
					if($object->is_downloadable()) {
						$product['type']='virtual';
					}
					
					$product['category']=0;
					$categories=get_the_terms($ID, 'product_cat');
					if(is_array($categories) && !empty($categories)) {
						if(ThemexCore::checkOption('product_category')) {
							$product['category']=wp_list_pluck($categories, 'term_id');
						} else {
							$category=reset($categories);
							$product['category']=$category->term_id;
						}
					}
					
					$product['tags']='';
					$tags=get_the_terms($ID, 'product_tag');
					if(is_array($tags) && !empty($tags)) {
						$tags=wp_list_pluck($tags, 'name');
						$product['tags']=implode(', ', $tags);
					}
					
					$product['price']=$object->get_price_html();
					$product['regular_price']=themex_value('_regular_price', $data);
					$product['sale_price']=themex_value('_sale_price', $data);
					$product['stock']=$object->stock;
					
					$product['shipping_class']=0;
					$classes=get_the_terms($ID, 'product_shipping_class');
					if(is_array($classes) && !empty($classes)) {
						$class=reset($classes);
						$product['shipping_class']=$class->term_id;
					}
					
					$product['images']=array();
					if(!empty($object->product_image_gallery)) {
						$product['images']=explode(',', $object->product_image_gallery);
					}
					
					$product['file']='';
					$files=$object->get_files();
					if(!empty($files)) {
						$file=reset($files);
						$product['file']=$file['name'];
					}
					
					$attributes=self::getAttributes();
					$product['attributes']=array();
					foreach($object->get_attributes() as $attribute) {
						if(isset($attributes[$attribute['name']]) && $attribute['is_taxonomy']) {
							$field='names';
							if($attributes[$attribute['name']]['type']=='select') {
								$field='ids';
							}
							
							$terms=wc_get_product_terms($ID, $attribute['name'], array('fields' => $field));
							$product['attributes'][$attribute['name']]=reset($terms);
						}
					}
				}
			}
		}
	
		return $product;
	}
	
	/**
	 * Adds product
     *
     * @access public
     * @return bool
     */
	public static function addProduct() {
		$user=get_current_user_id();
		$args=array(
			'post_type' => 'product',
			'post_status' => 'draft',
			'post_author' => $user,
			'post_title' => __('Untitled', 'makery'),
		);
		
		$product=wp_insert_post($args);
		if(!empty($product)) {
			ThemexUser::countMembership($user);
			
			return $product;
		}
		
		return false;
	}

	/**
	 * Updates product
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateProduct($ID, $data) {
		global $wpdb;
		
		$redirect=false;
		if(empty($ID)) {
			$ID=self::addProduct();
			$redirect=true;
		}
		
		$args=array(
			'ID' => $ID,
		);
		
		//title
		$args['post_title']=themex_value('title', $data);
		if(empty($args['post_title'])) {
			ThemexInterface::$messages[]=__('Item name field is required', 'makery');
		}
		
		//category
		if(themex_taxonomy('product_cat')) {
			if(ThemexCore::checkOption('product_category')) {
				$categories=themex_array('category', $data);
				
				if(!empty($categories) && is_array($categories)) {
					wp_set_object_terms($ID, '', 'product_cat');
					
					foreach($categories as $category) {
						$term=get_term($category, 'product_cat');
						
						if(empty($term)) {
							ThemexInterface::$messages[]=__('This item category does not exist', 'makery');
						} else {
							wp_set_object_terms($ID, $term->name, 'product_cat', true);
						}
					}
				} else {
					ThemexInterface::$messages[]=__('Item category field is required', 'makery');
				}
			} else {
				$category=intval(themex_value('category', $data));
				
				if(empty($category)) {
					ThemexInterface::$messages[]=__('Item category field is required', 'makery');
				} else {
					$term=get_term($category, 'product_cat');
					
					if(empty($term)) {
						ThemexInterface::$messages[]=__('This item category does not exist', 'makery');
					} else {
						wp_set_object_terms($ID, $term->name, 'product_cat');
					}
				}
			}
		}
		
		//tags
		if(!ThemexCore::checkOption('product_tags')) {
			$tags=sanitize_text_field(themex_value('tags', $data));
			$tags=array_map('trim', explode(',', $tags));
			if(!empty($tags)) {
				$tag=reset($tags);
				
				if(empty($tag)) {
					wp_set_object_terms($ID, null, 'product_tag');
				} else {
					wp_set_object_terms($ID, $tags, 'product_tag');
				}
			}
		}
		
		//type
		$virtual='no';
		$ending='yes';
		if(themex_value('type', $data)=='virtual' || ThemexCore::getOption('product_type', 'all')=='virtual') {
			$virtual='yes';
			$ending='no';
		}
		
		update_post_meta($ID, '_virtual', $virtual);
		update_post_meta($ID, '_downloadable', $virtual);
		update_post_meta($ID, '_manage_stock', $ending);		
		update_post_meta($ID, '_visibility', 'visible');
		
		//price
		if(!ThemexCore::checkOption('product_price')) {
		
			if(isset($data['regular_price'])) {
				$regular_price=self::formatPrice(themex_value('regular_price', $data), false);
				
				update_post_meta($ID, '_price', $regular_price);
				update_post_meta($ID, '_regular_price', $regular_price);
			}
			
			if(isset($data['sale_price'])) {
				$sale_price=self::formatPrice(themex_value('sale_price', $data), false);
				if(empty($sale_price)) {
					$sale_price='';
				}		
				
				update_post_meta($ID, '_sale_price', $sale_price);
				if(!empty($sale_price)) {
					update_post_meta($ID, '_price', $sale_price);
				}
			}
		}
		
		//stock
		if($virtual=='no' && isset($data['stock'])) {
			$stock=intval(themex_value('stock', $data));
			$stock_status=$stock>0?'instock':'outofstock';
			
			update_post_meta($ID, '_stock', $stock);
			update_post_meta($ID, '_stock_status', $stock_status);
		}
		
		//shipping
		if(themex_taxonomy('product_shipping_class')) {
			$class=intval(themex_value('shipping_class', $data));
			$term=get_term($class, 'product_shipping_class');
			
			if(empty($class)) {
				wp_set_object_terms($ID, '', 'product_shipping_class');
			} else if(!is_wp_error($term)) {
				wp_set_object_terms($ID, $term->name, 'product_shipping_class');
			}
		}
		
		//file
		if($virtual=='yes' && isset($_FILES['file'])) {
			$files=get_post_meta($ID, '_downloadable_files', true);
			if(empty($files) || !is_array($files)) {
				$files=array();
			}
			
			$extensions=array_map('trim', explode(',', ThemexCore::getOption('product_extensions', 'zip')));
			$attachment=ThemexCore::addFile($_FILES['file'], $extensions, $ID);
			if(isset($attachment['ID']) && $attachment['ID']!=0) {
				$file=array_shift($files);
				if(is_array($file) && isset($file['file'])) {
					$current=$wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $file['file']));
					if(!empty($current)) {
						wp_delete_attachment($current, true);
					}
				}
				
				$files=array_merge(array(
					md5($attachment['guid']) => array(
						'name' => themex_filename($attachment['guid']),
						'file' => $attachment['guid'],
					),
				), $files);
				
				update_post_meta($ID, '_downloadable_files', $files);
			}
		}
		
		//attributes
		if(!ThemexCore::checkOption('product_attributes')) {
			$attributes=self::getAttributes();
			$options=get_post_meta($ID, '_product_attributes', true);
			if(empty($options) || !is_array($options)) {
				$options=array();
			}
			
			foreach($attributes as $attribute) {
				if(isset($data[$attribute['name']]) && taxonomy_exists($attribute['name'])) {
					$name=stripslashes(strip_tags($data[$attribute['name']]));
					$value=sanitize_title($data[$attribute['name']]);
					
					if($attribute['type']=='select') {
						$term=get_term_by('id', $value, $attribute['name']);
						
						$value='';
						if($term!==false) {
							$value=$term->slug;
						}
					}
					
					if($attribute['type']!='select' || (isset($attribute['options'][$value]) || $value=='')) {
						if($attribute['type']=='select') {
							wp_set_object_terms($ID, $value, $attribute['name']);
						} else {
							wp_set_object_terms($ID, $name, $attribute['name']);
						}
						
						if($value!='') {
							$options[$attribute['name']]=array(
								'name' => $attribute['name'],
								'value' => $value,
								'position' => '0',
								'is_visible' => 1,
								'is_variation' => 0,
								'is_taxonomy' => 1,
							);
						} else {
							unset($options[$attribute['name']]);
						}
					}
				}
			}
			
			update_post_meta($ID, '_product_attributes', $options);
		}
		
		//options		
		$option_names=themex_array('option_names', $data);
		$option_values=themex_array('option_values', $data);
		if(is_array($option_names) && is_array($option_values)) {
		
			$slugs=array();
			$options=get_post_meta($ID, '_product_attributes', true);
			if(empty($options) || !is_array($options)) {
				$options=array();
			}
			
			foreach($option_names as $index => $name) {
				if(isset($option_values[$index])) {
					$name=sanitize_text_field($name);
					$slug=sanitize_title($name);
					$value=implode('|', array_map('trim', explode(',', sanitize_text_field($option_values[$index]))));
					
					if(!empty($slug) && !empty($name) && !empty($value)) {
						$slugs[]=$slug;
						$options[$slug]=array(
							'name' => $name,
							'value' => $value,
							'position' => '0',
							'is_visible' => 0,
							'is_variation' => 1,
							'is_taxonomy' => 0,
						);
					}
				}
			}
			
			foreach($options as $slug => $option) {
				if(isset($option['is_taxonomy']) && empty($option['is_taxonomy']) && !in_array($slug, $slugs)) {
					unset($options[$slug]);
				}
			}
			
			update_post_meta($ID, '_product_attributes', $options);
		}
		
		//variations
		$variation_ids=themex_array('variation_ids', $data);
		$variation_stocks=themex_array('variation_stocks', $data);
		$variation_regular_prices=themex_array('variation_regular_prices', $data);
		$variation_sale_prices=themex_array('variation_sale_prices', $data);
		
		if(is_array($variation_ids) && is_array($variation_stocks) && is_array($variation_regular_prices) && is_array($variation_sale_prices)) {
		
			$variations=self::getVariations($ID, array('fields' => 'ids'));
			$new_variations=array();
			
			foreach($variation_ids as $index => $variation_id) {
				if(isset($variation_stocks[$index]) && isset($variation_regular_prices[$index]) && isset($variation_sale_prices[$index])) {
				
					$variation_id=intval($variation_id);
					$stock=intval($variation_stocks[$index]);
					$stock_status=$stock>0?'instock':'outofstock';
					$regular_price=self::formatPrice($variation_regular_prices[$index], false);
					$sale_price=$variation_sale_prices[$index];
					
					if(!empty($stock) && (empty($variation_id) || !in_array($variation_id, $variations))) {
						$variation_id=wp_insert_post(array(
							'post_type' => 'product_variation',
							'post_parent' => $ID,
							'post_status' => 'publish',
							'post_author' => 1,
							'post_title' => sprintf(__('Variation #%s', 'makery'), count($variations)+1),
						));
					}
					
					if(!empty($variation_id)) {
						$new_variations[]=$variation_id;
						if(($variation_index=array_search($variation_id, $variations))!==false) {
							unset($variations[$variation_index]);
						}
						
						update_post_meta($variation_id, '_manage_stock', 'yes');
						update_post_meta($variation_id, '_stock', $stock);
						update_post_meta($variation_id, '_stock_status', $stock_status);						
						
						update_post_meta($variation_id, '_regular_price', $regular_price);
						update_post_meta($variation_id, '_price', $regular_price);
						
						if($sale_price!='') {
							$sale_price=self::formatPrice($sale_price, false);
							update_post_meta($variation_id, '_sale_price', $sale_price);
							update_post_meta($variation_id, '_price', $sale_price);
						} else {
							update_post_meta($variation_id, '_sale_price', '');
						}
						
						$options=self::getOptions($ID);
						foreach($options as $name => $option) {
							$values=themex_array('variation_'.$name.'s', $data);
							$items=array_map('trim', explode(',', $option['value']));
							
							if(is_array($values) && isset($values[$index]) && (in_array($values[$index], $items) || $values[$index]=='')) {
								update_post_meta($variation_id, 'attribute_'.$name, $values[$index]);
							}
						}
					}
				}
			}			
			
			foreach($variations as $variation) {
				wp_delete_post($variation, true);
			}
			
			if(empty($new_variations)) {
				wp_set_object_terms($ID, 'simple', 'product_type');
			} else {
				WC_Product_Variable::sync($ID);
				WC_Product_Variable::sync_stock_status($ID);
				
				wp_set_object_terms($ID, 'variable', 'product_type');
			}
		}
		
		//content
		$args['post_content']=trim(themex_value('content', $data));
		$args['post_content']=wp_kses($args['post_content'], array(
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
		
		$success=false;
		if(empty(ThemexInterface::$messages)) {
			$status=get_post_status($ID);
			if($status=='draft') {
				$args['post_status']='pending';
				if(ThemexCore::checkOption('product_approve')) {
					$args['post_status']='publish';
				}
				
				$redirect=true;
			}
			
			ThemexInterface::$messages[]=__('Item has been successfully saved', 'makery');
			$_POST['success']=true;
			$success=true;
		}
		
		//defaults
		add_post_meta($ID, 'total_sales', '0', true);
		add_post_meta($ID, '_'.THEMEX_PREFIX.'hidden', '0', true);
		
		//update post
		wc_delete_product_transients($ID);
		wp_update_post($args);
		
		if($redirect) {
			ThemexInterface::setMessages($success);
			
			wp_redirect(ThemexCore::getURL('shop-product', $ID));
			exit();
		}
	}
	
	/**
	 * Filters cart product
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return bool
     */
	public static function filterProduct($ID, $data) {
		wc_clear_notices();

		$product=$data['product_id'];
		if(!empty($data['variation_id'])) {
			$product=$data['variation_id'];
		}
		
		$object=wc_get_product($product);
		$cart=self::getCart();
		
		$quantity=0;
		foreach($cart as $products) {
			foreach($products as $key=>$product) {
				if($key==$ID) {
					$quantity=$product['quantity'];
					break;
				}
			}
		}

		if($object->is_sold_individually() && $quantity>0) {
			return false;
		}

		if($object->managing_stock() && !$object->has_enough_stock($quantity+$data['quantity'])) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Removes product
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function removeProduct($ID, $user) {
		wp_update_post(array(
			'ID' => $ID,
			'post_status' => 'trash',
		));
		
		ThemexUser::countMembership($user);
		
		wp_redirect(ThemexCore::getURL('shop-products'));
		exit();
	}
	
	/**
	 * Checkouts product
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function checkoutProduct($ID) {
		self::$woocommerce->cart->empty_cart();
		self::$woocommerce->cart->add_to_cart($ID, 1);
		wp_redirect(self::$woocommerce->cart->get_checkout_url());
		
		exit();
	}
	
	/**
	 * Gets orders
     *
     * @access public
	 * @param int $user
	 * @param array $args
     * @return array
     */
	public static function getOrders($user, $args=array()) {
		$orders=array();
		if(self::isActive()) {
			$orders=get_posts(array_merge(array(
				'post_type' => 'shop_order',
				'post_status' => array('wc-processing', 'wc-completed', 'wc-refunded'),
				'posts_per_page' => -1,
				'fields' => 'ids',
				'author' => $user,
			), $args));
		}
		
		return $orders;
	}
	
	/**
	 * Gets order
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getOrder($ID) {
		$order=array();
		if(self::isActive()) {	
			$object=wc_get_order($ID);
			
			if(!empty($object)) {
				$order['ID']=$ID;
				$order['object']=$object;
				$order['number']=$object->get_order_number();
				$order['date']=$object->order_date;
				$order['author']=$object->post->post_author;
				
				$order['status']=$object->get_status();
				$order['condition']=wc_get_order_status_name($order['status']);
				$order['total']=$object->get_formatted_order_total();
				$order['totals']=$object->get_order_item_totals();
				
				$order['products']=$object->get_items();
				foreach($order['products'] as &$product) {
					$product['subtotal']=$object->get_formatted_line_subtotal($product);
					$product['meta']=new WC_Order_Item_Meta($product['item_meta']);
				}
				
				$order['customer']=$object->get_user_id();
				$order['email']=$object->billing_email;
				$order['phone']=$object->billing_phone;
				$order['billing_address']=$object->get_formatted_billing_address();
				$order['shipping_address']=$object->get_formatted_shipping_address();
				$order['customer_note']=$object->post->post_excerpt;
				$order['order_note']=self::getNote($order['ID']);
			}
		}
		
		return $order;
	}
	
	/**
	 * Adds order
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function addOrder($ID) {
		$order=wc_get_order($ID);
		$products=$order->get_items();
		
		//set author
		if(!empty($products)) {
			$product=reset($products);
			$post=get_post($product['product_id']);
			
			//set affiliate
			$referral=get_current_user_id();
			$affiliate=self::getAffiliate();
			if(!empty($affiliate) && $affiliate!=$referral) {
				ThemexCore::updatePostMeta($ID, 'affiliate', $affiliate);
				self::removeAffiliate();
				
				//send email				
				$content=ThemexCore::getOption('email_order_referral');
				if(!empty($content)) {
					$user=get_userdata($affiliate);					
					if($user!==false) {
						$subject=__('New Referral', 'makery');
					
						$keywords=array(
							'username' => $user->user_login,
							'order' => '<a href="'.ThemexCore::getURL('profile-referrals').'">'.$order->get_order_number().'</a>',
						);
						
						$content=themex_keywords($content, $keywords);
						themex_mail($user->user_email, $subject, $content);
					}
				}
			}
			
			if(!empty($post)) {
				wp_update_post(array(
					'ID' => $ID,
					'post_author' => $post->post_author,
				));
			}
		}
	}
	
	/**
	 * Updates order
     *
     * @access public
	 * @param int $ID
	 * @param string $old_status
	 * @param string $new_status
     * @return void
     */
	public static function updateOrder($ID, $old_status, $new_status) {
		$post=self::getPost($ID, 'membership');
		
		if(!empty($post)) {
			if($new_status=='completed') {
				ThemexUser::addMembership($post->post_author, $post->ID, false);
			} else {
				ThemexUser::removeMembership($post->post_author);
			}
		} else {
			$order=wc_get_order($ID);
			ThemexShop::updateBalance($order->post->post_author, array(
				'order' => $ID,
			));
			
			$affiliate=intval(ThemexCore::getPostMeta($ID, 'affiliate'));			
			if(!empty($affiliate) && $order->post->post_author!=$affiliate) {
				ThemexShop::updateBalance($affiliate);
			}
			
			if($new_status=='processing') {
			
				//send email				
				$content=ThemexCore::getOption('email_order_received');
				if(!empty($content)) {
					$user=get_userdata($order->post->post_author);
					$subject=__('New Order', 'makery');
					
					$keywords=array(
						'username' => $user->user_login,
						'order' => '<a href="'.ThemexCore::getURL('shop-order', $order->id).'">#'.$order->get_order_number().'</a>',
					);
					
					$content=themex_keywords($content, $keywords);
					themex_mail($user->user_email, $subject, $content);
				}
			}
		}
	}

	/**
	 * Splits order
     *
     * @access public
	 * @param array $args
	 * @param mixed $order
     * @return array
     */
	public static function splitOrder($args, $order) {
		if(!empty($order->post->post_author)) {
			$user=$order->post->post_author;
			$data=get_userdata($user);
			
			$rate_min=absint(ThemexCore::getOption('shop_rate_min', 50));
			$rate=absint(ThemexCore::getUserMeta($user, 'rate', $rate_min));
			
			$total=$order->get_total()-$order->get_total_refunded();
			$amount=round($total*$rate/100, 2);
			$commission=$total-$amount;
			
			$receiver=reset(reset($args['receiverList']));
			$email=$receiver['email'];
			
			$args['receiverList']=array(
				'receiver' => array(					
					array(
						'amount' => $amount,
						'email' => $data->user_email,
					),
					array(
						'amount' => $commission,
						'email' => $email,
					),
				),
			);
		}
		
		return $args;
	}
	
	/**
	 * Completes order
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function completeOrder($ID, $data) {
		if(self::isActive()) {
			$order=wc_get_order($ID);
			$status=$order->get_status();
			
			if($status=='processing') {
				if(!ThemexCore::checkOption('order_note')) {
					$note=trim(strip_tags(themex_value('order_note', $data)));
					if(!empty($note)) {
						self::addNote($ID, $note);
					}
				}
				
				$order->update_status('completed');			
				ThemexInterface::$messages[]=__('Order has been marked as completed', 'makery');
				$_POST['success']=true;
			}			
		}
	}
	
	/**
	 * Gets order note
     *
     * @access public
	 * @param int $ID
     * @return string
     */
	public static function getNote($ID) {
		$note='';
		
		if(self::isActive()) {
			$args=array(
				'post_id' => $ID,
				'approve' => 'approve',
				'type' => 'order_note',			
				'meta_key' => 'is_customer_note',
				'meta_value' => '1',
				'number' => 1,
			);

			remove_filter('comments_clauses', array('WC_Comments', 'exclude_order_comments'), 10, 1);
			$comments=get_comments( $args );
			add_filter('comments_clauses', array('WC_Comments', 'exclude_order_comments'), 10, 1);
			
			if(!empty($comments)) {
				$comment=reset($comments);
				$note=$comment->comment_content;
			}
		}
		
		return $note;
	}
	
	/**
	 * Adds order note
     *
     * @access public
	 * @param int $ID
	 * @param string $note
     * @return void
     */
	public static function addNote($ID, $note) {
		$args=array(
			'comment_post_ID' => $ID,
			'comment_author_url' => '',
			'comment_content' => $note,
			'comment_agent' => 'WooCommerce',
			'comment_type' => 'order_note',
			'comment_parent' => 0,
			'comment_approved' => 1,			
		);
		
		$comment=wp_insert_comment($args);
		add_comment_meta($comment, 'is_customer_note', 1);
	}
	
	/**
	 * Gets related post
     *
     * @access public
	 * @param int $ID
     * @return mixed
     */
	public static function getPost($ID, $type) {
		$post=array();
		$order=new WC_Order($ID);
		$products=$order->get_items();		
		
		if(!empty($products)) {
			$product=reset($products);
			$ID=$product['product_id'];
		}
		
		$posts=get_posts(array(
			'numberposts' => 1,
			'post_type' => $type,
			'meta_query' => array(
				array(
					'key' => '_'.THEMEX_PREFIX.'product',
					'value' => $ID,
				),
			),			
		));		

		if(!empty($posts)) {			
			$post=reset($posts);
			if(!empty($products)) {
				$post->post_author=$order->user_id;
			}
		}
		
		return $post;
	}
	
	/**
	 * Adds cart contents
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function addCart($ID) {
		$products=self::$woocommerce->cart->get_cart();
		
		if(!empty($products) && isset($products[$ID])) {
			$product=$products[$ID];
			$first=reset($products);					
			
			if($product['data']->post->post_author!=$first['data']->post->post_author) {
				if(self::filterProduct($ID, $product)) {
					$author=$product['data']->post->post_author;
					$cart=self::getCart();
					
					unset($product['data']);
					if(isset($cart[$author][$ID])) {
						$product['quantity']=$product['quantity']+$cart[$author][$ID]['quantity'];
					}
					
					$cart[$author][$ID]=$product;
					self::setCart($cart);
				}
				
				self::$woocommerce->cart->set_quantity($ID, 0);			
			}
		}
	}
	
	/**
	 * Sets cart contents
     *
     * @access public
	 * @param array $cart
     * @return void
     */
	public static function setCart($cart) {
		$cart=json_encode($cart);
		$expire=time()+86400*2;
				
		setcookie(THEMEX_PREFIX.'cart', $cart, $expire, COOKIEPATH, COOKIE_DOMAIN, false);
	}
	
	/**
	 * Gets cart contents
     *
     * @access public
     * @return array
     */
	public static function getCart() {
		$cart=array();
		if(isset($_COOKIE[THEMEX_PREFIX.'cart'])) {
			$cart=stripslashes($_COOKIE[THEMEX_PREFIX.'cart']);
			$cart=json_decode($cart, true);
		}
		
		return $cart;
	}
	
	/**
	 * Updates cart contents
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function updateCart($data) {
		$ID=intval(themex_value('cart_id', $data));
		$cart=self::getCart();
		
		if(isset($cart[$ID])) {
			if(isset($data['add'])) {
				$author=self::getUser();
				$products=self::$woocommerce->cart->get_cart();
				
				if(!empty($products)) {
					self::$woocommerce->cart->empty_cart();				
					foreach($cart[$ID] as $product) {
						self::$woocommerce->cart->add_to_cart($product['product_id'], $product['quantity'], $product['variation_id'], $product['variation'], $product);
					}
					
					foreach($products as &$product) {
						unset($product['data']);
					}
						
					$cart[$author]=$products;
				}
			}
			
			unset($cart[$ID]);
		}
		
		self::setCart($cart);
	}
	
	/**
	 * Removes cart contents
     *
     * @access public
     * @return void
     */
	public static function removeCart() {
		$cart=self::getCart();
		
		if(!empty($cart)) {
			$quantity=count(self::$woocommerce->cart->get_cart());
			
			$filter=current_filter();			
			if($filter=='woocommerce_before_cart_item_quantity_zero') {
				$quantity--;
			}
			
			if($quantity==0) {
				$first=reset($cart);				
				$current=key($cart);				
				
				self::$woocommerce->cart->empty_cart();
				unset($cart[$current]);
				self::setCart($cart);
				
				foreach($first as $product) {
					self::$woocommerce->cart->add_to_cart($product['product_id'], $product['quantity'], $product['variation_id'], $product['variation'], $product);
				}
			}
		}
	}
	
	/**
	 * Filters cart fragments
     *
     * @access public
	 * @param array $fragments
     * @return array
     */
	public static function filterCart($fragments) {
		$out='<div class="header-cart right"><a href="'.self::$woocommerce->cart->get_cart_url().'" class="cart-amount">';
		$out.='<span class="fa fa-shopping-cart"></span>&nbsp;'.self::$woocommerce->cart->get_cart_total().'</a>';
		$out.='<div class="cart-quantity">'.self::$woocommerce->cart->cart_contents_count.'</div></div>';

		$fragments['div.header-cart']=$out;	
		return $fragments;
	}
	
	/**
	 * Gets payment methods
     *
     * @access public
     * @return array
     */
	public static function getPaymentMethods() {
		$methods=array();
		
		if(self::isActive()) {
			$methods=self::$woocommerce->payment_gateways->get_available_payment_gateways();
		}
		
		return $methods;
	}
	
	/**
	 * Gets shipping methods
     *
     * @access public
     * @return array
     */
	public static function getShippingMethods() {
		$methods=array();
		
		if(self::isActive() && WC()->shipping()) {
			$methods=WC()->shipping->load_shipping_methods();
		}
		
		return $methods;
	}
	
	/**
	 * Gets shipping countries
     *
     * @access public
     * @return array
     */
	public static function getShippingCountries() {
		$countries=array();
		
		if(self::isActive()) {
			$countries=WC()->countries->get_shipping_countries();
		}
		
		return $countries;
	}
	
	/**
	 * Gets shipping classes
     *
     * @access public
     * @return array
     */
	public static function getShippingClasses() {
		$classes=array();
		$terms=get_terms('product_shipping_class', array(
			'hide_empty' => false,
		));
		
		if(is_array($terms)) {
			$classes=$terms;
		}
		
		return $classes;
	}
	
	/**
	 * Updates shipping rates
     *
     * @access public
	 * @param array $rates
	 * @param array $package
     * @return array
     */
	public static function updateShipping($rates, $package) {
		$products=$package['contents'];
		$product=reset($products);
		$total=$package['contents_cost'];
		$shipping=ThemexShop::getShipping(ThemexUser::getShop($product['data']->post->post_author));
		
		$country='';
		if(isset($package['destination']) && isset($package['destination']['country'])) {
			$country=$package['destination']['country'];
		}
		
		if(!empty($rates)) {
			foreach($rates as $key => $rate) {
				if(isset($shipping[$key]) && !empty($shipping[$key])) {
					$method=$shipping[$key];
					$enabled=themex_value('enabled', $method);
					$available=themex_value('availability', $method);
					$amount=themex_value('min_amount', $method);							
					$countries=themex_array('countries', $method, array());
					
					if(!is_array($countries)) {
						$countries=array();
					}
					
					if($enabled=='no' || (!empty($amount) && $total<$amount) || ($available=='specific' && !in_array($country, $countries))) {
						unset($rates[$key]);
					} else if($key=='local_delivery') {
						$default=round(floatval(themex_value('cost', $method)), 2);
						$rates[$key]->cost=$default;
					} else if(in_array($key, array('international_delivery', 'flat_rate'))) {
						$default=round(floatval(themex_value('default_cost', $method)), 2);								
						$costs=themex_array('costs', $method);
						$cost=0;
						
						foreach($products as $product) {
							$classes=get_the_terms($product['product_id'], 'product_shipping_class');
							if(is_array($classes) && !empty($classes)) {
								$class=reset($classes);
								$class=$class->slug;
								
								if(isset($costs[$class])) {
									$cost=$cost+round(floatval($costs[$class]), 2)*$product['quantity'];
								} else {
									$cost=$cost+$default*$product['quantity'];
								}
							} else {
								$cost=$cost+$default*$product['quantity'];
							}
						}
						
						if($key=='international_delivery' && (($available=='including' && !in_array($country, $countries)) || ($available=='excluding' && in_array($country, $countries)))) {
							unset($rates[$key]);
						} else {
							$rates[$key]->cost=$cost;
						}
					}
				}
			}
		}

		return $rates;
	}
	
	/**
	 * Checks shipping availability
     *
     * @access public
     * @return bool
     */
	public static function isShipping() {
		$shipping=ThemexCore::getOption('product_type')!='virtual';
		
		if(self::isActive()) {
			$shipping=$shipping && self::$woocommerce->shipping->enabled;
		}
		
		return $shipping;
	}
	
	/**
	 * Gets product options
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getOptions($ID) {
		$options=get_post_meta($ID, '_product_attributes', true);
		if(empty($options) || !is_array($options)) {
			$options=array();
		}
		
		foreach($options as $name => &$option) {
			$option['value']=str_replace('|', ',', $option['value']);			
			if(isset($option['is_taxonomy']) && !empty($option['is_taxonomy'])) {
				unset($options[$name]);
			}
		}
		
		return $options;
	}
	
	/**
	 * Gets product variations
     *
     * @access public
	 * @param int $ID
	 * @param array $args
     * @return array
     */
	public static function getVariations($ID, $args=array()) {
		$variations=array();
		
		if(!empty($ID)) {
			$variations=get_posts(array_merge(array(
				'post_parent' => $ID,
				'post_type' => 'product_variation',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_status' => 'publish',
				'numberposts' => -1,
			), $args));
		}

		return $variations;
	}
	
	/**
	 * Gets affiliate
     *
     * @access public
     * @return int
     */
	public static function getAffiliate() {
		$affiliate=intval(themex_value(THEMEX_PREFIX.'affiliate', $_COOKIE));
		
		return $affiliate;
	}
	
	/**
	 * Sets affiliate
     *
     * @access public
	 * @param string $login
     * @return void
     */
	public static function setAffiliate($login) {
		$login=sanitize_user($login);
		
		if(!empty($login) && !ThemexCore::checkOption('shop_referrals')) {
			$user=get_user_by('login', $login);
			$affiliate=intval(themex_value(THEMEX_PREFIX.'affiliate', $_COOKIE));
			
			if($user!==false && (empty($affiliate) || $user->ID!=$affiliate)) {
				$expire=time()+86400*10;
				setcookie(THEMEX_PREFIX.'affiliate', $user->ID, $expire, COOKIEPATH, COOKIE_DOMAIN, false);	
				
				$clicks=intval(ThemexCore::getUserMeta($user->ID, 'clicks'));
				ThemexCore::updateUserMeta($user->ID, 'clicks', $clicks+1);
			}
		}
	}
	
	/**
	 * Removes affiliate
     *
     * @access public
     * @return void
     */
	public static function removeAffiliate() {
		$expire=time()-3600;
		setcookie(THEMEX_PREFIX.'affiliate', 0, $expire, COOKIEPATH, COOKIE_DOMAIN, false);	
	}
	
	/**
	 * Gets referrals
     *
     * @access public
	 * @param int $user
	 * @param array $args
     * @return array
     */
	public static function getReferrals($user, $args=array()) {
		$orders=self::getOrders(null, array_merge(array(
			'meta_key' => '_'.THEMEX_PREFIX.'affiliate',
			'meta_value' => $user,
		), $args));
		
		return $orders;
	}
	
	/**
	 * Gets product reviews
     *
     * @access public
	 * @param array $args
     * @return array
     */
	public static function getReviews($args) {
		$args['type']='comment';
		
		return $args;
	}
	
	/**
	 * Gets cart user
     *
     * @access public
     * @return int
     */
	public static function getUser() {
		$user=0;
		$products=self::$woocommerce->cart->get_cart();
		
		if(!empty($products)) {
			$first=reset($products);
			$user=$first['data']->post->post_author;
		}
		
		return $user;
	}
	
	/**
	 * Adds product image
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function addImage($ID, $file) {
		if(empty($ID)) {
			$ID=self::addProduct();
		}
		
		$images=get_post_meta($ID, '_product_image_gallery', true);
		if(count(explode(',', $images))<10) {
			$attachment=ThemexCore::addFile($file, array(), $ID);		
			if(isset($attachment['ID']) && $attachment['ID']!=0) {
				if(!empty($images)) {
					$images.=','.$attachment['ID'];
				} else {
					$images=$attachment['ID'];
				}
				
				update_post_meta($ID, '_product_image_gallery', $images);
			}
		}
		
		wp_redirect(ThemexCore::getURL('shop-product', $ID));
		exit();
	}
	
	/**
	 * Updates product image
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function updateImage($ID, $file) {
		if(empty($ID)) {
			$ID=self::addProduct();
		}
		
		ThemexCore::updateImage($ID, $file);
		wp_redirect(ThemexCore::getURL('shop-product', $ID));
		exit();
	}
	
	/**
	 * Removes product image
     *
     * @access public
	 * @param int $ID
	 * @param int $image
     * @return void
     */
	public static function removeImage($ID, $image) {
		wp_delete_attachment($image, true);
		$images=get_post_meta($ID, '_product_image_gallery', true);
		$images=explode(',', $images);
		
		$key=array_search($image, $images);
		if($key!==false) {
			unset($images[$key]);
			$images=implode(',', $images);
			update_post_meta($ID, '_product_image_gallery', $images);
		}
	}

	/**
	 * Gets product relations
     *
     * @access public
	 * @param int $ID
	 * @param bool $extended
     * @return array
     */
	public static function getRelations($shops) {
		global $wpdb;
		
		$relations=array();
		if(!empty($shops)) {
			$shops=themex_implode($shops);
			$results=$wpdb->get_results("
				SELECT post_author FROM {$wpdb->posts} 
				WHERE post_status = 'publish' 
				AND post_type = 'shop' 
				AND ID IN (".$shops.") 
			");
			
			$authors=wp_list_pluck($results, 'post_author');
			$authors=themex_implode($authors);			
			$results=$wpdb->get_results("
				SELECT ID FROM {$wpdb->posts} 
				WHERE post_status = 'publish' 
				AND post_type = 'product' 
				AND post_author IN (".$authors.") 
				ORDER BY post_date DESC
			");
				
			$relations=wp_list_pluck($results, 'ID');
		}
		
		return $relations;
	}
	
	/**
	 * Gets product attributes
     *
     * @access public
     * @return array
     */
	public static function getAttributes() {
		$attributes=array();
		
		if(self::isActive()) {
			$taxonomies=wc_get_attribute_taxonomies();
			
			foreach($taxonomies as $taxonomy) {
				$attribute['type']=$taxonomy->attribute_type;
				$attribute['name']=wc_attribute_taxonomy_name($taxonomy->attribute_name);
				
				$attribute['label']=$taxonomy->attribute_label;			
				if(empty($attribute['label'])) {
					$attribute['label']=$taxonomy->attribute_name;
				}
				
				$attribute['options']=array();
				$terms=get_terms($attribute['name'], 'orderby=name&hide_empty=0');
				if(!empty($terms)) {
					foreach($terms as $term) {
						$attribute['options'][$term->slug]=$term->name;
					}
				}
				
				$attributes[$attribute['name']]=$attribute;
			}
		}
		
		return $attributes;
	}
	
	/**
	 * Gets formatted price
     *
     * @access public
	 * @param int $amount
     * @return string
     */
	public static function getPrice($amount) {
		$price=intval($amount);
		if(self::isActive()) {
			$price=wc_price($amount);
		}		
		
		return $price;
	}
	
	/**
	 * Formats product price
     *
     * @access public
	 * @param int $amount
     * @return mixed
     */
	public static function formatPrice($amount, $format=true) {
		$sep=get_option('woocommerce_price_decimal_sep');
		if(empty($sep)) {
			$sep='.';
		}
		
		if($format) {			
			$price=str_replace('.', $sep, $amount);
		} else {
			$price=str_replace($sep, '.', $amount);
			$price=round(abs(floatval($price)), 2);
		}
		
		return $price;
	}
	
	/**
	 * Gets product period
     *
     * @access public
	 * @param int $ID
	 * @param int $period
     * @return string
     */
	public static function getPeriod($ID, $period) {
		$price='';
		
		if(self::isActive()) {
			$product=get_product($ID);

			if(!is_wp_error($product) && is_object($product)) {
				$price=$product->get_price_html();
				
				if(!empty($price) && !empty($period) && $product->product_type!='subscription') {
					$price.=' / '.themex_period($period);
				}
			}
		}
		
		return $price;
	}
	
	/**
	 * Gets products rating
     *
     * @access public
	 * @param int $user
     * @return array
     */
	public static function getRating($user) {
		$data=array(
			'rating' => 0,
			'ratings' => 0,
		);
		
		if(self::isActive()) {
			$products=self::getProducts($user);
			$count=0;
			
			foreach($products as $product) {
				$object=wc_get_product($product);
				$rating=$object->get_average_rating();
				
				if(!empty($rating)) {
					$count++;
					
					$data['rating']=$data['rating']+$rating;
					$data['ratings']=$data['ratings']+$object->get_rating_count();
				}				
			}
			
			if($count>0) {
				$data['rating']=$data['rating']/$count;
			}
		}
		
		return $data;
	}
	
	/**
	 * Gets custom template
     *
     * @access public
	 * @param string $name
     * @return void
     */
	public static function getTemplate($name) {
		if(self::isActive()) {
			wc_get_template($name);
		}
	}
	
	/**
	 * Gets custom template part
     *
     * @access public
	 * @param string $type
	 * @param string $name
     * @return void
     */
	public static function getTemplatePart($type, $name) {
		if(self::isActive()) {
			wc_get_template_part($type, $name);
		}
	}
	
	/**
	 * Updates products limit
     *
     * @access public
     * @return int
     */
	public static function updateProductsLimit() {
		$limit=intval(themex_value('limit', $_GET, ThemexCore::getOption('products_per_page', 9)));
		
		return $limit;
	}
	
	/**
	 * Updates related limit
     *
     * @access public
	 * @param array $args
     * @return array
     */
	public static function updateRelatedLimit($args) {
		$args['columns']=4;
		$args['posts_per_page']=ThemexCore::getOption('product_related_number', 4);
		
		return $args;
	}
	
	/**
	 * Updates upsell limit
     *
     * @access public
     * @return void
     */
	public static function updateUpsellLimit() {
		$columns=4;
		$limit=ThemexCore::getOption('product_upsell_number', 4);
		
		woocommerce_upsell_display($limit, $columns);
	}
	
	/**
	 * Adds custom scripts
     *
     * @access public
     * @return void
     */	
	public static function addScripts() {
		$path=str_replace(array('http:', 'https:'), '', WC()->plugin_url()).'/assets/';
		
		if(get_query_var('shop-shipping')) {
			wp_enqueue_script('chosen');
			wp_enqueue_style('woocommerce_chosen_styles', $path.'css/chosen.css');
		}		
	}
	
	/**
	 * Adds posts support
     *
     * @access public
     * @return void
     */	
	public static function addPosts() {
		$types=array('product', 'shop_order');
		
		foreach($types as $type) {
			add_post_type_support($type, 'author');
		}
	}
	
	/**
	 * Adds checkout fields
     *
     * @access public
     * @return void
     */
	public static function addFields() {
		$fields=WC()->countries->get_address_fields('', 'billing_');
		
		unset($fields['billing_first_name']);
		unset($fields['billing_last_name']);
		unset($fields['billing_city']);
		unset($fields['billing_country']);
		unset($fields['billing_address_2']);
		unset($fields['billing_email']);
		
		foreach($fields as $name=>&$field) {
			$field['name']=$name;
			$field['prefix']=false;
		}
		
		ThemexCore::$components['forms']['address']=$fields;
	}
	
	/**
	 * Adds custom columns
     *
     * @access public
	 * @param array $columns
     * @return array
     */
	public static function addColumns($columns) {
		$columns['author']=__('Author', 'makery'); 
		
		return $columns;
	}
	
	/**
	 * Gets sorting options
     *
     * @access public
     * @return array
     */
	public static function getSorting() {
		$order=array(
			'orderby' => '',
			'order' => '',
			'meta_key' => '',
		);
		
		if(self::isActive()) {
			$query=new WC_Query();
			$order=$query->get_catalog_ordering_args();
		}		
		
		return $order;
	}
	
	/**
	 * Checks plugin activity
     *
     * @access public
     * @return bool
     */
	public static function isActive() {
		if(class_exists('Woocommerce')) {
			return true;
		}
		
		return false;
	}
}