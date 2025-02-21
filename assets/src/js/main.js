import "../scss/main.scss";

import "./utils/slick-slider";

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
