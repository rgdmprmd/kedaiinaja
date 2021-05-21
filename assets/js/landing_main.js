$(document).ready(function () {
	let menuSlider = $("#lightSlider");
	menuSlider.owlCarousel({
		margin: 30,
		responsive: {
			0: {
				items: 2,
				margin: 10,
			},
			576: {
				items: 3,
			},
			768: {
				items: 4,
			},
			1210: {
				items: 6,
			},
		},
	});

	$("#vertSlider").owlCarousel({
		items: 1,
		autoplay: true,
		loop: true,
		autoplayTimeout: 2000,
		mergeFit: true,
	});

	$(".fa-arrow-left").click(function () {
		menuSlider.trigger("prev.owl.carousel");
	});

	$(".fa-arrow-right").click(function () {
		menuSlider.trigger("next.owl.carousel", [300]);
	});

	$(".menu-toggle input").click(function () {
		if ($(this).prop("checked") === true) {
			$("nav .container ul").toggleClass("slide", true);
		} else {
			$("nav .container ul").toggleClass("slide", false);
		}
	});
});
