<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $product;

if(get_option('woocommerce_enable_review_rating')==='no') {
	return;
}

$count=$product->get_rating_count();
$average=$product->get_average_rating();

if($count>0) {
?>
<div class="title-option right" title="<?php printf(_n('%s Review', '%s Reviews', $count, 'makery'), $count); ?>" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
	<div class="element-rating" data-score="<?php echo $average; ?>"></div>
	<div class="hidden" itemprop="ratingValue"><?php echo $average; ?></div>
</div>
<?php } ?>