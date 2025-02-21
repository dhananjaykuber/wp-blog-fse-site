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

document.addEventListener("DOMContentLoaded", function () {
	const categoryList = document.querySelector("ul.wp-block-categories-list");

	if (!categoryList) {
		return;
	}

	const allPostsLi = document.createElement("li");
	const allPostsLink = document.createElement("a");
	allPostsLink.href = "/";
	allPostsLink.textContent = "All Posts";

	if (window.location.pathname === "/") {
		allPostsLi.classList.add("current-cat");
	}

	allPostsLi.appendChild(allPostsLink);
	categoryList.insertBefore(allPostsLi, categoryList.firstChild);
});
