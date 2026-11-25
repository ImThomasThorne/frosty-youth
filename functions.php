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

// Enqueue mobile submenu accordion script.
add_action( 'wp_enqueue_scripts', 'frost_enqueue_mobile_submenu' );
function frost_enqueue_mobile_submenu() {

	wp_enqueue_script( 'frost-mobile-submenu', get_template_directory_uri() . '/js/mobile-submenu.js', array(), wp_get_theme()->get( 'Version' ), true );

}

// Enqueue script to widen the GiveWP donation form, which renders inside its
// own same-origin iframe and so can't be reached by the theme's stylesheet.
add_action( 'wp_enqueue_scripts', 'frost_enqueue_givewp_form_width' );
function frost_enqueue_givewp_form_width() {

	wp_enqueue_script( 'frost-givewp-form-width', get_template_directory_uri() . '/js/givewp-form-width.js', array(), wp_get_theme()->get( 'Version' ), true );

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


/**
 * Remove 'Archives:' and similar prefixes from event archive titles.
 * Covers the page heading, the browser <title> tag, and The Events Calendar's
 * own title output.
 */
function frosty_youth_clean_archive_title( $title ) {
	return preg_replace( '/^[^:]+:\s*/', '', $title );
}
add_filter( 'get_the_archive_title', 'frosty_youth_clean_archive_title' );
add_filter( 'tribe_get_events_title', 'frosty_youth_clean_archive_title' );

add_filter( 'document_title_parts', function( $parts ) {
	if ( is_archive() && isset( $parts['title'] ) ) {
		$parts['title'] = frosty_youth_clean_archive_title( $parts['title'] );
	}
	return $parts;
} );

// Replace the WordPress logo on the login screen
function cya_login_logo() {
    ?>
    <style>
        #login h1 a,
        .login h1 a {
            background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/login-logo.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            max-width: 480px;
            height: 150px;
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'cya_login_logo' );

// Point the logo link at your own site instead of wordpress.org
add_filter( 'login_headerurl', function() {
    return home_url();
} );

// Change the logo's link text (used by screen readers)
add_filter( 'login_headertext', function() {
    return get_bloginfo( 'name' );
} );
/* ------------------------------------------------------------------
 * Classic menus (Appearance → Menus)
 *
 * Lets the menus be edited under Appearance → Menus while keeping the
 * Navigation blocks (and their styling and mobile overlay) in the
 * header and footer. Each Navigation block below takes its links from
 * the menu assigned to its location. If no menu is assigned, the block
 * shows its own links as before.
 * ------------------------------------------------------------------ */

// Menu locations shown under Appearance → Menus → Manage Locations.
add_action( 'after_setup_theme', 'cya_register_menus' );
function cya_register_menus() {
	register_nav_menus( array(
		'main-menu'   => __( 'Main Menu', 'frosty-youth' ),
		'quick-links' => __( 'Quick Links', 'frosty-youth' ),
		'footer-menu' => __( 'Footer', 'frosty-youth' ),
	) );
}

// Which Navigation block uses which location, matched by the block's
// navigation menu ID ("ref" in the header/footer templates).
function cya_nav_block_locations() {
	return array(
		2476 => 'main-menu',   // Header (desktop and mobile)
		4189 => 'quick-links', // Footer Quick Links column
		2642 => 'footer-menu', // Footer bottom bar
	);
}

add_filter( 'render_block_data', 'cya_nav_block_use_classic_menu' );
function cya_nav_block_use_classic_menu( $block ) {
	if ( 'core/navigation' !== $block['blockName'] || empty( $block['attrs']['ref'] ) ) {
		return $block;
	}

	$locations = cya_nav_block_locations();
	$ref       = (int) $block['attrs']['ref'];
	if ( ! isset( $locations[ $ref ] ) ) {
		return $block;
	}
	$location = $locations[ $ref ];

	// Always add a class so each menu can be styled on its own,
	// e.g. .nav-quick-links, .nav-footer-menu, .nav-main-menu.
	$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] . ' ' : '';
	$block['attrs']['className'] = $class . 'nav-' . $location;

	// Stop here if no menu (or an empty one) is assigned to this location.
	$assigned = get_nav_menu_locations();
	if ( empty( $assigned[ $location ] ) ) {
		return $block;
	}
	$menu_items = wp_get_nav_menu_items( $assigned[ $location ], array( 'update_post_term_cache' => false ) );
	if ( empty( $menu_items ) ) {
		return $block;
	}

	$link_blocks = cya_menu_items_to_blocks( $menu_items );

	// Keep anything in the Navigation block that isn't a link (e.g. a
	// button) and put the classic menu's links where the links were.
	$nav_post    = get_post( $ref );
	$inner       = ( $nav_post && 'wp_navigation' === $nav_post->post_type ) ? parse_blocks( $nav_post->post_content ) : array();
	$link_types  = array( 'core/navigation-link', 'core/navigation-submenu', 'core/page-list', 'core/home-link' );
	$new_inner   = array();
	$links_added = false;
	foreach ( $inner as $inner_block ) {
		if ( empty( $inner_block['blockName'] ) ) {
			continue; // Skip blank space between blocks.
		}
		if ( in_array( $inner_block['blockName'], $link_types, true ) ) {
			if ( ! $links_added ) {
				$new_inner   = array_merge( $new_inner, $link_blocks );
				$links_added = true;
			}
			continue;
		}
		$new_inner[] = $inner_block;
	}
	if ( ! $links_added ) {
		$new_inner = array_merge( $link_blocks, $new_inner );
	}

	// Render these blocks instead of the saved Navigation menu.
	unset( $block['attrs']['ref'] );
	$block['innerBlocks']  = $new_inner;
	$block['innerContent'] = array();

	return $block;
}

// Turn classic menu items into Navigation link / submenu blocks.
function cya_menu_items_to_blocks( $menu_items, $parent_id = 0 ) {
	$blocks = array();

	foreach ( $menu_items as $item ) {
		if ( (int) $item->menu_item_parent !== (int) $parent_id ) {
			continue;
		}

		$children = cya_menu_items_to_blocks( $menu_items, $item->ID );
		$classes  = array_filter( (array) $item->classes );

		// Dropdowns need this class for the mobile accordion
		// (js/mobile-submenu.js and the .mobile-submenu styles).
		if ( $children ) {
			$classes[] = 'mobile-submenu';
		}

		$attrs = array(
			'label'         => $item->title,
			'url'           => $item->url,
			'id'            => (int) $item->object_id,
			'kind'          => str_replace( '_', '-', $item->type ),
			'type'          => $item->object,
			'opensInNewTab' => '_blank' === $item->target,
			'rel'           => $item->xfn,
			'title'         => $item->attr_title,
			'description'   => $item->description,
		);
		if ( $classes ) {
			$attrs['className'] = implode( ' ', $classes );
		}

		$blocks[] = array(
			'blockName'    => $children ? 'core/navigation-submenu' : 'core/navigation-link',
			'attrs'        => $attrs,
			'innerBlocks'  => $children,
			'innerHTML'    => '',
			'innerContent' => array(),
		);
	}

	return $blocks;
}
