<?php
/**
* CyberChimps Response Core Framework Functions
*
* Authors: Tyler Cunningham
* Copyright: © 2011
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
* Load styles.
*/ 

function iribbon_styles() {
	global $options, $ir_themeslug;
	
	// get color scheme
	if ($options->get($ir_themeslug.'_skin_color') == '') {
		$color = 'default';
	}
	else {
		$color = $options->get($ir_themeslug.'_skin_color');
	}
	
	// set paths to stylesheet dir
	$core_path =  get_template_directory_uri() ."/core/css";
	$path = get_template_directory_uri() ."/css";
	
	// register stylesheets
	wp_register_style( 'bootstrap', $core_path.'/bootstrap/bootstrap.css' );
	wp_register_style( 'bootstrap_responsive', $core_path.'/bootstrap/bootstrap-responsive.css', array( 'bootstrap' ) );
	wp_register_style( 'orbit', $core_path.'/orbit/orbit.css', array( 'bootstrap' ) );
	wp_register_style( 'shortcode', $path.'/shortcode.css' );
	wp_register_style( 'iribbon_style', $path.'/style.css', array( 'bootstrap' ) );
	wp_register_style( 'elements', $path.'/elements.css', array( 'bootstrap', 'iribbon_style' ) );
	wp_register_style( 'color', $path.'/color/'.$color.'.css', array( 'elements' ) );
	
	// child theme support
	wp_register_style( 'child_theme', get_stylesheet_directory_uri().'/style.css', array( 'iribbon_style' ) );
	if( is_child_theme() ) {
		wp_enqueue_style( 'child_theme' );
	}
	
	// get fonts
	if ($options->get($ir_themeslug.'_font') == "" AND $options->get($ir_themeslug.'_custom_font') == "") {
		$font = apply_filters( 'response_default_font', 'Georgia' );
	}		
	elseif ($options->get($ir_themeslug.'_custom_font') != "" && $options->get($ir_themeslug.'_font') == 'custom') {
		$font = $options->get($ir_themeslug.'_custom_font');	
	}	
	else {
		$font = $options->get($ir_themeslug.'_font'); 
	} 
	
	// register font stylesheet
	if( $font == 'Actor' ||
		$font == 'Coda' ||
		$font == 'Maven Pro' ||
		$font == 'Metrophobic' ||
		$font == 'News Cycle' ||
		$font == 'Nobile' ||
		$font == 'Tenor Sans' ||
		$font == 'Quicksand' ||
		$font == 'Ubuntu') {
		
		// Check if SSL is present, if so then use https othereise use http
		$protocol = is_ssl() ? 'https' : 'http';
		wp_register_style( 'fonts', $protocol . '://fonts.googleapis.com/css?family='.$font, array( 'iribbon_style' ) ); 		
	}
	
	// enqueue stylesheets
	wp_enqueue_style( 'bootstrap' );
	wp_enqueue_style( 'bootstrap_responsive' );
	wp_enqueue_style( 'orbit' );
	wp_enqueue_style( 'shortcode' );
	wp_enqueue_style( 'iribbon_style' );
	wp_enqueue_style( 'elements' );
	wp_enqueue_style( 'color' );
	wp_enqueue_style( 'fonts' );
}

add_action( 'wp_enqueue_scripts', 'iribbon_styles' );

/**
* Load jQuery and register additional scripts.
*/ 
function response_scripts() {

	if ( !is_admin() ) {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	}
	
	$path =  get_template_directory_uri() ."/core/library";
	
	wp_register_script( 'orbit' ,$path.'/js/orbit/jquery.orbit.js');
	wp_register_script( 'bootstrap' ,$path.'/js/bootstrap/bootstrap.min.js');
	wp_register_script( 'menu' ,$path.'/js/menu.js');
	wp_register_script( 'plusone' ,$path.'/js/plusone.js');
	wp_register_script( 'mobilemenu' ,$path.'/js/mobilemenu.js');
	wp_register_script( 'html5shiv', $path.'/js/html5shiv.js' );
	wp_register_script( 'oembed' ,$path.'/js/oembed-twitter.js');
	
	wp_enqueue_script ('orbit');
	wp_enqueue_script ('bootstrap');
	wp_enqueue_script ('menu');
	wp_enqueue_script ('plusone');
	wp_enqueue_script ('mobilemenu');
	wp_enqueue_script ('html5shiv');
	wp_enqueue_script ('oembed');
	
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	
}
	
add_action('wp_enqueue_scripts', 'response_scripts');	

/**
* Adds "untitled" to posts with no title.
*
* @since 1.0
*/
add_filter('the_title', 'response_title');

function response_title($title) {

	if ($title == '') {
		return 'Untitled';
	} else {
		return $title;
	}
}

/**
* Truncate next/previous post link text for post pagination.
*
* @since 1.0
*/
function response_shorten_linktext($linkstring,$link) {
	$characters = 33;
	$displayedTitle = preg_match('/<a.*?>(.*?)<\/a>/is',$linkstring,$matches) ? $matches[1] : "";
	$newTitle = shorten_with_ellipsis($displayedTitle,$characters);
	return str_replace('>'.$displayedTitle.'<','>'.$newTitle.'<',$linkstring);
}

function shorten_with_ellipsis($inputstring,$characters) {
  return (strlen($inputstring) >= $characters) ? substr($inputstring,0,($characters-3)) . '...' : $inputstring;
}

add_filter('previous_post_link','response_shorten_linktext',10,2);
add_filter('next_post_link','response_shorten_linktext',10,2);

/**
* Comment function
*
* @since 1.0
*/
function response_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <?php echo get_avatar( $comment, 48 ); ?>

         <?php printf(__('<cite class="fn">%s</cite> <span class="says"></span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.', 'iribbon' ) ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s', 'iribbon' ), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)', 'iribbon' ),'  ','') ?></div>

      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
}

/**
* Breadcrumbs function
*
* @since 1.0
*/
function response_breadcrumbs() {
  
  $delimiter = "/";
  $home = 'Home'; // text for the 'Home' link
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  if ( !is_home() && !is_front_page() && !is_attachment() || is_paged() ) {
 
    echo '<div class="row-fluid"><div id="crumbs" class="span12"><div class="crumbs_text">';
 
    global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . 'Archive for category "' . single_cat_title('', false) . '"' . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
         echo is_wp_error( $cat_parents = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ') ) ? '' : $cat_parents;
        echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo is_wp_error( $cat_parents = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ') ) ? '' : $cat_parents;
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page', 'iribbon') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
 
    echo '</div></div></div>';
 
  }
}
/* set the title tag */
function response_title_tag_filter( $old_title ) {
	global $options, $ir_themeslug, $post; 
	
	
	
	$blogtitle = ($options->get($ir_themeslug.'_home_title'));
	if (!is_404()) {
		$title = get_post_meta($post->ID, 'seo_title' , true);
	}
	else {
		$title = $oldtitle;
	}
	
	if (function_exists('is_tag') && is_tag()) { /*Title for tags */
		$title_tag = get_bloginfo('name').' - '.single_tag_title("Tag Archive for &quot;", FALSE).'&quot;  ';
	}
	elseif( is_feed() ) {
		$title_tag = '';
	}
	elseif (is_archive()) { /*Title for archives */ 
		$title_tag = get_bloginfo('name').$old_title.' Archive '; 
	}    
	elseif (is_search()) { /*Title for search */ 
		$title_tag = get_bloginfo('name').' - Search for &quot;'.get_search_query().'&quot;  '; 
	}    
	elseif (is_404()) { /*Title for 404 */
		$title_tag = get_bloginfo('name').' - Not Found'; 
	}
	elseif (is_front_page() AND !is_page() AND $blogtitle == '') { /*Title if front page is latest posts and no custom title */
		$title_tag = get_bloginfo('name').' - '.get_bloginfo('description'); 
	}
	elseif (is_front_page() AND !is_page() AND $blogtitle != '') { /*Title if front page is latest posts with custom title */
		$title_tag = get_bloginfo('name').' - '.$blogtitle ; 
	}
	elseif (is_front_page() AND is_page() AND $title == '') { /*Title if front page is static page and no custom title */
		$title_tag = get_bloginfo('name').' - '.bloginfo('description'); 
	}
	elseif (is_front_page() AND is_page() AND $title != '') { /*Title if front page is static page with custom title */
		$title_tag = get_bloginfo('name').' - '.$title ; 
	}
	elseif (is_page() AND $title == '') { /*Title if static page is static page with no custom title */
		$title_tag = get_bloginfo('name').$old_title; 
	}
	elseif (is_page() AND $title != '') { /*Title if static page is static page with custom title */
		$title_tag = get_bloginfo('name').' - '.$title ; 
	}
	elseif (is_page() AND is_front_page() AND $blogtitle == '') { /*Title if blog page with no custom title */
		$title_tag = get_bloginfo('name').$old_title; 
	}
	elseif ($blogtitle != '') { /*Title if blog page with custom title */ 
		$title_tag = get_bloginfo('name').' - '.$blogtitle ; 
	}
	else { /*Title if blog page without custom title */
		$title_tag = get_bloginfo('name').$old_title; 
	}
	return $title_tag;
}

add_filter( 'wp_title', 'response_title_tag_filter', 10, 3 )

/**
* End
*/
		    
?>