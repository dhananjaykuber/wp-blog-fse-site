<?php
/**
 * Define custom functions for Starter Block Theme.
 *
 * @package Starter_FSE_Theme
 */

/**
 * Extract and localize H2 heading text from a parsed block.
 *
 * @param array $parsed_block The parsed block containing the H2 heading.
 * @return string|null Localized H2 heading text or null if not found.
 */
function blog_theme_get_h2_name( $parsed_block ) {

	if ( preg_match( '/<h2[^>]*>(.*?)<\/h2>/', $parsed_block['innerContent'][0], $matches ) ) {
		return wp_strip_all_tags( $matches[1] );
	}
}
/**
 * Retrieve all localized H2 headings from the current post content.
 *
 * @return array Array of localized H2 heading texts.
 */
function blog_theme_get_toc_headings() {
	global $post;
	$toc_headings = array();

	if ( ! $post ) {
		return $toc_headings;
	}

	$parsed_blocks = array();

	$parsed_blocks = parse_blocks( $post->post_content );

	$parsed_blocks = array_filter(
		$parsed_blocks,
		function ( $block ) {
			return ( 'core/heading' === $block['blockName'] );
		}
	);

	foreach ( $parsed_blocks as $parsed_block ) {
		$h2_name = blog_theme_get_h2_name( $parsed_block );

		if ( $h2_name ) {
			$toc_headings[] = $h2_name;
		}
	}

	return $toc_headings;
}

/**
 * Sanitize and localize an H2 heading for use as an HTML ID or URL slug.
 *
 * @param string $heading The H2 heading text to sanitize.
 * @return string Sanitized and localized heading text.
 */
function blog_theme_sanitize_h2( $heading ) {

	$heading = preg_replace( '/[^a-zA-Z\s]/', '', $heading );
	$heading = sanitize_title( $heading );

	return $heading;
}
