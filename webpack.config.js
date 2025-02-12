const path = require("path");
const glob = require("glob");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const { getWebpackEntryPoints } = require("@wordpress/scripts/utils/config");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const cleanedDefaultPlugins = defaultConfig.plugins.filter((plugin) => {
	if (
		["RtlCssPlugin", "MiniCssExtractPlugin"].includes(plugin.constructor.name)
	) {
		return false;
	}
	return true;
});

// Function to generate block entries
const generateBlockEntries = () => {
	const blocks = glob.sync("assets/src/blocks/**/+(view|index).js");
	const blockEntries = {};

	blocks.forEach((file) => {
		const { dir, name } = path.parse(file);
		const isViewScript = name.includes("view");
		const entryKey = `${dir.replace("assets/src/", "")}/${
			isViewScript ? "view" : "index"
		}`;
		blockEntries[entryKey] = `./${file}`;
	});

	return blockEntries;
};

// Function to generate JS entries
const generateJsEntries = () => {
	const jsFiles = glob.sync("assets/src/js/*.js");
	const jsEntries = {};

	jsFiles.forEach((file) => {
		const { name } = path.parse(file);
		jsEntries[name] = `./${file}`;
	});

	return jsEntries;
};

// Generate block entries
const blockEntries = generateBlockEntries();
const jsEntries = generateJsEntries();

// Extend the default Webpack config
module.exports = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints(),
		...blockEntries,
		...jsEntries,
	},
	output: {
		path: path.resolve(__dirname, "build"),
		filename: (pathData) => {
			// Check if the entry point is from the src/blocks folder
			const isBlockEntry = Object.keys(blockEntries).some((key) =>
				pathData.chunk.name.startsWith(key),
			);
			return isBlockEntry ? "[name].js" : "js/[name].js";
		},
	},
	plugins: [
		...cleanedDefaultPlugins,
		new MiniCssExtractPlugin({
			filename: (pathData) => {
				// Check if the entry point is from the src/blocks folder
				const isBlockEntry = Object.keys(blockEntries).some((key) =>
					pathData.chunk.name.startsWith(key),
				);

				return isBlockEntry ? "[name].css" : "css/[name].css";
			},
		}),
	],
	module: {
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.css$/,
				use: [MiniCssExtractPlugin.loader, "css-loader"],
			},
		],
	},
};
