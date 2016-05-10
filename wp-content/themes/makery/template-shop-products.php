<?php
/*
Template Name: Shop Products
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('Shop Items', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('shop-products'); ?>
	<?php if(ThemexCore::checkOption('shop_multiple')) { ?>
	<span class="secondary"><?php _e('This shop does not exist.', 'makery'); ?></span>
	<?php } else if(ThemexShop::$data['status']!='publish') { ?>
	<span class="secondary"><?php _e('This shop is currently being reviewed.', 'makery'); ?></span>
	<?php } else if(empty(ThemexShop::$data['products'])) { ?>
	<p class="secondary"><?php _e('No items created yet.', 'makery'); ?></p>
	<?php } else { ?>
	<table class="profile-table">
		<thead>
			<tr>
				<th><?php _e('Name', 'makery'); ?></th>
				<th><?php _e('Stock', 'makery'); ?></th>
				<th><?php _e('Price', 'makery'); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach(ThemexShop::$data['products'] as $ID) {
			$product=ThemexWoo::getProduct($ID);
			?>
			<tr>
				<td>
					<a href="<?php echo ThemexCore::getURL('shop-product', $product['ID']); ?>" <?php if($product['status']=='draft') { ?>class="secondary"<?php } ?>>
						<?php 
						if(empty($product['title'])) {
							_e('Untitled', 'makery');
						} else {
							echo $product['title'];
						}
						?>
					</a>
				</td>
				<td>
				<?php 
				if($product['type']=='virtual') {
					echo '&ndash;';
				} else {
					echo $product['object']->get_total_stock();
				}				
				?>
				</td>
				<td><?php echo $product['price']; ?></td>
				<td class="textright nobreak">
					<a href="<?php echo ThemexCore::getURL('shop-product', $product['ID']); ?>" title="<?php _e('Edit', 'makery'); ?>" class="element-button small square secondary">
						<span class="fa fa-pencil"></span>
					</a>&nbsp;
					<a href="<?php echo get_permalink($product['ID']); ?>" target="_blank" title="<?php _e('View', 'makery'); ?>" class="element-button small square secondary">
						<span class="fa fa-eye"></span>
					</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	<?php if(ThemexShop::$data['status']=='publish') { ?>
	<a href="<?php echo ThemexCore::getURL('shop-product'); ?>" class="element-button primary"><?php _e('Add Item', 'makery'); ?></a>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>