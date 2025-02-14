/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

/**
 * Styles
 */
import "./editor.scss";

export default function Edit() {
	const blockProps = useBlockProps();

	const headings = [
		"Getting Started",
		"Key Features",
		"How to Use",
		"Best Practices",
		"Conclusion",
	];

	return (
		<div {...blockProps}>
			<ul>
				{headings.map((heading, index) => (
					<li key={index}>
						<a
							href={`#${heading.toLowerCase().replace(/\s+/g, "-")}`}
							onClick={(e) => e.preventDefault()}
						>
							{heading}
						</a>
					</li>
				))}
			</ul>
		</div>
	);
}
