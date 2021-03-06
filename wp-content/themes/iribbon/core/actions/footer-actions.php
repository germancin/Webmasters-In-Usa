<?php
/**
* Footer actions used by the CyberChimps Response Core Framework
*
* Author: Tyler Cunningham
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response
* @since 1.0
*/

/**
* Response footer actions
*/
add_action ( 'response_footer', 'response_footer_widgets' );

add_action ( 'response_secondary_footer', 'response_secondary_footer_credit' );
add_action ( 'response_secondary_footer', 'response_secondary_footer_copyright' );


/**
* Set the footer widgetized area.
*
* @since 1.0
*/
function response_footer_widgets() { 

   	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer") ) { ?>
		
		<div class="span3 footer-widgets">
			<h3 class="footer-widget-title"><?php _e( 'Footer Widgets', 'iribbon' ); ?></h3>
			<ul>
				<li>To customize this widget area login to your admin account, go to Appearance, then Widgets and drag new widgets into Footer Widgets</li>
			</ul>
		</div>

		<div class="span3 footer-widgets">
			<h3 class="footer-widget-title"><?php _e( 'Recent Posts', 'iribbon' ); ?></h3>
			<ul>
				<?php wp_get_archives('type=postbypost&limit=4'); ?>
			</ul>
		</div>
		
		<div class="span3 footer-widgets">
			<h3 class="footer-widget-title"><?php _e( 'Archives', 'iribbon' ); ?></h3>
			<ul>
				<?php wp_get_archives('type=monthly&limit=16'); ?>
			</ul>
		</div>

		<div class="span3 footer-widgets">
			<h3 class="footer-widget-title"><?php _e( 'WordPress', 'iribbon' ); ?></h3>
			<ul>
    		<?php wp_register(); ?>
    		<li><?php wp_loginout(); ?></li>
    		<li><a href="<?php echo esc_url( __('http://wordpress.org/', 'iribbon' )); ?>" target="_blank" title="<?php esc_attr_e('Powered by WordPress, state-of-the-art semantic personal publishing platform.', 'iribbon'); ?>"> <?php _e('WordPress', 'iribbon' ); ?></a></li>
    		<?php wp_meta(); ?>
    		</ul>
		</div>
		
			<?php }
			
			echo "<div class='clear'></div> ";
}

/**
* Adds the afterfooter copyright area. 
*
* @since 1.0
*/
function response_secondary_footer_copyright() {
	global $options, $ir_themeslug; //call globals
		
	if ($options->get($ir_themeslug.'_footer_text') == "") {
		$copyright =  get_bloginfo('name');
	}
	else {
		$copyright = $options->get($ir_themeslug.'_footer_text');
	}
	
	echo "<div id='afterfootercopyright' class='span6'>";
		echo "&copy; $copyright";
	echo "</div>";
}

/**
* Adds the CyberChimps credit.
*
* @since 1.0
*/
function response_secondary_footer_credit() { 
?>
	<div id="credit" class="span6">
		<a href="http://cyberchimps.com/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/achimps.png" alt="credit" /></a>
	</div> 
<?php
}
/**
* End
*/

?>