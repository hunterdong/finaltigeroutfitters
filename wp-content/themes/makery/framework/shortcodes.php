<?php
//Columns
add_shortcode('one_sixth', 'themex_one_sixth');
function themex_one_sixth($atts, $content = null) {
   return '<div class="twocol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_sixth_last', 'themex_one_sixth_last');
function themex_one_sixth_last($atts, $content = null) {
   return '<div class="twocol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_fourth', 'themex_one_fourth');
function themex_one_fourth($atts, $content = null) {
   return '<div class="threecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_fourth_last', 'themex_one_fourth_last');
function themex_one_fourth_last($atts, $content = null) {
   return '<div class="threecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_third', 'themex_one_third');
function themex_one_third($atts, $content = null) {
   return '<div class="fourcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_third_last', 'themex_one_third_last');
function themex_one_third_last($atts, $content = null) {
   return '<div class="fourcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('five_twelfths', 'themex_five_twelfths');
function themex_five_twelfths($atts, $content = null) {
   return '<div class="fivecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('five_twelfths_last', 'themex_five_twelfths_last');
function themex_five_twelfths_last($atts, $content = null) {
   return '<div class="fivecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_half', 'themex_one_half');
function themex_one_half($atts, $content = null) {
   return '<div class="sixcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_half_last', 'themex_one_half_last');
function themex_one_half_last($atts, $content = null) {
   return '<div class="sixcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('seven_twelfths', 'themex_seven_twelfths');
function themex_seven_twelfths($atts, $content = null) {
   return '<div class="sevencol column">'.do_shortcode($content).'</div>';
}

add_shortcode('seven_twelfths_last', 'themex_seven_twelfths_last');
function themex_seven_twelfths_last($atts, $content = null) {
   return '<div class="sevencol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('two_thirds', 'themex_two_thirds');
function themex_two_thirds($atts, $content = null) {
   return '<div class="eightcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('two_thirds_last', 'themex_two_thirds_last');
function themex_two_thirds_last($atts, $content = null) {
   return '<div class="eightcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('three_fourths', 'themex_three_fourths');
function themex_three_fourths($atts, $content = null) {
   return '<div class="ninecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('three_fourths_last', 'themex_three_fourths_last');
function themex_three_fourths_last($atts, $content = null) {
   return '<div class="ninecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

//Button
add_shortcode('button','themex_button');
function themex_button($atts, $content=null) {	
	extract(shortcode_atts(array(
		'url' => '#',
		'target' => 'self',
		'color' => '',
		'size' => '',
    ), $atts));
	
	$out='<a href="'.$url.'" target="_'.$target.'" class="element-button '.$size.' '.$color.'">'.do_shortcode($content).'</a>';
	return $out;
}

//Section
add_shortcode('section', 'themex_section');
function themex_section($atts, $content=null) {
	extract(shortcode_atts(array(
		'background' => '',
    ), $atts));
	
	$style='';
	if(!empty($background)) {
		$style='background:url('.$background.');';
	}

	$out='</section></div><div class="section-wrap" style="'.$style.'"><section class="site-content container">';
	$out.=do_shortcode($content);
	$out.='</section></div><div class="content-wrap"><section class="site-content container">';
	
	return $out;
}

//Shops
add_shortcode('shops', 'themex_shops');
function themex_shops($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '3',
		'columns' => '3',
		'order' => 'date',
		'category' => '0',
		'ids' => '0',
    ), $atts));
	
	if($order=='random') {
		$order='rand';
	}
	
	$columns=intval($columns);
	$width='three';
	$counter=0;	
	
	switch($columns) {
		case '1': $width='twelve'; break;
		case '2': $width='six'; break;
		case '3': $width='four'; break;
	}	
	
	$args=array(
		'post_type' => 'shop',
		'showposts' => $number,	
		'orderby' => $order,
		'order' => 'DESC',
	);
	
	if(!empty($ids)) {
		$ids=explode(',', $ids);
		$ids=array_map('intval', $ids);
		$args['post__in']=$ids;
	}
	
	if(!empty($category)) {
		$args['tax_query'][]=array(
            'taxonomy' => 'shop_category',
            'terms' => $category,
            'field' => 'term_id',
        );
	}
	
	if(ThemexCore::checkOption('shops_empty')) {
		$args['meta_query']=array(
			array(
				'key' => '_'.THEMEX_PREFIX.'hidden',
				'compare' => '!=',
				'value' => '1',
			),
		);
	}
		
	if(in_array($order, array('rating', 'sales', 'admirers'))) {
		$args['orderby']='meta_value_num';
		$args['meta_key']='_'.THEMEX_PREFIX.$order;
	} else if($order=='title') {
		$args['order']='ASC';
	}
	
	$query=new WP_Query($args);

	$out='<div class="shops-wrap clearfix">';
	while($query->have_posts()){
		$query->the_post();	
		$counter++;
		
		$class='';
		if($counter==$columns) {
			$class='last';
		}
		
		$out.='<div class="column '.$width.'col '.$class.'">';		
		ob_start();
		get_template_part('content', 'shop');
		$out.=ob_get_contents();
		ob_end_clean();
		$out.='</div>';
		
		if($counter==$columns) {
			$out.='<div class="clear"></div>';
			$counter=0;						
		}
	}
	$out.='</div><div class="clear"></div>';
	
	wp_reset_query();
	return $out;
}

//Testimonials
add_shortcode('testimonials', 'themex_testimonials');
function themex_testimonials($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '4',
		'order' => 'date',
		'category' => '0',
		'pause' => '0',
		'speed' => '900',
    ), $atts));
	
	if($order=='random') {
		$order='rand';
	}
	
	$args=array(
		'post_type' => 'testimonial',
		'showposts' => $number,
		'orderby' => $order,
	);
	
	if(!empty($category)) {
		$args['tax_query'][]=array(
            'taxonomy' => 'testimonial_category',
            'terms' => $category,
            'field' => 'term_id',
        );
	}
		
	$query=new WP_Query($args);	
	
	$out='<div class="testimonials-slider element-slider" data-pause="'.$pause.'" data-speed="'.$speed.'"><ul>';
	while($query->have_posts()){
		$query->the_post();
		
		ob_start();
		the_content();
		$content=ob_get_contents();
		ob_end_clean();
		
		$content=str_replace('<p>', '<h1>', $content);
		$content=str_replace('</p>', '</h1>', $content);
		$GLOBALS['content']=$content;
		
		$out.='<li>';
		ob_start();
		get_template_part('content', 'testimonial');
		$out.=ob_get_contents();
		ob_end_clean();
		$out.='</li>';
	}
	$out.='</ul></div>';	
	
	wp_reset_query();
	return $out;
}

//Title
add_shortcode('title', 'themex_title');
function themex_title($atts, $content=null) {
	extract(shortcode_atts(array(
		'indent' => '',
    ), $atts));
	
	$style='';
	if(!empty($indent)) {
		$style='margin-bottom:'.$indent.'em;';
	}
	
	$out='<div class="element-title" style="'.$style.'"><h1>'.do_shortcode($content).'</h1></div>';
	return $out;
}

//Users
add_shortcode('users','themex_users');
function themex_users( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'number' => '3',
		'columns' => '3',
		'order' => 'date',
		'role' => '',
		'ids' => '',
    ), $atts));
	
	$orderby='registered';
	$orderdir='ASC';
	switch($order) {
		case 'activity':
			$orderby='post_count';
			$orderdir='DESC';
		break;
		
		case 'name':
			$orderby='display_name';
		break;
		
		case 'date':
			$orderby='registered';
			$orderdir='DESC';
		break;
	}
	
	$columns=intval($columns);
	$width='three';
	$counter=0;	
	
	switch($columns) {
		case '1': $width='twelve'; break;
		case '2': $width='six'; break;
		case '3': $width='four'; break;
	}
	
	$args=array(
		'number' => intval($number),
		'orderby' => $orderby,
		'order' => $orderdir,
	);
	
	if(!empty($id)) {
		$ids=explode(',', $id);
		$ids=array_map('intval', $ids);
		$args['include']=$ids;		
	}
	
	if(!empty($role)) {
		$args['role']=$role;
	}
	
	$users=get_users($args);
	
	$out='<div class="profiles-wrap">';
	foreach($users as $user) {
		$GLOBALS['user']=$user;
		$counter++;
		
		$class='';
		if($counter==$columns) {
			$class='last';
		}
		
		$out.='<div class="column '.$width.'col '.$class.'">';
		ob_start();
		get_template_part('content', 'profile');
		$out.=ob_get_contents();
		ob_end_clean();
		$out.='</div>';
		
		if($counter==$columns) {
			$out.='<div class="clear"></div>';
			$counter=0;						
		}
	}
	$out.='<div class="clear"></div></div>';
	
	return $out;
}