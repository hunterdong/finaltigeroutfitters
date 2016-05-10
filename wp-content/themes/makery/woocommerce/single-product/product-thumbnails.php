<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $post, $product, $woocommerce;
$attachment_ids = $product->get_gallery_attachment_ids();

$colorbox='';
if(get_option('woocommerce_enable_lightbox')=='yes') {
	$colorbox='element-colorbox';
}

if($attachment_ids) {
?>
<div class="item-gallery clearfix">
	<?php
	$loop=0;
	$columns=apply_filters('woocommerce_product_thumbnails_columns', 3);

	foreach($attachment_ids as $attachment_id){
		$classes=array();

		if($loop==0 || $loop % $columns==0) {
			$classes[]='first';
		}			

		if(($loop + 1)% $columns==0) {
			$classes[]='last';
		}			

		$image_link=wp_get_attachment_url($attachment_id);
		if(!$image_link) {
			continue;
		}			

		$image=wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'));
		$image_class=esc_attr(implode(' ', $classes));
		$image_title=esc_attr(get_the_title($attachment_id));
		?>
		<div class="thirdcol left">
			<div class="image-wrap">
				<?php echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<a href="%s" class="%s %s" title="%s" data-rel="gallery">%s</a>', $image_link, $image_class, $colorbox, $image_title, $image), $attachment_id, $post->ID, $image_class); ?>
			</div>
		</div>
		<?php
		$loop++;
	}
	?>
</div>
<?php } ?>