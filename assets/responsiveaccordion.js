// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

$(function () {

	$("#nav-tabContent button").on("click", function (e) {
		$("#nav-tabContent button").not(this).attr("aria-expanded", "false").next().removeClass("show");
	})

});