<?php
/**
 * Table Of Content file.
 *
 * @package Starter_FSE_Theme
 */

namespace Starter_FSE_Theme\Inc;

use Starter_FSE_Theme\Inc\Traits\Singleton;

/**
 * Class Toc.
 *
 * @since 1.0.0
 */
class Toc {

	use Singleton;

	/**
	 * Constructor.
	 */
	protected function __construct() {

		$this->setup_hooks();
	}

	/**
	 * Setup hooks.
	 *
	 * @since 1.0.0
	 */
	public function setup_hooks() {

		add_action( 'render_block_core/heading', array( $this, 'update_h2_tag_for_toc' ), 10, 2 );
	}

	public function update_h2_tag_for_toc( $block_content, $block ) {

		if ( ! 'core/heading' === $block['blockName'] ) {
			return $block_content;
		}

		$processor = new \WP_HTML_Tag_Processor( $block_content );

		while ( $processor->next_tag( array( 'tag_name' => 'h2' ) ) ) {
			$heading_text = $this->get_tag_content( $block_content );
			$heading_id   = blog_theme_sanitize_h2( $heading_text );

			$processor->set_attribute( 'id', $heading_id );
		}

		return $processor->get_updated_html();
	}

	private function get_tag_content( $block_content ) {

		if ( preg_match( '/<h2[^>]*>(.*?)<\/h2>/is', $block_content, $matches ) ) {
			return strip_tags( $matches[1] );
		}

		return '';
	}
}
