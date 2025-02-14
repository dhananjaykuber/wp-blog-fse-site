import "./view.scss";

document.addEventListener("DOMContentLoaded", function () {
	const tocLinks = document.querySelectorAll(
		".wp-block-blog-theme-25-table-of-content a",
	);
	const headings = document.querySelectorAll("h2[id]");

	tocLinks.forEach((link) => {
		link.addEventListener("click", function (e) {
			e.preventDefault();

			// Remove active class from all links
			tocLinks.forEach((l) => l.parentElement.classList.remove("active"));

			// Add active class to clicked link
			this.parentElement.classList.add("active");

			// Get the target heading
			const targetId = this.getAttribute("href");
			const targetHeading = document.querySelector(targetId);

			if (targetHeading) {
				// Get heading position
				const headingRect = targetHeading.getBoundingClientRect();
				const offsetPosition = window.pageYOffset + headingRect.top - 100;

				// Smooth scroll
				window.scrollTo({
					top: offsetPosition,
					behavior: "smooth",
				});

				// Update URL
				history.pushState(null, null, targetId);
			}
		});
	});

	// Scroll handling with Intersection Observer
	const observerOptions = {
		root: null,
		rootMargin: "-100px 0px -50% 0px",
		threshold: 0,
	};

	const observerCallback = (entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				const currentId = entry.target.getAttribute("id");

				// Update TOC active state
				tocLinks.forEach((link) => {
					const href = link.getAttribute("href").substring(1);
					if (href === currentId) {
						// Remove active class from all links
						tocLinks.forEach((l) => l.parentElement.classList.remove("active"));
						// Add active to current link
						link.parentElement.classList.add("active");
					}
				});
			}
		});
	};

	const observer = new IntersectionObserver(observerCallback, observerOptions);

	// Observe all headings
	headings.forEach((heading) => observer.observe(heading));
});
