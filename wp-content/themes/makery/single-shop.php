<?php get_header(); ?>
<div class="woocommerce">
	<?php	
	$woocommerce_loop['single']=true;
	$woocommerce_loop['columns']=4;	
	
	ThemexWoo::queryProducts();
		
	$layout='full';
	$shop=$post->ID;
	
	ThemexWoo::getTemplate('archive-product.php');
	?>
</div>
<?php get_footer(); ?>