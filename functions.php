<?php
/**
 * Starter Block Theme functions and definitions
 *
 * @package Starter_FSE_Theme
 */

if ( ! defined( 'STARTER_FSE_THEME_VERSION' ) ) :
	define( 'STARTER_FSE_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
endif;

if ( ! defined( 'STARTER_FSE_THEME_TEMP_DIR' ) ) :
	define( 'STARTER_FSE_THEME_TEMP_DIR', untrailingslashit( get_template_directory() ) );
endif;

if ( ! defined( 'STARTER_FSE_THEME_TEMP_URI' ) ) :
	define( 'STARTER_FSE_THEME_TEMP_URI', untrailingslashit( get_template_directory_uri() ) );
endif;

if ( ! defined( 'STARTER_FSE_THEME_BUILD_URI' ) ) :
	define( 'STARTER_FSE_THEME_BUILD_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build' );
endif;

if ( ! defined( 'STARTER_FSE_THEME_BUILD_DIR' ) ) :
	define( 'STARTER_FSE_THEME_BUILD_DIR', untrailingslashit( get_template_directory() ) . '/assets/build' );
endif;

require_once STARTER_FSE_THEME_TEMP_DIR . '/inc/helpers/autoloader.php';
require_once STARTER_FSE_THEME_TEMP_DIR . '/inc/helpers/custom-functions.php';

/**
 * Theme bootstrap instance.
 *
 * @return object Theme bootstrap instance.
 */

\Starter_FSE_Theme\Inc\Theme::get_instance();
