				<?php if(is_active_sidebar('footer')) { ?>
					<div class="clear"></div>
					<div class="footer-sidebar sidebar clearfix">
						<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer')); ?>
					</div>
				<?php } ?>
			</section>
			<!-- /content -->			
		</div>
		<div class="footer-wrap">
			<footer class="site-footer container">
				<div class="site-copyright left">
					<?php echo ThemexCore::getOption('copyright', 'Makery Theme &copy; '.date('Y')); ?>
				</div>
				<nav class="footer-menu right">
					<?php wp_nav_menu(array('theme_location' => 'footer_menu')); ?>
				</nav>
			</footer>
			<!-- /footer -->
		</div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>