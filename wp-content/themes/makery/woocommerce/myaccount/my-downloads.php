<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

if($downloads=WC()->customer->get_downloadable_products()) {
?>
	<?php do_action( 'woocommerce_before_available_downloads' ); ?>
	<div class="element-title">
		<h1><?php echo apply_filters('woocommerce_my_account_my_downloads_title', __('My Downloads', 'makery')); ?></h1>
	</div>	
	<ul class="digital-downloads">
		<?php foreach($downloads as $download) { ?>
		<li>
			<?php
			do_action('woocommerce_available_download_start', $download);

			if(is_numeric($download['downloads_remaining'])) {
				echo apply_filters('woocommerce_available_download_count', '<span class="count">'.sprintf(_n('%s download remaining', '%s downloads remaining', $download['downloads_remaining'], 'makery'), $download['downloads_remaining']).'</span> ', $download);
			}

			echo apply_filters('woocommerce_available_download_link', '<a href="'.esc_url($download['download_url']).'">'.$download['download_name'].'</a>', $download);

			do_action('woocommerce_available_download_end', $download);
			?>
		</li>
		<?php } ?>
	</ul>
	<?php do_action('woocommerce_after_available_downloads'); ?>
<?php } ?>