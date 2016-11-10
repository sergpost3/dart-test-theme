<?php
/**
 * dart-theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package dart-theme
 */

if ( ! function_exists( 'dart_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dart_theme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on dart-theme, use a find and replace
	 * to change 'dart-theme' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'dart-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'dart-theme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'dart_theme_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Set uo custom logo
	add_theme_support( 'custom-logo', array(
		'height' => 65,
		'width' => 172,
		'flex-height' => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'dart_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dart_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dart_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'dart_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dart_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dart-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'dart-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'dart_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dart_theme_scripts() {
	wp_enqueue_style( 'dart-theme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'dart-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'dart-theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/*
	 * Load jQuery and script for loading more posts
	 * Only in the front page
	 */
	if ( is_front_page() ) {
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), false, true );

		wp_localize_script( 'custom-js', 'custom_js',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'custom_js_nonce' )
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'dart_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/*
 * Function, which returns HTML of posts by AJAX
 */
function load_more_posts() {
	if ( ! wp_verify_nonce( $nonce = $_POST['nonce'], 'custom_js_nonce' ) )
		die ( 'Error nonce!' );

	$args = array(
		'paged' => intval( $_POST['page'] ) + 1
	);

	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ) :
		while( $the_query->have_posts() ) :
			$the_query->the_post();
			get_template_part( 'template-parts/content', get_post_format( $the_query->post ) );
		endwhile;
	else :
		echo "lpage";
	endif;

	wp_reset_query();

	exit;
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

/*
 * Customize links and headers in site
 */
function dart_theme_customizer_init( $wp_customize ) {

	// Links color
	$wp_customize->add_setting(
		'all_links_color',
		array(
			'default'  => '#4169e1',
			'transport' => 'postMessage'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'all_links_color',
			array(
				'label' => __( 'Links color', 'dart-theme' ),
				'section' => 'colors',
				'settings' => 'all_links_color'
			)
		)
	);

	// H2 color
	$wp_customize->add_setting(
		'headers_h2_color',
		array(
			'default' => '#404040',
			'transport' => 'postMessage'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'headers_h2_color',
			array(
				'label' => __( 'H2 color', 'dart-theme' ),
				'section' => 'colors',
				'settings' => 'headers_h2_color'
			)
		)
	);
}
add_action( 'customize_register', 'dart_theme_customizer_init' );

/*
 * Custom CSS
 */
function dart_theme_customizer_css() {
	echo '<style>';
	echo 'a { color: ' . get_theme_mod( 'all_links_color' ) . '; }';
	echo 'h2 { color: ' . get_theme_mod( 'headers_h2_color' ) . '; }';
	echo '</style>';
}
add_action( 'wp_head', 'dart_theme_customizer_css' );

/*
 * Customizer Live preview
 */
function dart_theme_customizer_live() {
	wp_enqueue_script( 'dart-theme-customizer', get_stylesheet_directory_uri() . '/js/theme-customizer.js', array( 'jquery', 'customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'dart_theme_customizer_live' );