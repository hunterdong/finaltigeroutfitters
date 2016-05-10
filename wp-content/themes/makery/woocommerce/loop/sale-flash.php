<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $post, $product;

if($product->is_on_sale()) {
?>
<div class="item-sale" title="<?php _e('Sale!', 'makery'); ?>">
	<img src="<?php echo THEME_URI; ?>images/icons/icon-sale.png" alt="">
</div>
<?php } ?>