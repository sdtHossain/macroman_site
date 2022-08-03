$(function () {

	$("#nav-tabContent button").on("click", function (e) {
		$("#nav-tabContent button").not(this).attr("aria-expanded", "false").next().removeClass("show");
	})


	// var $myGroup = $('#nav-tabContent');
	// $myGroup.on('show.bs.collapse', '.collapse', function () {
	// 	$myGroup.find('.collapse.in').collapse('hide');
	// });

});