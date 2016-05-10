<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

$limit=ThemexCore::getOption('products_per_page', 9);
$extend=$limit*2;
?>
<div class="items-order right">
	<form action="<?php echo themex_url(); ?>" method="GET" class="woocommerce-ordering">
		<a href="#limit" class="element-submit <?php if(themex_value('limit', $_GET, $limit)==$limit) { ?>active<?php } ?>" data-value="<?php echo $limit; ?>"><?php echo $limit; ?></a>
		<a href="#limit" class="element-submit <?php if(themex_value('limit', $_GET)==$extend) { ?>active<?php } ?>" data-value="<?php echo $extend; ?>"><?php echo $extend; ?></a>
		<a href="#limit" class="element-submit <?php if(themex_value('limit', $_GET)=='-1') { ?>active<?php } ?>" data-value="-1"><?php _e('All', 'makery'); ?></a>	
		<div class="element-select left small">
			<span></span>
			<select name="orderby" class="orderby">
				<?php foreach($catalog_orderby_options as $id => $name) { ?>
				<option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
				<?php } ?>
			</select>
		</div>
		<input type="hidden" name="limit" id="limit" value="<?php echo themex_value('limit', $_GET, $limit); ?>" />
		<?php
		foreach($_GET as $key => $value) {
			if(!in_array($key, array('orderby', 'submit', 'limit'))) {			
				if (is_array($value)) {
					foreach($value as $item) {
						echo '<input type="hidden" name="'.esc_attr($key).'[]" value="'.esc_attr($item).'" />';
					}
				} else {
					echo '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($value).'" />';
				}
			}
		}
		?>
	</form>
</div>