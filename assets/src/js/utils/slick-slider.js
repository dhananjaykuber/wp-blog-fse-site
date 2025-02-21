jQuery(document).ready(function ($) {
	$(".is-style-slider-query-loop ul").slick({
		slidesToShow: 1.5,
		slidesToScroll: 1,
		infinite: false,
		speed: 500,
		cssEase: "ease",
		initialSlide: 0,
		responsive: [
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1,
				},
			},
		],
	});

	$(".is-style-vertical-post-query-loop ul").slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		arrows: true,
		dots: false,
		infinite: false,
		speed: 500,
		cssEase: "ease",
		initialSlide: 0,
		responsive: [
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1.7,
				},
			},
		],
	});
});
