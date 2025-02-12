<?php
/**
 * Block class file.
 *
 * @package Starter_FSE_Theme
 */

namespace Starter_FSE_Theme\Inc;

use Starter_FSE_Theme\Inc\Traits\Singleton;

/**
 * Class Blocks
 *
 * @since 1.0.0
 */
class Blocks {

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

		add_action( 'init', array( $this, 'register_blocks' ) );
		add_filter( 'block_categories_all', array( $this, 'add_custom_block_category' ), 10 );
		add_action( 'init', array( $this, 'add_custom_pattern_category' ) );
		add_action( 'init', array( $this, 'register_custom_block_styles' ) );
	}

	/**
	 * Register blocks.
	 *
	 * @since 1.0.0
	 *
	 * @action init
	 */
	public function register_blocks() {

		// Get all blocks folder name and register them.
		$blocks = glob( STARTER_FSE_THEME_TEMP_DIR . '/assets/build/blocks/*' );

		// Loop through all blocks and register them.
		foreach ( $blocks as $block ) {
			$block_name = basename( $block );

			if ( ! empty( $block_name ) ) {
				register_block_type( STARTER_FSE_THEME_BUILD_DIR . '/blocks/' . $block_name );
			}
		}
	}

	/**
	 * Add Custom Block Category.
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array
	 */
	public function add_custom_block_category( $categories ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'starter-block-theme',
					'title' => __( 'Starter Block (FSE) Theme', 'starter-block-theme' ),
				),
			)
		);
	}

	/**
	 * Add custom Pattern Category.
	 *
	 * @return void
	 */
	public function add_custom_pattern_category() {

		register_block_pattern_category(
			'starter-block-theme-patterns',
			array( 'label' => __( 'Starter Block (FSE) Theme Patterns', 'starter-block-theme' ) )
		);
	}

	/**
	 * Register header styles.
	 *
	 * @return void
	 */
	public function register_custom_block_styles() {

		$block_styles = array(
			array(
				'block' => 'core/button',
				'style' => array(
					'name'  => 'starter-block-theme-button-primary',
					'label' => __( 'Starter Block Theme Primary Button', 'starter-block-theme' ),
				),
			),
			array(
				'block' => 'core/categories',
				'style' => array(
					'name'  => 'category-list',
					'label' => __( 'Category List', 'starter-block-theme' ),
				),
			),
			array(
				'block' => 'core/query',
				'style' => array(
					'name'  => 'primary-query-loop',
					'label' => __( 'Primary Query Loop', 'starter-block-theme' ),
				),
			),
			array(
				'block' => 'core/query',
				'style' => array(
					'name'  => 'secondary-query-loop',
					'label' => __( 'Secondary Query Loop', 'starter-block-theme' ),
				),
			),
			array(
				'block' => 'core/query',
				'style' => array(
					'name'  => 'vertical-post-query-loop',
					'label' => __( 'Vertical Post Query Loop', 'starter-block-theme' ),
				),
			),
			array(
				'block' => 'core/query',
				'style' => array(
					'name'  => 'slider-query-loop',
					'label' => __( 'Slider Query Loop', 'starter-block-theme' ),
				),
			),
		);

		foreach ( $block_styles as $block_style ) {
			register_block_style( $block_style['block'], $block_style['style'] );
		}
	}
}
