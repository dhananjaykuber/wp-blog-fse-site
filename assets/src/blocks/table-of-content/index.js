/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import Edit from "./edit";

/**
 * Styles
 */
import "./view.scss";

registerBlockType('blog-theme-25/table-of-content', {
	edit: Edit,
});
