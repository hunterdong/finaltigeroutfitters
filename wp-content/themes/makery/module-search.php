<div class="shop-search clearfix">
	<form action="<?php echo SITE_URL; ?>" method="GET" class="site-form">
		<table>
			<tbody>
				<tr>
					<th><?php _e('Keywords', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="s" value="<?php the_search_query(); ?>" />
						</div>
					</td>
				</tr>
				<?php if(themex_taxonomy('shop_category')) { ?>
				<tr>
					<th><?php _e('Category', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'category',
								'type' => 'select_category',
								'taxonomy' => 'shop_category',
								'value' => esc_attr(themex_array('category', $_GET)),
								'wrap' => false,				
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('profile_location')) { ?>
				<tr>
					<th><?php _e('Country', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'country',
								'type' => 'select_country',
								'attributes' => array('class' => 'countries-list'),
								'value' => esc_attr(themex_array('country', $_GET)),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('City', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'city',
								'type' => 'select_city',
								'attributes' => array(
									'class' => 'element-filter',
									'data-filter' => 'countries-list',
								),
								'value' => esc_attr(themex_array('city', $_GET)),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<a href="#" class="element-button element-submit primary"><?php _e('Search', 'makery'); ?></a>
		<input type="hidden" name="post_type" value="shop" />
	</form>
</div>