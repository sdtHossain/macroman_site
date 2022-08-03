$(function () {

	$("#nav-tabContent button").on("click", function (e) {
		$("#nav-tabContent button").not(this).attr("aria-expanded", "false").next().removeClass("show");
	})

});