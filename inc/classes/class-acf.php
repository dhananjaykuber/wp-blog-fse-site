<?php
/**
 * Load and save acf fields.
 *
 * @package Starter_FSE_Theme
 */

namespace Starter_FSE_Theme\Inc;

use Starter_FSE_Theme\Inc\Traits\Singleton;

/**
 * Class ACF
 *
 * @since 1.0.0
 */
class ACF {

	use Singleton;

	/**
	 * Constructor.
	 */
	protected function __construct() {

		// Setup hooks.
		$this->setup_hooks();
	}

	/**
	 * Setup hooks.
	 *
	 * @since 1.0.0
	 */
	public function setup_hooks() {

		add_filter( 'acf/settings/save_json', array( $this, 'acf_json_save_point' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'acf_json_load_point' ) );
	}

	/**
	 * Save acf fields.
	 *
	 * @return void
	 */
	public function acf_json_save_point() {

		return STARTER_FSE_THEME_TEMP_DIR . '/acf-json';
	}

	/**
	 * Load acf fields.
	 *
	 * @return array
	 */
	public function acf_json_load_point( $paths ) {

		unset( $paths[0] );
		$paths[] = STARTER_FSE_THEME_TEMP_DIR . '/acf-json';
		return $paths;
	}
}
