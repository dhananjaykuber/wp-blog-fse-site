#!/usr/bin/env node

/**
 * External dependencies
 */
const fs = require("fs");
const path = require("path");
const readLine = require("readline");

const rl = readLine.createInterface({
	input: process.stdin,
	output: process.stdout,
});

// Message styles
const message = {
	error: (message) => {
		return `\x1b[31m${message}\x1b[0m`;
	},
	success: (message) => {
		return `\x1b[32m${message}\x1b[0m`;
	},
	warning: (message) => {
		return `\x1b[33m${message}\x1b[0m`;
	},
	info: (message) => {
		return `\x1b[34m${message}\x1b[0m`;
	},
};

// Questions to ask
const questions = {
	blockTitle: {
		question: "\nEnter the block title:",
		default: "Example Block",
	},
	blockSlug: {
		question: "\nEnter the block slug:",
		default: "example-block",
	},
	blockNamespace: {
		question: "\nEnter the block namespace:",
		default: "starter-block-theme",
	},
	blockDescription: {
		question: "\nEnter the block description:",
		default: "A example block",
	},
	blockKeywords: {
		question: "\nEnter the block keywords:",
		default: "example,block",
	},
	blockIcon: {
		question: "\nEnter the block icon:",
		default: "smiley",
	},
	blockTextDomain: {
		question: "\nEnter the block text domain:",
		default: "starter-block-theme",
	},
	blockCategory: {
		question: `\nSelect the block category: \n${message.info(
			"1. text \n2. media \n3. embed \n4. design \n5. theme",
		)}`,
		default: 1,
		options: {
			1: "text",
			2: "media",
			3: "embed",
			4: "design",
			5: "theme",
		},
	},
};

/**
 * Prompts the user with a question and returns their answer or a default value.
 */
const generateQuestions = (question, defaultAnswer) => {
	return new Promise((resolve) => {
		rl.question(question, (answer) => {
			if (!answer) {
				rl.write(`${message.success(defaultAnswer)}\n`);
				resolve(defaultAnswer);
			}
			resolve(answer);
		});
	});
};

/**
 * Asks all the questions and returns the answers.
 */
const askAllQuestions = async () => {
	const answers = {};

	for (const question in questions) {
		if (questions.hasOwnProperty(question)) {
			answers[question] = await generateQuestions(
				`${questions[question].question} (${message.info(
					questions[question].default,
				)}): `,
				questions[question].default,
			);
		}
	}

	answers.blockCategory =
		questions.blockCategory.options[answers.blockCategory] || "text";

	return answers;
};

/**
 * Get all the mustache files in the block template directory.
 */
const getBlockTemplateFiles = (blockType) => {
	try {
		const blockPath = path.resolve(
			__dirname,
			`../../bin/block-scaffold/templates/${blockType}`,
		);

		return fs
			.readdirSync(blockPath)
			.map((file) => {
				return path.resolve(blockPath, file);
			})
			.filter((file) => {
				return fs.statSync(file).isFile() && path.extname(file) === ".mustache";
			});
	} catch (error) {
		console.log(message.error(`Error: ${error.message}`));
		process.exit(0);
	}
};

/**
 * Get the block directory path.
 */
const getBlockDir = () => {
	try {
		const blockDir = path.resolve(__dirname, "../../assets/src/blocks");

		if (!fs.existsSync(blockDir)) {
			fs.mkdirSync(blockDir);
		}

		return blockDir;
	} catch (error) {
		console.log(message.error(`Error: ${error.message}`));
		process.exit(0);
	}
};

/**
 * Copy the template files to the block directory.
 */
const copyTemplateFiles = (blockDirName, blockPath, blockFiles) => {
	try {
		const blockDir = `${blockPath}/${blockDirName}`;

		if (!fs.existsSync(blockDir)) {
			fs.mkdirSync(blockDir);
		}

		for (const file of blockFiles) {
			const fileName = path.basename(file, ".mustache");
			const filePath = `${blockDir}/${fileName}`;

			if (!fs.existsSync(filePath)) {
				fs.copyFileSync(file, filePath);
			}
		}

		return blockDir;
	} catch (error) {
		console.log(message.error(`Error: ${error.message}`));
		process.exit(0);
	}
};

/**
 * Run search and replace on the block files.
 */
const runSearchReplace = (blockDir, data) => {
	try {
		const blockFiles = fs.readdirSync(blockDir);

		blockFiles.forEach((file) => {
			const filePath = `${blockDir}/${file}`;

			if (fs.statSync(filePath).isFile()) {
				Object.keys(data).forEach((key) => {
					const regex = new RegExp(key, "g");
					const fileContent = fs.readFileSync(filePath, "utf8");
					const newContent = fileContent.replace(regex, data[key]);
					fs.writeFileSync(filePath, newContent);
				});
			}
		});
	} catch (error) {
		console.log(message.error(`Error: ${error.message}`));
		process.exit(0);
	}
};

/**
 * Generate the block.json file.
 */
const generateBlockJSONFile = async (blockType, blockPath, blockData) => {
	const blockJSON = {
		$schema: "https://schemas.wp.org/trunk/block.json",
		apiVersion: 3,
		name: `${blockData.blockNamespace}/${blockData.blockSlug}`,
		version: "0.1.0",
		title: blockData.blockTitle,
		category: blockData.blockCategory,
		icon: blockData.blockIcon,
		description: blockData.blockDescription,
		keywords: blockData.blockKeywords.split(","),
		example: {},
		supports: {
			html: false,
		},
		attributes: {},
		textdomain: blockData.blockTextDomain,
		editorScript: "file:./index.js",
		editorStyle: "file:./index.css",
		style: "file:./view.css",
		viewScript: "file:./view.js",
	};

	if (blockType !== "static-block") {
		blockJSON.render = "file:./render.php";
	}

	try {
		fs.writeFileSync(
			`${blockPath}/block.json`,
			JSON.stringify(blockJSON, null, 2),
		);
	} catch (error) {
		console.log(message.error(`Error: ${error.message}`));
		process.exit(0);
	}
};

/**
 * Generate the block.
 */
const generateBlock = async (blockType) => {
	const blockData = await askAllQuestions();

	const chunksToReplace = {
		"{{title}}": blockData.blockTitle,
		"{{slug}}": blockData.blockSlug,
		"{{namespace}}": blockData.blockNamespace,
		"{{description}}": blockData.blockDescription,
		"{{keywords}}": blockData.blockKeywords,
		"{{icon}}": blockData.blockIcon,
		"{{textDomain}}": blockData.blockTextDomain,
		"{{category}}": blockData.blockCategory,
	};

	console.log(
		message.info(
			`\nGenerating ${blockType} ${message.warning(blockData.blockTitle)}...\n`,
		),
	);

	const templateFiles = getBlockTemplateFiles(blockType);

	const newBlockDir = copyTemplateFiles(
		blockData.blockSlug,
		getBlockDir(),
		templateFiles,
	);

	runSearchReplace(newBlockDir, chunksToReplace);

	generateBlockJSONFile(blockType, newBlockDir, blockData);

	console.log(
		`${message.success("\nBlock")} ${message.warning(
			blockData.blockTitle,
		)} ${message.success("generated successfully!")}`,
	);

	rl.close();
};

/**
 * Initialize the script.
 */
const init = () => {
	rl.question(
		`\nWhat type of block would you like to create? \n\n${message.info(
			"1. Dynamic Block \n2. Static Block \n3. Server Side Rendered Block \n4. Exit",
		)} \n\nEnter your choice: `,
		async (option) => {
			switch (parseInt(option)) {
				case 1:
					await generateBlock("dynamic-block");
					break;
				case 2:
					await generateBlock("static-block");
					break;
				case 3:
					await generateBlock("server-side-rendered-block");
					break;
				case 4:
					console.log(message.info("Exiting..."));
					rl.close();
					break;
				default:
					console.log(message.error("Invalid option selected"));
					break;
			}
		},
	);
};

init();
