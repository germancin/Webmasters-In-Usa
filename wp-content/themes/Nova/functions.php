<?php
add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		global $themename, $shortname, $default_colorscheme;
		$themename = "Nova";
		$shortname = "nova";

		$default_colorscheme = "Grey";

		$theme_directory = get_template_directory();

		require_once($theme_directory . '/epanel/custom_functions.php');

		require_once($theme_directory . '/includes/functions/comments.php');

		require_once($theme_directory . '/includes/functions/sidebars.php');

		load_theme_textdomain('Nova',$theme_directory.'/lang');

		require_once($theme_directory . '/epanel/core_functions.php');

		require_once($theme_directory . '/epanel/post_thumbnails_nova.php');

		include($theme_directory . '/includes/widgets.php');

		require_once($theme_directory . '/includes/additional_functions.php');

		add_action( 'wp_enqueue_scripts', 'et_add_responsive_shortcodes_css', 11 );

		add_action( 'pre_get_posts', 'et_home_posts_query' );

		add_action( 'et_epanel_changing_options', 'et_delete_featured_ids_cache' );
		add_action( 'delete_post', 'et_delete_featured_ids_cache' );
		add_action( 'save_post', 'et_delete_featured_ids_cache' );
	}
}

function register_main_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu', 'Nova' )
		)
	);
}
if (function_exists('register_nav_menus')) add_action( 'init', 'register_main_menus' );

// add Home link to the custom menu WP-Admin page
function et_add_home_link( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'et_add_home_link' );

/**
 * Gets featured posts IDs from transient, if the transient doesn't exist - runs the query and stores IDs
 */
function et_get_featured_posts_ids(){
	if ( false === ( $et_featured_post_ids = get_transient( 'et_featured_post_ids' ) ) ) {
		$featured_query = new WP_Query( apply_filters( 'et_featured_post_args', array(
			'posts_per_page'	=> (int) et_get_option( 'nova_featured_num' ),
			'cat'				=> (int) get_catId( et_get_option( 'nova_feat_cat' ) )
		) ) );

		if ( $featured_query->have_posts() ) {
			while ( $featured_query->have_posts() ) {
				$featured_query->the_post();

				$et_featured_post_ids[] = get_the_ID();
			}

			set_transient( 'et_featured_post_ids', $et_featured_post_ids );
		}

		wp_reset_postdata();
	}

	return $et_featured_post_ids;
}

/**
 * Filters the main query on homepage
 */
function et_home_posts_query( $query = false ) {
	/* Don't proceed if it's not homepage or the main query */
	if ( ! is_home() || ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() ) return;

	if ( 'false' == et_get_option( 'nova_blog_style', 'false' ) ) return;

	/* Set the amount of posts per page on homepage */
	$query->set( 'posts_per_page', (int) et_get_option( 'nova_homepage_posts', '6' ) );

	/* Exclude categories set in ePanel */
	$exclude_categories = et_get_option( 'nova_exlcats_recent', false );
	if ( $exclude_categories ) $query->set( 'category__not_in', array_map( 'intval', et_generate_wpml_ids( $exclude_categories, 'category' ) ) );

	/* Exclude slider posts, if the slider is activated, pages are not featured and posts duplication is disabled in ePanel  */
	if ( 'on' == et_get_option( 'nova_featured', 'on' ) && 'false' == et_get_option( 'nova_use_pages', 'false' ) && 'false' == et_get_option( 'nova_duplicate', 'on' ) )
		$query->set( 'post__not_in', et_get_featured_posts_ids() );
}

/**
 * Deletes featured posts IDs transient, when the user saves, resets ePanel settings, creates or moves posts to trash in WP-Admin
 */
function et_delete_featured_ids_cache(){
	if ( false !== get_transient( 'et_featured_post_ids' ) ) delete_transient( 'et_featured_post_ids' );
}

if ( ! function_exists( 'et_list_pings' ) ){
	function et_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php }
}

add_action( 'wp_enqueue_scripts', 'et_responsive_layout' );
function et_responsive_layout(){
	if ( 'on' != get_option('nova_responsive_layout') ) return;
	$template_dir = get_template_directory_uri();

	wp_enqueue_style('et_responsive', $template_dir . '/css/responsive.css');

	wp_enqueue_script('fitvids', $template_dir . '/js/jquery.fitvids.js', array('jquery'), '1.0', true);
	wp_enqueue_script('flexslider', $template_dir . '/js/jquery.flexslider-min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('et_flexslider_script', $template_dir . '/js/et_flexslider.js', array('jquery'), '1.0', true);
}

add_action( 'wp_enqueue_scripts', 'et_nova_load_scripts_styles' );
function et_nova_load_scripts_styles(){
	global $shortname;

	$et_prefix = ! et_options_stored_in_one_row() ? "{$shortname}_" : '';
	$heading_font_option_name = "{$et_prefix}heading_font";
	$body_font_option_name = "{$et_prefix}body_font";

	$et_gf_enqueue_fonts = array();
	$et_gf_heading_font = sanitize_text_field( et_get_option( $heading_font_option_name, 'none' ) );
	$et_gf_body_font = sanitize_text_field( et_get_option( $body_font_option_name, 'none' ) );

	if ( 'none' != $et_gf_heading_font ) $et_gf_enqueue_fonts[] = $et_gf_heading_font;
	if ( 'none' != $et_gf_body_font ) $et_gf_enqueue_fonts[] = $et_gf_body_font;

	if ( ! empty( $et_gf_enqueue_fonts ) ) et_gf_enqueue_fonts( $et_gf_enqueue_fonts );
}

add_action( 'wp_head', 'et_add_viewport_meta' );
function et_add_viewport_meta(){
	if ( 'on' != get_option('nova_responsive_layout') ) return;
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
}

add_action( 'et_header_menu', 'et_add_mobile_navigation' );
function et_add_mobile_navigation(){
	if ( 'on' != get_option('nova_responsive_layout') ) return;
	echo '<div><a href="#" class="mobile_nav closed">' . esc_html__( 'navigation menu', 'Nova' ) . '</a></div>';
}

function et_epanel_custom_colors_css(){
	global $shortname; ?>

	<style type="text/css">
		body { color: #<?php echo esc_html(get_option($shortname.'_color_mainfont')); ?>; }
		#content-area a { color: #<?php echo esc_html(get_option($shortname.'_color_mainlink')); ?>; }
		ul.nav li a { color: #<?php echo esc_html(get_option($shortname.'_color_pagelink')); ?>; }
		ul.nav > li.current_page_item > a, ul#top-menu > li:hover > a, ul.nav > li.current-cat > a { color: #<?php echo esc_html(get_option($shortname.'_color_pagelink_active')); ?>; }
		h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: #<?php echo esc_html(get_option($shortname.'_color_headings')); ?>; }

		#sidebar a { color:#<?php echo esc_html(get_option($shortname.'_color_sidebar_links')); ?>; }
		div#footer { color:#<?php echo esc_html(get_option($shortname.'_footer_text')); ?> }
		#footer a, ul#bottom-menu li a { color:#<?php echo esc_html(get_option($shortname.'_color_footerlinks')); ?> }
	</style>

<?php }

if ( function_exists( 'get_custom_header' ) ) {
	// compatibility with versions of WordPress prior to 3.4

	add_action( 'customize_register', 'et_nova_customize_register' );
	function et_nova_customize_register( $wp_customize ) {
		$google_fonts = et_get_google_fonts();

		$font_choices = array();
		$font_choices['none'] = 'Default Theme Font';
		foreach ( $google_fonts as $google_font_name => $google_font_properties ) {
			$font_choices[ $google_font_name ] = $google_font_name;
		}

		$wp_customize->remove_section( 'title_tagline' );
		$wp_customize->remove_section( 'background_image' );
		$wp_customize->remove_section( 'colors' );

		$wp_customize->add_section( 'et_google_fonts' , array(
			'title'		=> __( 'Fonts', 'Nova' ),
			'priority'	=> 50,
		) );

		$wp_customize->add_setting( 'nova_heading_font', array(
			'default'		=> 'none',
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options'
		) );

		$wp_customize->add_control( 'nova_heading_font', array(
			'label'		=> __( 'Header Font', 'Nova' ),
			'section'	=> 'et_google_fonts',
			'settings'	=> 'nova_heading_font',
			'type'		=> 'select',
			'choices'	=> $font_choices
		) );

		$wp_customize->add_setting( 'nova_body_font', array(
			'default'		=> 'none',
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options'
		) );

		$wp_customize->add_control( 'nova_body_font', array(
			'label'		=> __( 'Body Font', 'Nova' ),
			'section'	=> 'et_google_fonts',
			'settings'	=> 'nova_body_font',
			'type'		=> 'select',
			'choices'	=> $font_choices
		) );
	}

	add_action( 'wp_head', 'et_nova_add_customizer_css' );
	add_action( 'customize_controls_print_styles', 'et_nova_add_customizer_css' );
	function et_nova_add_customizer_css(){ ?>
		<style type="text/css">
		<?php
			global $shortname;

			$et_prefix = ! et_options_stored_in_one_row() ? "{$shortname}_" : '';
			$heading_font_option_name = "{$et_prefix}heading_font";
			$body_font_option_name = "{$et_prefix}body_font";

			$et_gf_heading_font = sanitize_text_field( et_get_option( $heading_font_option_name, 'none' ) );
			$et_gf_body_font = sanitize_text_field( et_get_option( $body_font_option_name, 'none' ) );

			if ( 'none' != $et_gf_heading_font || 'none' != $et_gf_body_font ) :

				if ( 'none' != $et_gf_heading_font )
					et_gf_attach_font( $et_gf_heading_font, 'h1, h2, h3, h4, h5, h6, #featured h2.title, #featured .description p, #entries-area h1, #entries-area h2, #entries-area h3, #entries-area h4, #entries-area h5, #entries-area h6, ul#main-tabs span, span.fn, .tab-slide h3.title, #footer-widgets h3, #featured h2.title span, #featured .description p span, .tab-slide h3.title span' );

				if ( 'none' != $et_gf_body_font )
					et_gf_attach_font( $et_gf_body_font, 'body' );

			endif;
		?>
		</style>
	<?php }

	add_action( 'customize_controls_print_footer_scripts', 'et_load_google_fonts_scripts' );
	function et_load_google_fonts_scripts() {
		wp_enqueue_script( 'et_google_fonts', get_template_directory_uri() . '/epanel/google-fonts/et_google_fonts.js', array( 'jquery' ), '1.0', true );
		wp_localize_script( 'et_google_fonts', 'et_google_fonts', array(
			'options_in_one_row' => ( et_options_stored_in_one_row() ? 1 : 0 )
		) );
	}

	add_action( 'customize_controls_print_styles', 'et_load_google_fonts_styles' );
	function et_load_google_fonts_styles() {
		wp_enqueue_style( 'et_google_fonts_style', get_template_directory_uri() . '/epanel/google-fonts/et_google_fonts.css', array(), null );
	}
}