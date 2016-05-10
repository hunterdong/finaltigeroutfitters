<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product;

if($price_html=$product->get_price_html()) {
?>
<div class="item-price left">
	<?php echo $price_html; ?>
</div>
<?php } ?>