<?php


if ( ! function_exists( 'frost_setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since 0.8.0
	 *
	 * @return void
	 */
	function frost_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'frosty-youth', get_template_directory() . '/languages' );

		// Enqueue editor stylesheet.
		add_editor_style( get_template_directory_uri() . '/style.css' );

	}
}
add_action( 'after_setup_theme', 'frost_setup' );

// Enqueue stylesheet.
add_action( 'wp_enqueue_scripts', 'frost_enqueue_stylesheet' );
function frost_enqueue_stylesheet() {

	wp_enqueue_style( 'frost', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );

}

// Enqueue search overlay script.
add_action( 'wp_enqueue_scripts', 'frost_enqueue_search_overlay' );
function frost_enqueue_search_overlay() {

	wp_enqueue_script( 'frost-search-overlay', get_template_directory_uri() . '/js/search-overlay.js', array(), wp_get_theme()->get( 'Version' ), true );

}

// Enqueue editor styles to hide unwanted button styles
add_action( 'enqueue_block_editor_assets', 'frost_editor_styles' );
function frost_editor_styles() {
	wp_add_inline_style( 'wp-edit-blocks', '
		/* Hide unwanted button style options in editor */
		.block-editor-block-styles__item[aria-label*="Fill"],
		.block-editor-block-styles__item[aria-label*="3D"],
		.block-editor-block-styles__item[aria-label*="Shadow"] {
			display: none !important;
		}
	' );
}

/**
 * Register block styles.
 *
 * @since 0.9.2
 */
function frost_register_block_styles() {

	$block_styles = array(
		'core/button' => array(
			'outline' => __( 'Outline', 'frosty-youth' ),
			'secondary' => __( 'Secondary', 'frosty-youth' ),
		),
		'core/columns' => array(
			'columns-reverse' => __( 'Reverse', 'frosty-youth' ),
		),
		'core/group' => array(
			'shadow-light' => __( 'Shadow', 'frosty-youth' ),
			'shadow-solid' => __( 'Solid', 'frosty-youth' ),
		),
		'core/list' => array(
			'no-disc' => __( 'No Disc', 'frosty-youth' ),
		),
		'core/quote' => array(
			'shadow-light' => __( 'Shadow', 'frosty-youth' ),
			'shadow-solid' => __( 'Solid', 'frosty-youth' ),
		),
		'core/social-links' => array(
			'outline' => __( 'Outline', 'frosty-youth' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action( 'init', 'frost_register_block_styles' );

/**
 * Unregister default button styles.
 *
 * @since 2.6.0
 */
function frost_unregister_button_styles() {
	// Unregister WordPress core button styles
	unregister_block_style( 'core/button', 'fill' );
	unregister_block_style( 'core/button', 'default' );

	// Unregister theme-specific styles we don't want
	unregister_block_style( 'core/social-links', 'outline' );
}
add_action( 'init', 'frost_unregister_button_styles', 100 );

/**
 * Register block pattern categories.
 *
 * @since 1.0.4
 */
function frost_register_block_pattern_categories() {

	register_block_pattern_category(
		'cya-blocks',
		array(
			'label'       => __( 'CYA Blocks', 'frosty-youth' ),
			'description' => __( 'Blocks used by CYA', 'frosty-youth' ),
		)
	);

}

add_action( 'init', 'frost_register_block_pattern_categories' );


/**
 * Remove core block patterns
 */

add_action( 'after_setup_theme', function() {
    remove_theme_support( 'core-block-patterns' );
} );

add_filter( 'should_load_remote_block_patterns', '__return_false' );


/**
 * Current Year Shortcode
 */

 function current_year() {
	return wp_date('Y');
}

add_shortcode('current-year', 'current_year');


/**
 * Github Updater
 */

 require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/ImThomasThorne/frosty-youth',
	__FILE__,
	'frosty-youth'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('trunk');