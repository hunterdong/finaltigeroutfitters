<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

$rating=intval(get_comment_meta($comment->comment_ID, 'rating', true));
?>
<li itemprop="reviews" itemscope itemtype="http://schema.org/Review" id="li-comment-<?php comment_ID()?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment">
		<div class="comment-image">
			<div class="image-border">
				<div class="image-wrap">
					<?php echo get_avatar($comment, apply_filters('woocommerce_review_gravatar_size', '100'), '', get_comment_author()); ?>
				</div>
			</div>											
		</div>
		<div class="comment-content">
			<header class="comment-header clearfix">
				<h6 class="comment-author" itemprop="author"><?php comment_author(); ?></h6>
				<time class="comment-date" itemprop="datePublished" datetime="<?php echo get_comment_date('c'); ?>"><?php echo get_comment_date(__(get_option('date_format'), 'makery')); ?></time>
				<?php
				if(get_option('woocommerce_review_rating_verification_label')=== 'yes'){
					if(wc_customer_bought_product($comment->comment_author_email, $comment->user_id, $comment->comment_post_ID)){
						echo '<div class="comment-status">'.__('Buyer', 'makery').'</div>';
					}
				}
				?>				
				<?php if($rating && get_option('woocommerce_enable_review_rating')== 'yes'){ ?>
				<div class="comment-rating right" title="<?php echo sprintf(__('Rated %d out of 5', 'makery'), $rating)?>" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
					<div class="element-rating" data-score="<?php echo $rating; ?>"></div>
				</div>
				<?php } ?>
			</header>
			<div itemprop="description">
				<?php comment_text(); ?>
			</div>
		</div>
	</div>