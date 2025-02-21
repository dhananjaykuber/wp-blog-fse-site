<?php
/**
 * Theme bootstrap file.
 *
 * @package Starter_FSE_Theme
 */

namespace Starter_FSE_Theme\Inc;

use Starter_FSE_Theme\Inc\Traits\Singleton;

/**
 * Class Assets
 *
 * @since 1.0.0
 */
class Assets {

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

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'register_block_assets' ), 8 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_assets' ), 12 );
	}

	/**
	 * Register block assets.
	 *
	 * @return void
	 */
	public function register_block_assets() {

		$this->register_script( 'starter-fse-theme-block-scripts', 'editor.js' );
	}

	/**
	 * Enqueue Block assets.
	 *
	 * @return void
	 */
	public function enqueue_block_assets() {

		wp_enqueue_script( 'starter-fse-theme-block-scripts' );
		wp_enqueue_style( 'starter-fse-theme-block-styles', STARTER_FSE_THEME_BUILD_URI . '/css/editor.css', array(), filemtime( STARTER_FSE_THEME_BUILD_DIR . '/css/editor.css' ) );
	}

	/**
	 * Register assets.
	 *
	 * @since 1.0.0
	 *
	 * @action wp_enqueue_scripts
	 */
	public function register_assets() {

		$this->register_style( 'starter-fse-theme-styles', 'main.css' );
		$this->register_script( 'starter-fse-theme-scripts', 'main.js', array( 'jquery' ) );

		// Slick slider.
		wp_register_style(
			'slick-carousel',
			'//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
			array(),
			'1.8.1'
		);
		wp_register_style(
			'slick-carousel-theme',
			'//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css',
			array( 'slick-carousel' ),
			'1.8.1'
		);
		wp_register_script(
			'slick-carousel',
			'//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
			array( 'jquery' ),
			'1.8.1',
			true
		);

		if ( is_single() || is_archive() ) {
			$this->localize_post_categories();
		}
	}

	/**
	 * Enqueue JS and CSS in frontend.
	 *
	 * @return void
	 */
	public function enqueue_assets() {

		wp_enqueue_style( 'starter-fse-theme-styles' );
		wp_enqueue_style( 'slick-carousel' );
		wp_enqueue_style( 'slick-carousel-theme' );

		wp_enqueue_script( 'starter-fse-theme-scripts' );
		wp_enqueue_script( 'slick-carousel' );

		wp_localize_script(
			'starter-fse-theme-scripts',
			'starterThemeGlobal',
			array(
				'siteURL' => get_home_url(),
			)
		);
	}

	/**
	 * Get asset dependencies and version info from {handle}.asset.php if exists.
	 *
	 * @param string $file File name.
	 * @param array  $deps Script dependencies to merge with.
	 * @param string $ver  Asset version string.
	 *
	 * @return array
	 */
	public function get_asset_meta( $file, $deps = array(), $ver = false ) {

		$asset_meta_file = sprintf( '%s/js/%s.asset.php', untrailingslashit( STARTER_FSE_THEME_BUILD_DIR ), basename( $file, '.' . pathinfo( $file )['extension'] ) );
		$asset_meta      = is_readable( $asset_meta_file )
			? require $asset_meta_file
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $file, $ver ),
			);

		$asset_meta['dependencies'] = array_merge( $deps, $asset_meta['dependencies'] );

		return $asset_meta;
	}

	/**
	 * Register a new script.
	 *
	 * @param string           $handle    Name of the script. Should be unique.
	 * @param string|bool      $file       script file, path of the script relative to the assets/build/ directory.
	 * @param array            $deps      Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param string|bool|null $ver       Optional. String specifying script version number, if not set, filetime will be used as version number.
	 * @param bool             $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>.
	 *                                    Default 'false'.
	 * @return bool Whether the script has been registered. True on success, false on failure.
	 */
	public function register_script( $handle, $file, $deps = array(), $ver = false, $in_footer = true ) {

		$file_path = sprintf( '%s/js/%s', STARTER_FSE_THEME_BUILD_DIR, $file );

		if ( ! \file_exists( $file_path ) ) {
			return false;
		}

		$src        = sprintf( STARTER_FSE_THEME_BUILD_URI . '/js/%s', $file );
		$asset_meta = $this->get_asset_meta( $file, $deps );

		return wp_register_script( $handle, $src, $asset_meta['dependencies'], $asset_meta['version'], $in_footer );
	}

	/**
	 * Register a CSS stylesheet.
	 *
	 * @param string           $handle Name of the stylesheet. Should be unique.
	 * @param string|bool      $file    style file, path of the script relative to the assets/build/ directory.
	 * @param array            $deps   Optional. An array of registered stylesheet handles this stylesheet depends on. Default empty array.
	 * @param string|bool|null $ver    Optional. String specifying script version number, if not set, filetime will be used as version number.
	 * @param string           $media  Optional. The media for which this stylesheet has been defined.
	 *                                 Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media queries like
	 *                                 '(orientation: portrait)' and '(max-width: 640px)'.
	 *
	 * @return bool Whether the style has been registered. True on success, false on failure.
	 */
	public function register_style( $handle, $file, $deps = array(), $ver = false, $media = 'all' ) {

		$file_path = sprintf( '%s/css/%s', STARTER_FSE_THEME_BUILD_DIR, $file );

		if ( ! \file_exists( $file_path ) ) {
			return false;
		}

		$src        = sprintf( STARTER_FSE_THEME_BUILD_URI . '/css/%s', $file );
		$asset_meta = $this->get_asset_meta( $file, $deps );

		return wp_register_style( $handle, $src, $asset_meta['dependencies'], $asset_meta['version'], $media );
	}

	/**
	 * Get file version.
	 *
	 * @param string             $file File path.
	 * @param int|string|boolean $ver  File version.
	 *
	 * @return bool|false|int
	 */
	public function get_file_version( $file, $ver = false ) {

		if ( ! empty( $ver ) ) {
			return $ver;
		}

		$file_path = sprintf( '%s/js/%s', STARTER_FSE_THEME_BUILD_DIR, $file );

		return file_exists( $file_path ) ? filemtime( $file_path ) : false;
	}

	/**
	 * Localize post categories.
	 *
	 * @return void
	 */
	private function localize_post_categories() {
		$localized_categories = array();

		if ( is_single() ) {
			global $post;

			$categories = get_the_category( $post->ID );

			$localized_categories = array_map(
				function ( $category ) {
					return $category->name;
				},
				$categories
			);
		} elseif ( is_category() ) {
			$current_category = get_queried_object();

			$localized_categories = array( $current_category->name );
		}

		wp_localize_script(
			'starter-fse-theme-scripts',
			'starter_fse_theme',
			array(
				'categories' => $localized_categories,
			)
		);
	}
}
