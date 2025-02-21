document.addEventListener("DOMContentLoaded", function () {
	if (!starter_fse_theme || !starter_fse_theme?.categories) {
		return;
	}

	const categoriesList = document.querySelectorAll(
		".is-style-category-list li a",
	);

	categoriesList?.forEach((category) => {
		if (starter_fse_theme.categories.includes(category.textContent)) {
			category.parentElement?.classList.add("current-cat");
		}
	});
});

document.addEventListener("DOMContentLoaded", function () {
	const categoryDropdown = document.querySelector(
		".wp-block-categories-dropdown select",
	);

	if (!categoryDropdown) return;

	const defaultOption = categoryDropdown.querySelector('option[value="-1"]');
	const options = categoryDropdown.querySelectorAll("option");

	if (defaultOption && starter_fse_theme?.categories) {
		const currentCategory = Array.from(options).find((option) =>
			starter_fse_theme.categories.includes(option.textContent),
		);

		if (currentCategory) {
			defaultOption.textContent = currentCategory.textContent;
		}
	}
});
