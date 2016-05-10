<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $post, $woocommerce, $product;

$colorbox='';
if(get_option('woocommerce_enable_lightbox')=='yes') {
	$colorbox='element-colorbox';
}
?>
<div class="item-preview sidebar-widget">
	<div class="item-image">
		<div class="image-wrap">
		<?php
		if(has_post_thumbnail()) {
			$image_title=esc_attr(get_the_title(get_post_thumbnail_id()));
			$image_link=wp_get_attachment_url(get_post_thumbnail_id());
			$image=get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
				'title'=> $image_title,
			));

			$attachment_count=count($product->get_gallery_attachment_ids());
			if($attachment_count > 0) {
				$gallery='gallery';
			} else {
				$gallery='';
			}

			echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="woocommerce-main-image %s" title="%s" data-rel="'.$gallery.'">%s</a>', $image_link, $colorbox, $image_title, $image), $post->ID);
		} else {
			echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'makery')), $post->ID);
		}
		?>
		</div>
	</div>
	<?php do_action('woocommerce_product_thumbnails'); ?>
</div>