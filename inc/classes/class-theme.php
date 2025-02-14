<?php
/**
 * Theme bootstrap file.
 *
 * @package Starter_FSE_Theme
 */

namespace Starter_FSE_Theme\Inc;

use Starter_FSE_Theme\Inc\Traits\Singleton;

/**
 * Class Theme.
 *
 * @since 1.0.0
 */
class Theme {

	use Singleton;

	/**
	 * Constructor.
	 */
	protected function __construct() {

		// Instantiate classes.
		Assets::get_instance();
		Blocks::get_instance();
		ACF::get_instance();
		Toc::get_instance();

		// Setup hooks.
		$this->setup_hooks();
	}

	/**
	 * Setup hooks.
	 *
	 * @since 1.0.0
	 */
	public function setup_hooks() {

		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );
	}

	/**
	 * Add required theme support.
	 *
	 * @since 1.0.0
	 */
	public function theme_support() {

		// Add support for core block styles.
		add_theme_support( 'wp-block-styles' );
	}
}
