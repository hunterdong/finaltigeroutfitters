<?php
/*
Template Name: Shop Membership
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<h1><?php _e('Shop Membership', 'makery'); ?></h1>
	</div>
	<?php ThemexInterface::renderTemplateContent('shop-membership'); ?>
	<?php if(!ThemexWoo::isActive() || !ThemexCore::checkOption('membership_free')) { ?>
	<span class="secondary"><?php _e('This shop does not exist.', 'makery'); ?></span>
	<?php 
	} else {
	$membership=ThemexUser::getMembership(ThemexUser::$data['current']['ID']);
	?>
	<table class="profile-fields">
		<tbody>
			<tr>
				<th><?php _e('Membership', 'makery'); ?></th>
				<td><?php echo $membership['title']; ?></td>
			</tr>
			<tr>
				<th><?php _e('Expires', 'makery'); ?></th>
				<td><?php echo $membership['date']; ?></td>
			</tr>
			<tr>
				<th><?php _e('Products', 'makery'); ?></th>
				<td><?php echo $membership['products']; ?></td>
			</tr>
		</tbody>
	</table>
	<?php 
	$query=new WP_Query(array(
		'post_type' =>'membership',
		'showposts' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	));
	
	if($query->have_posts()) {
	?>
	<div class="shop-membership element-toggle">
		<?php
		while($query->have_posts()) {
		$query->the_post(); 
		?>
		<div class="toggle-container">
			<div class="toggle-title"><h4><?php the_title(); ?><span class="right"><?php echo ThemexUser::getPeriod($post->ID); ?></span></h4></div>
			<div class="toggle-content">
				<?php the_content(); ?>
				<form action="" method="POST">
					<a href="#" class="element-button element-submit primary"><?php _e('Subscribe', 'makery'); ?></a>
					<input type="hidden" name="membership_id" value="<?php echo $post->ID; ?>" />
					<input type="hidden" name="user_action" value="add_membership" />
				</form>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>