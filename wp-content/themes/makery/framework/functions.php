<?php
/**
 * Removes slashes
 *
 * @param string $string
 * @return string
 */
function themex_stripslashes($string) {
 if(!is_array($string)) {
  return stripslashes(stripslashes($string));
 }
 
 return $string; 
}

/**
 * Gets page number
 *
 * @return int
 */
function themex_paged() {
 $paged=get_query_var('paged')?get_query_var('paged'):1;
 $paged=(get_query_var('page'))?get_query_var('page'):$paged;
 
 return $paged;
}

/**
 * Checks search page
 *
 * @param string $type
 * @return bool
 */
function themex_search($type) {
 if(isset($_GET['s']) && ((isset($_GET['post_type']) && $_GET['post_type']==$type) || (!isset($_GET['post_type']) && $type=='post'))) {
  return true;
 }
 
 return false;
}

/**
 * Gets array value
 *
 * @param string $key
 * @param array $array
 * @param string $default
 * @return mixed
 */
function themex_value($key, $array, $default='') {
 $value='';
 
 if(isset($array[$key])) {
  if(is_array($array[$key])) {
   $value=reset($array[$key]);
  } else {
   $value=$array[$key];
  }
 } else if ($default!='') {
  $value=$default;
 }
 
 return $value;
}

/**
 * Gets array item
 *
 * @param string $key
 * @param array $array
 * @param string $default
 * @return mixed
 */
function themex_array($key, $array, $default='') {
 $value='';
 
 if(isset($array[$key])) {
  $value=$array[$key];
 } else if ($default!='') {
  $value=$default;
 }
 
 return $value;
}

/**
 * Gets period name
 *
 * @param int $period
 * @return string
 */
function themex_period($period) { 
 switch($period) {
  case 7: 
   $period=__('week', 'makery');
  break;
  
  case 31: 
   $period=__('month', 'makery');
  break;
  
  case 365: 
   $period=__('year', 'makery');
  break;
  
  default:
   $period=round($period/31).' '.__('months', 'makery');
  break;
 }
 
 return $period;
}

/**
 * Implodes array or value
 *
 * @param string $value
 * @param string $prefix
 * @return string
 */
function themex_implode($value, $prefix='') {
 if(is_array($value)) {
  $value=array_map('sanitize_key', $value);
  $value=implode("', '".$prefix, $value);
 } else {
  $value=sanitize_key($value);
 }
 
 $value="'".$prefix.$value."'"; 
 return $value;
}

/**
 * Gets current URL
 *
 * @param bool $private
 * @return string
 */
function themex_url($private=false, $default='') {
 $url=@(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']!='on') ? 'http://'.$_SERVER['SERVER_NAME']:'https://'.$_SERVER['SERVER_NAME'];
 $url.=$_SERVER['REQUEST_URI'];
 
 return $url;
}

/**
 * Gets file name
 *
 * @param string $url
 * @return string
 */
function themex_filename($url) {
 $name=__('Untitled', 'makery');
 $parts=parse_url($url);
 
 if(isset($parts['path'])) {
  $name=basename($parts['path']);
 }
 
 return $name;
}

/**
 * Checks empty taxonomy
 *
 * @param string $name
 * @return bool
 */
function themex_taxonomy($name) {
 $terms=get_terms($name, array(
  'fields' => 'count',
  'hide_empty' => false,
 ));
 
 if($terms!=0) {
  return true;
 }
 
 return false;
}

/**
 * Gets post status
 *
 * @param int $ID
 * @return string
 */
function themex_status($ID) {
 $status='draft';
 if(!empty($ID)) {
  $status=get_post_status($ID);
 }
 
 return $status;
}

/**
 * Replaces string keywords
 *
 * @param string $string
 * @param array $keywords
 * @return string
 */
function themex_keywords($string, $keywords) {
 foreach($keywords as $keyword => $value) {
  $string=str_replace('%'.$keyword.'%', $value, $string);
 }
 
 return $string;
}

/**
 * Sends encoded email
 *
 * @param string $recipient
 * @param string $subject
 * @param string $message
 * @param string $reply
 * @return bool
 */
function themex_mail($recipient, $subject, $message, $reply='') {
 $headers='MIME-Version: 1.0'.PHP_EOL;
 $headers.='Content-Type: text/html; charset=UTF-8'.PHP_EOL;
 $headers.='From: '.get_option('admin_email').PHP_EOL;
 
 if(!empty($reply)) {
  $headers.='Reply-To: '.$reply.PHP_EOL;
 }
 
 $subject='=?UTF-8?B?'.base64_encode($subject).'?='; 
 if(wp_mail($recipient, $subject, $message, $headers)) {
  return true;
 }
 
 return false;
}

/**
 * Sanitizes key
 *
 * @param string $string
 * @return string
 */
function themex_sanitize_key($string) {
 $replacements=array(
  // Latin
  'Ë' => 'A', 'ç' => 'A', 'å' => 'A', 'Ì' => 'A', '€' => 'A', '' => 'A', '®' => 'AE', '‚' => 'C', 
  'é' => 'E', 'ƒ' => 'E', 'æ' => 'E', 'è' => 'E', 'í' => 'I', 'ê' => 'I', 'ë' => 'I', 'ì' => 'I', 
  '?' => 'D', '„' => 'N', 'ñ' => 'O', 'î' => 'O', 'ï' => 'O', 'Í' => 'O', '…' => 'O', '?' => 'O', 
  '¯' => 'O', 'ô' => 'U', 'ò' => 'U', 'ó' => 'U', '†' => 'U', '?' => 'U', '?' => 'Y', '?' => 'TH', 
  '§' => 'ss', 
  'ˆ' => 'a', '‡' => 'a', '‰' => 'a', '‹' => 'a', 'Š' => 'a', 'Œ' => 'a', '¾' => 'ae', '' => 'c', 
  '' => 'e', 'Ž' => 'e', '' => 'e', '‘' => 'e', '“' => 'i', '’' => 'i', '”' => 'i', '•' => 'i', 
  '?' => 'd', '–' => 'n', '˜' => 'o', '—' => 'o', '™' => 'o', '›' => 'o', 'š' => 'o', '?' => 'o', 
  '¿' => 'o', '' => 'u', 'œ' => 'u', 'ž' => 'u', 'Ÿ' => 'u', '?' => 'u', '?' => 'y', '?' => 'th', 
  'Ø' => 'y',
 
  // Greek
  '?' => 'A', '?' => 'B', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', '?' => '8',
  '?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
  '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'Y', '?' => 'F', '?' => 'X', '?' => 'PS', '½' => 'W',
  '?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
  '?' => 'Y',
  '?' => 'a', '?' => 'b', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
  '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => '3', '?' => 'o', '¹' => 'p',
  '?' => 'r', '?' => 's', '?' => 't', '?' => 'y', '?' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
  '?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
  '?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',
 
  // Turkish
  '?' => 'S', '?' => 'I', '‚' => 'C', '†' => 'U', '…' => 'O', '?' => 'G',
  '?' => 's', 'õ' => 'i', '' => 'c', 'Ÿ' => 'u', 'š' => 'o', '?' => 'g', 
 
  // Russian
  '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
  '?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
  '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
  '?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
  '?' => 'Ya',
  '?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
  '?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
  '?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
  '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
  '?' => 'ya',
 
  // Ukrainian
  '?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
  '?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',
 
  // Czech
  '?' => 'C', '?' => 'D', '?' => 'E', '?' => 'N', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', 
  '?' => 'Z', 
  '?' => 'c', '?' => 'd', '?' => 'e', '?' => 'n', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u',
  '?' => 'z', 
 
  // Polish
  '?' => 'A', '?' => 'C', '?' => 'e', '?' => 'L', '?' => 'N', 'î' => 'o', '?' => 'S', '?' => 'Z', 
  '?' => 'Z', 
  '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'l', '?' => 'n', '—' => 'o', '?' => 's', '?' => 'z',
  '?' => 'z',
 
  // Latvian
  '?' => 'A', '?' => 'C', '?' => 'E', '?' => 'G', '?' => 'i', '?' => 'k', '?' => 'L', '?' => 'N', 
  '?' => 'S', '?' => 'u', '?' => 'Z',
  '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'g', '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'n',
  '?' => 's', '?' => 'u', '?' => 'z'
 ); 
 
 $string=str_replace(array_keys($replacements), $replacements, $string);
 $string=preg_replace('/\s+/', '-', $string);
 $string=preg_replace('!\-+!', '-', $string);
 $filtered=$string;
 
 $string=preg_replace('/[^A-Za-z0-9-]/', '', $string);
 $string=strtolower($string);
 $string=trim($string, '-');
 
 if(empty($string) || $string[0]=='-') {
  $string='a'.md5($filtered);
 }
 
 return $string;
}

/**
 * Resize image
 *
 * @param string $url
 * @param int $width
 * @param int $height
 * @return array
 */
function themex_resize($url, $width, $height) {
 add_filter('image_resize_dimensions', 'themex_scale', 10, 6);

 $upload_info=wp_upload_dir();
 $upload_dir=$upload_info['basedir'];
 $upload_url=$upload_info['baseurl'];
 
 //check prefix
 $http_prefix='http://';
 $https_prefix='https://';
 
 if(!strncmp($url, $https_prefix, strlen($https_prefix))){
  $upload_url=str_replace($http_prefix, $https_prefix, $upload_url);
 } else if (!strncmp($url, $http_prefix, strlen($http_prefix))){
  $upload_url=str_replace($https_prefix, $http_prefix, $upload_url);  
 } 

 //check URL
 if (strpos($url, $upload_url)===false) {
  return false;
 }

 //define path
 $rel_path=str_replace($upload_url, '', $url);
 $img_path=$upload_dir.$rel_path;

 //check file
 if (!file_exists($img_path) or !getimagesize($img_path)) {
  return false;
 }

 //get file info
 $info=pathinfo($img_path);
 $ext=$info['extension'];
 list($orig_w, $orig_h)=getimagesize($img_path);

 //get image size
 $dims=image_resize_dimensions($orig_w, $orig_h, $width, $height, true);
 $dst_w=$dims[4];
 $dst_h=$dims[5];

 //resize image
 if((($height===null && $orig_w==$width) xor ($width===null && $orig_h==$height)) xor ($height==$orig_h && $width==$orig_w)) {
  $img_url=$url;
  $dst_w=$orig_w;
  $dst_h=$orig_h;
 } else {
  $suffix=$dst_w.'x'.$dst_h;
  $dst_rel_path=str_replace('.'.$ext, '', $rel_path);
  $destfilename=$upload_dir.$dst_rel_path.'-'.$suffix.'.'.$ext;

  if(!$dims) {
   return false;
  } else if(file_exists($destfilename) && getimagesize($destfilename) && empty($_FILES)) {
   $img_url=$upload_url.$dst_rel_path.'-'.$suffix.'.'.$ext;
  } else {
   if (function_exists('wp_get_image_editor')) {
    $editor=wp_get_image_editor($img_path);
    if (is_wp_error($editor) || is_wp_error($editor->resize($width, $height, true))) {
     return false;
    }

    $resized_file=$editor->save();

    if (!is_wp_error($resized_file)) {
     $resized_rel_path=str_replace($upload_dir, '', $resized_file['path']);
     
     $img_url=$upload_url.$resized_rel_path.'?'.time();
    } else {
     return false;
    }
   } else {
    $resized_img_path=image_resize($img_path, $width, $height, true);
    
    if (!is_wp_error($resized_img_path)) {
     $resized_rel_path=str_replace($upload_dir, '', $resized_img_path);
     $img_url=$upload_url.$resized_rel_path;
    } else {
     return false;
    }
   }
  }
 }

 remove_filter('image_resize_dimensions', 'themex_scale');
 return $img_url;
}

/**
 * Scale image
 *
 * @param string $default
 * @param int $orig_w
 * @param int $orig_h
 * @param int $dest_w
 * @param int $dest_h
 * @param bool $crop
 * @return array
 */
function themex_scale($default, $orig_w, $orig_h, $dest_w, $dest_h, $crop) {
 $aspect_ratio=$orig_w/$orig_h;
 $new_w=$dest_w;
 $new_h=$dest_h;

 if (!$new_w) {
  $new_w=intval($new_h*$aspect_ratio);
 }

 if (!$new_h) {
  $new_h=intval($new_w/$aspect_ratio);
 }

 $size_ratio=max($new_w/$orig_w, $new_h/$orig_h);
 $crop_w=round($new_w/$size_ratio);
 $crop_h=round($new_h/$size_ratio);

 $s_x=floor(($orig_w-$crop_w)/2);
 $s_y=floor(($orig_h-$crop_h)/2);
 $scale=array(0, 0, (int)$s_x, (int)$s_y, (int)$new_w, (int)$new_h, (int)$crop_w, (int)$crop_h);

 return $scale;
}

/**
 * Check multiple select
 */
class themex_walker extends Walker {
 public $tree_type='category';
 public $db_fields=array('parent'=>'parent', 'id'=>'term_id');
 
 public function start_el(&$output, $category, $depth=0, $args=array(), $id=0) {
  $pad=str_repeat('&nbsp;', $depth*3);  
  $cat_name=apply_filters('list_cats', $category->name, $category);
  
  if (isset($args['value_field']) && isset($category->{$args['value_field']})) {
   $value_field=$args['value_field'];
  } else {
   $value_field='term_id';
  }
  
  $output.="\t<option class=\"level-$depth\" value=\"".esc_attr($category->{$value_field})."\"";
  
  if(in_array($category->term_id, $args['selected']))
   $output.=' selected="selected"';
  
  $output.='>';
  $output.=$pad.$cat_name;
  if ($args['show_count'])
   $output.='&nbsp;&nbsp;('.number_format_i18n($category->count).')';
  
  $output.="</option>\n";
 }
}