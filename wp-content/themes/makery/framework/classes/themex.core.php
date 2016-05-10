<?php
/**
 * Themex Core
 *
 * Inits modules and components
 *
 * @class ThemexCore
 * @author Themex
 */

class ThemexCore {
	
	/** @var array Contains an array of modules. */
	public static $modules;

	/** @var array Contains an array of components. */
	public static $components;
	
	/** @var array Contains an array of options. */
	public static $options;
	
	/**
	 * Inits modules and components, adds edit actions
     *
     * @access public
	 * @param array $config
     * @return void
     */
	public function __construct($config) {

		//set modules
		self::$modules=$config['modules'];
		
		//set components
		self::$components=$config['components'];

		//set options
		self::$options=$config['options'];		

		//init modules
		$this->initModules();

		//init components
		$this->initComponents();

		//save options action
		add_action('wp_ajax_themex_save_options', array(__CLASS__, 'saveOptions'));
		
		//reset options action
		add_action('wp_ajax_themex_reset_options', array(__CLASS__, 'resetOptions'));

		//save post action
		add_action('save_post', array(__CLASS__, 'savePost'));
		
		//filter user relations
		add_filter('comments_clauses', array($this, 'filterUserRelations'));
		
		//activation hook
		add_action('init', array(__CLASS__, 'activate'));
	}
	
	/**
	 * Checks PHP version and redirects to the options page
     *
     * @access public
     * @return void
     */
	public static function activate() {
		global $pagenow, $wp_rewrite;		

		if ($pagenow=='themes.php' && isset($_GET['activated'])) {
			if(version_compare(PHP_VERSION, '5', '<')) {
				switch_theme('twentyten', 'twentyten');
				die();
			}
			
			$wp_rewrite->set_permalink_structure('/%category%/%postname%/');
			$wp_rewrite->flush_rules();
			
			//set defaults
			update_option('users_can_register', '1');
			
			wp_redirect(admin_url('admin.php?page=theme-options&activated=true'));
			exit;
		}
	}
	
	/**
	 * Requires and inits modules
     *
     * @access public
     * @return void
     */
	public function initModules() {
		
		foreach(self::$modules as $module) {
		
			//require class
			$file=substr(strtolower(implode('.', preg_split('/(?=[A-Z])/', $module))), 1);
			require_once(THEMEX_PATH.'classes/'.$file.'.php');
			
			//init module
			if(method_exists($module, 'init')) {
				call_user_func(array($module, 'init'));
			}
		}
	}
	
	/**
	 * Adds actions to init components
     *
     * @access public
     * @return void
     */
	public function initComponents() {
		
		//add supports
		add_action('after_setup_theme', array($this, 'supports'));
		
		//add rewrite rules
		add_action('after_setup_theme', array($this, 'rewrite_rules'));
		
		//add user roles
		add_action('init', array($this, 'user_roles'));
		
		//register custom menus
		add_action('init', array($this, 'custom_menus'));
		
		//add image sizes
		add_action('init', array($this, 'image_sizes'));
		
		//enqueue admin scripts and styles
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'admin_styles'), 99);
		
		//enqueue user scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'user_scripts'));	
		add_action('wp_enqueue_scripts', array($this, 'user_styles'), 99);	
		
		//register sidebars and widgets
		add_action('widgets_init', array($this, 'widget_areas'));
		add_action('widgets_init', array($this, 'widgets'));
		
		//register custom post types
		add_action('init', array($this, 'post_types'));
		
		//register taxonomies
		add_action('init', array($this, 'taxonomies'));

		//add meta boxes
		add_action('admin_menu', array($this, 'meta_boxes'));		
	}
	
	/**
	 * Inits component on action
     *
     * @access public
     * @return void
     */
	public function __call($component, $args)	{
	
		if(isset(self::$components[$component])) {
			foreach(self::$components[$component] as $item) {
			
				switch($component) {
				
					case 'supports':
						add_theme_support($item);
					break;
					
					case 'rewrite_rules':
						self::rewriteRule($item);
					break;
				
					case 'user_roles':
						add_role($item['role'], $item['name'], $item['capabilities']);
					break;
					
					case 'custom_menus':
						register_nav_menu( $item['slug'], $item['name'] );
					break;
					
					case 'image_sizes':
						add_image_size($item['name'], $item['width'], $item['height'], $item['crop']);
					break;					
					
					case 'admin_scripts':					
						self::enqueueScript($item);
					break;					
					
					case 'admin_styles':
						self::enqueueStyle($item);
					break;
					
					case 'user_scripts':					
						self::enqueueScript($item);
					break;
					
					case 'user_styles':
						self::enqueueStyle($item);
					break;
					
					case 'widgets':
						self::registerWidget($item);
					break;
					
					case 'widget_areas':
						register_sidebar($item);
					break;
					
					case 'post_types':
						register_post_type($item['id'], $item);
					break;
					
					case 'taxonomies':
						register_taxonomy($item['taxonomy'], $item['object_type'], $item['settings']);
					break;
					
					case 'meta_boxes':
						add_meta_box($item['id'], $item['title'], array('ThemexInterface', 'renderMetabox'), $item['page'], $item['context'], $item['priority'], array('ID' => $item['id']));
					break;					
				}
			}
		}
	}
	
	/**
	 * Saves theme options
     *
     * @access public
     * @return void
     */
	public static function saveOptions() {
	
		parse_str($_POST['options'], $options);
			
		//save options
		if(current_user_can('manage_options')) {
			foreach(self::$options as $option) {		
				if(isset($option['id'])) {
				
					$option['index']=$option['id'];
					if($option['type']!='module') {
						$option['index']=THEMEX_PREFIX.$option['id'];
					}
			
					if(!isset($options[$option['index']])) {
						$options[$option['index']]='false';
					}
					
					self::updateOption($option['id'], themex_stripslashes($options[$option['index']]));
					
					if($option['type']=='module' && method_exists($option['id'], 'saveOptions')) {
						call_user_func(array($option['id'], 'saveOptions'), $options[$option['index']]);
					}
				}
			}
		}
		
		_e('All changes have been saved','makery');
		die();		
	}
	
	/**
	 * Resets theme options
     *
     * @access public
     * @return void
     */
	public static function resetOptions() {	
	
		if(current_user_can('manage_options')) {		
			//delete options
			foreach(self::$options as $option) {
				if(isset($option['id'])) {
					self::deleteOption($option['id']);
				}
			}
			
			//delete modules
			foreach(self::$modules as $module) {
				self::deleteOption($module);
			}
		}
		
		_e('All options have been reset','makery');
		die();
	}
	
	/**
	 * Saves post meta
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function savePost($ID) {
		
		global $post;

		//check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $ID;
		}

		//verify nonce
		if (isset($_POST['themex_nonce']) && !wp_verify_nonce($_POST['themex_nonce'], $ID)) {
			return $ID;
		}
		
		//check permissions
		if (isset($_POST['post_type']) && $_POST['post_type']=='page') {
			if (!current_user_can('edit_page', $ID)) {
				return $ID;
			}
		} else if (!current_user_can('edit_post', $ID)) {
			return $ID;
		}
		
		//save post meta
		if(isset(self::$components['meta_boxes']) && isset($post)) {
			foreach(self::$components['meta_boxes'] as $meta_box) {
				if($meta_box['page']==$post->post_type) {
					foreach ($meta_box['options'] as $option) {
						if($option['type']=='module') {
							if(isset($option['slug'])) {
								call_user_func(array(str_replace(THEMEX_PREFIX, '', $option['id']), 'saveData'), $option['slug']);
							} else {
								call_user_func(array(str_replace(THEMEX_PREFIX, '', $option['id']), 'saveData'));
							}
						} else if(isset($_POST['_'.THEMEX_PREFIX.$option['id']])) {
							self::updatePostMeta($ID, $option['id'], themex_stripslashes($_POST['_'.THEMEX_PREFIX.$option['id']]));								
						}
					}
				}
			}
		}				
	}
	
	/**
	 * Enqueues script
     *
     * @access public
	 * @param array $args
     * @return void
     */
	public static function enqueueScript($args) {

		if(isset($args['uri'])) {
			$args['deps']=themex_array('deps', $args, array());
			wp_enqueue_script($args['name'], $args['uri'], $args['deps'], null, true);			
		} else {
			wp_enqueue_script($args['name']);
		}
		
		if(isset($args['options'])) {
			wp_localize_script($args['name'], 'options', $args['options']);
		}
	}
	
	/**
	 * Enqueues style
     *
     * @access public
	 * @param array $args
     * @return void
     */
	public static function enqueueStyle($args) {
		if(isset($args['uri'])) {
			wp_enqueue_style($args['name'], $args['uri']);
		} else {
			wp_enqueue_style($args['name']);
		}
	}
	
	/**
	 * Registers widget
     *
     * @access public
	 * @param string $name
     * @return void
     */
	public static function registerWidget($name) {
		
		if(class_exists($name)) {
			unregister_widget($name);
		} else {
			$file=substr(strtolower(implode('.', preg_split('/(?=[A-Z])/', $name))), 1);
			require_once(THEMEX_PATH.'classes/widgets/'.$file.'.php');
			register_widget($name);
		}
	}
	
	/**
	 * Rewrites URL rule
     *
     * @access public
	 * @param array $rule
     * @return void
     */
	public static function rewriteRule($rule) {
		global $wp_rewrite;
		global $wp;
		
		$wp->add_query_var($rule['name']);
		
		if(isset($rule['replace']) && $rule['replace']) {
			$wp_rewrite->$rule['rule']=$rule['rewrite'];
		} else {			
			add_rewrite_rule($rule['rule'], $rule['rewrite'], $rule['position']);
		}		
	}
	
	/**
	 * Gets rewrite rule
     *
     * @access public
	 * @param string $rule
     * @return bool
     */
	public static function getRewriteRule($rule) {
		$rule=self::$components['rewrite_rules'][$rule]['name'];
		$value=get_query_var($rule);
		
		return $value;
	}
	
	/**
	 * Gets page URL
     *
     * @access public
	 * @param string $name
	 * @param int $value
     * @return string
     */
	public static function getURL($rule, $value=1) {
		global $wp_rewrite;
		
		$url=$wp_rewrite->get_page_permastruct();
		$rule=ThemexCore::$components['rewrite_rules'][$rule];
		
		$slug='';
		if(isset($rule['name'])) {
			$slug=$rule['name'];
		}
		
		if(!empty($url)) {
			$url=home_url(str_replace('%pagename%', $slug, $url));			
			if(isset($rule['dynamic']) && $rule['dynamic']) {
				$url.='/'.$value;
			}
		} else {
			$url=home_url('?'.$slug.'='.$value);
		}
		
		return $url;
	}
	
	/**
	 * Gets prefixed option
     *
     * @access public
	 * @param string $ID
	 * @param mixed $default
     * @return mixed
     */
	public static function getOption($ID, $default='') {
		$option=get_option(THEMEX_PREFIX.$ID);
		if(($option===false || $option=='') && $default!='') {
			return $default;
		}
		
		return $option;
	}
	
	/**
	 * Updates prefixed option
     *
     * @access public
	 * @param string $ID
	 * @param string $value
     * @return bool
     */
	public static function updateOption($ID, $value) {
		return update_option(THEMEX_PREFIX.$ID, $value);
	}
	
	/**
	 * Deletes prefixed option
     *
     * @access public
	 * @param string $ID
     * @return bool
     */
	public static function deleteOption($ID) {
		return delete_option(THEMEX_PREFIX.$ID);
	}
	
	/**
	 * Checks prefixed option
     *
     * @access public
	 * @param string $ID
     * @return bool
     */
	public static function checkOption($ID) {
		$option=self::getOption($ID);		
		if($option=='true') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets prefixed post meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getPostMeta($ID, $key, $default='') {
		$meta=get_post_meta($ID, '_'.THEMEX_PREFIX.$key, true);
		
		if($meta=='' && ($default!='' || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed post meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updatePostMeta($ID, $key, $value) {
		return update_post_meta($ID, '_'.THEMEX_PREFIX.$key, $value);
	}
	
	/**
	 * Gets prefixed user meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getUserMeta($ID, $key, $default='') {
		$meta=get_user_meta($ID, '_'.THEMEX_PREFIX.$key, true);
		if($meta=='' && ($default!='' || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed user meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updateUserMeta($ID, $key, $value) {
		$result=false;
		
		if($value=='') {
			$result=delete_user_meta($ID, '_'.THEMEX_PREFIX.$key);
		} else {
			$result=update_user_meta($ID, '_'.THEMEX_PREFIX.$key, $value);
		}
		
		return $result;
	}
	
	/**
	 * Gets relations
     *
     * @access public
	 * @param string $select
	 * @param string $where
	 * @param string $table
	 * @param bool $single
     * @return array
     */
	public static function getRelations($select, $where, $table, $single=false) {
		global $wpdb;
		
		$query="
			SELECT CAST(".$select." AS UNSIGNED) as ".$select." FROM ".$table." 
			WHERE 1=1 
		";
		
		foreach($where as $field => $value) {
			$query.="AND ".$field." IN (".$value.") ";
		}
		
		if($single) {
			$query.="LIMIT 1 ";
		}

		$relations=$wpdb->get_results($query, ARRAY_A);
		$relations=wp_list_pluck($relations, $select);
		
		if($single) {
			$relations=intval(reset($relations));
		}
		
		return $relations;
	}
	
	/**
	 * Gets post relations
     *
     * @access public
	 * @param mixed $ID
	 * @param mixed $related
	 * @param mixed $type
	 * @param bool $single
     * @return array
     */
	public static function getPostRelations($ID, $related, $type, $single=false) {
		global $wpdb;
		
		if($single && $ID!=0 && $related==0 && !is_array($ID)) {
			$relations=intval(self::getPostMeta($ID, $type));
		} else {
			$select='meta_value';
			$where['post_id']=themex_implode($ID);
			$where['meta_key']=themex_implode($type, '_');
			$where['meta_value']=themex_implode($related);
			
			if($ID==0) {
				$select='post_id';
				unset($where['post_id']);
			} else if($related==0) {
				unset($where['meta_value']);
			}
			
			$relations=self::getRelations($select, $where, $wpdb->postmeta, $single);
		}
		
		return $relations;
	}
	
	/**
	 * Gets user relations
     *
     * @access public
	 * @param mixed $ID
	 * @param mixed $related
	 * @param mixed $type
	 * @param bool $single
     * @return array
     */
	public static function getUserRelations($ID, $related, $type, $single=false) {
		global $wpdb;
		
		$select='comment_content';
		$where['user_id']=themex_implode($ID);
		$where['comment_post_ID']=themex_implode($related);
		$where['comment_type']=themex_implode($type, 'user_');
		
		if($ID==0) {
			$select='user_id';
			unset($where['user_id']);
		} else if($related==0) {
			$select='comment_post_ID';
			unset($where['comment_post_ID']);
		}
		
		return self::getRelations($select, $where, $wpdb->comments, $single);
	}
	
	/**
	 * Filters user relations
     *
     * @access public
	 * @param string $query
     * @return string
     */
	public static function filterUserRelations($query) {
		if(isset($query['where'])) {
			$query['where'].=" AND comment_type NOT LIKE 'user_%' ";
		}

        return $query;
	}

	/**
	 * Adds user relation
     *
     * @access public
	 * @param int $ID
	 * @param int $related
	 * @param string $type
	 * @param string $value
     * @return void
     */
	public static function addUserRelation($ID, $related, $type, $value='') {
		$ID=intval($ID);
		$related=intval($related);
		$type='user_'.sanitize_key($type);
		$value=sanitize_text_field($value);
		
		if(is_user_logged_in() && $ID==get_current_user_id()) {
			$user=wp_get_current_user();
		} else {
			$user=get_userdata($ID);
		}
		
		wp_insert_comment(array(
			'comment_author' => $user->user_login,
			'comment_author_email' => $user->user_email,			
			'user_id' => $ID,
			'comment_post_ID' => $related,
			'comment_type' => $type,
			'comment_content' => $value,
		));
	}
	
	/**
	 * Removes user relation
     *
     * @access public
	 * @param int $ID
	 * @param int $related
	 * @param string $type
     * @return void
     */
	public static function removeUserRelation($ID, $related, $type) {
		global $wpdb;
		
		$query="
			DELETE FROM ".$wpdb->comments." 
			WHERE user_id = ".intval($ID)." 
			AND comment_type = 'user_".sanitize_key($type)."' 
		";
		
		if($related!=0) {
			$query.="AND comment_post_ID = ".intval($related)." ";
		}
		
		$wpdb->query($query);
	}
	
	/**
	 * Uploads file
     *
     * @access public
	 * @param array $file
	 * @param array $exts
	 * @param int $post
     * @return int
     */
	public static function addFile($file, $exts=array(), $post=0) {
		require_once(ABSPATH.'wp-admin/includes/media.php');
		require_once(ABSPATH.'wp-admin/includes/image.php');
		
		$attachment=array(
			'ID' => 0,
		);
		
		$slug='file';
		if(empty($exts)) {
			$exts=array('jpg', 'jpeg', 'png');
			$slug='image';
		}
		
		foreach($exts as $ext) {
			$exts[]=strtolower($ext);
			$exts[]=strtoupper($ext);
		}

		if(!empty($file['name'])) {
			$uploads=wp_upload_dir();
			$filetype=wp_check_filetype($file['name'], null);
			$filename=wp_unique_filename($uploads['path'], $slug.'-1.'.$filetype['ext']);
			$filepath=$uploads['path'].'/'.$filename;			
			
			//validate file
			if (!in_array($filetype['ext'], array_unique($exts))) {
				ThemexInterface::$messages[]=__('Files with this extension are not allowed', 'makery');
			} else if(move_uploaded_file($file['tmp_name'], $filepath)) {
				
					//upload image
					$attachment=array(
						'guid' => $uploads['url'].'/'.$filename,
						'post_mime_type' => $filetype['type'],
						'post_title' => sanitize_title(current(explode('.', $filename))),
						'post_content' => '',
						'post_status' => 'inherit',
						'post_author' => get_current_user_id(),
					);
					
					//add image
					$attachment['ID']=wp_insert_attachment($attachment, $attachment['guid'], $post);
					update_post_meta($attachment['ID'], '_wp_attached_file', substr($uploads['subdir'], 1).'/'.$filename);
					
					//add thumbnails
					$metadata=wp_generate_attachment_metadata($attachment['ID'], $filepath);
					wp_update_attachment_metadata($attachment['ID'], $metadata);
			
			} else {
				ThemexInterface::$messages[]=__('This file is too large for uploading','makery');
			}
		}
		
		return $attachment;
	}
	
	/**
	 * Gets image
     *
     * @access public
	 * @param int $ID
	 * @param int $size
	 * @param string $default
     * @return string
     */
	public static function getImage($ID, $size, $default) {
		$current=get_post_thumbnail_id($ID);
		$url=wp_get_attachment_url($current);
		$image=$default;
		
		if($url!==false) {
			$thumb=themex_resize($url, $size, $size);
			if(!empty($thumb)) {
				$image=$thumb;
			}
		}
		
		$out='<img src="'.$image.'" alt="" />';
		return $out;
	}
	
	/**
	 * Updates image
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function updateImage($ID, $file) {
		if(!empty($ID)) {
			$current=get_post_thumbnail_id($ID);
			wp_delete_attachment($current, true);
			
			$attachment=ThemexCore::addFile($file, array(), $ID);
			if(isset($attachment['ID']) && $attachment['ID']!=0) {
				add_post_meta($ID, '_thumbnail_id', $attachment['ID']);
			}
		}		
	}
}