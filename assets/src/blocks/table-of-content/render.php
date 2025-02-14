<?php
/**
 * Table Of Content render callback.
 *
 * @package blog-theme-25
 */

$toc_headings = blog_theme_get_toc_headings();

if ( empty( $toc_headings ) ) {
	return;
}

?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( is_array( $toc_headings ) ) : ?>
		<ul>
			<?php foreach ( $toc_headings as $toc_heading ) : ?>
				<li>
					<a href="#<?php echo blog_theme_sanitize_h2( $toc_heading ); ?>">
						<?php echo esc_html( $toc_heading ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
