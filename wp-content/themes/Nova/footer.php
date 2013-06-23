		<?php if ( !is_home() ) { ?>
			<div id="footer-widgets">
				<div class="container">
					<div id="widgets-wrapper" class="clearfix">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
						<?php endif; ?>
					</div> <!-- end #widgets-wrapper -->
				</div> <!-- end .container -->
			</div> <!-- end #footer-widgets -->
		<?php } ?>

		<div id="footer">
			<div class="container clearfix">
				<p id="copyright"><?php esc_html_e('Designed by ','Nova'); ?> <a href="http://www.elegantthemes.com" title="Premium WordPress Themes">Elegant WordPress Themes</a> | <?php esc_html_e('Powered by ','Nova'); ?> <a href="http://www.wordpress.org">WordPress</a></p>
			</div> <!-- end .container -->
		</div> <!-- end #footer -->
	</div> <!-- end #center-highlight-->

	<?php get_template_part('includes/scripts'); ?>

	<?php wp_footer(); ?>

</body>
</html>