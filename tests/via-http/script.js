/**
 * This js file required jQuery.
 */


function jsMethodPostPagination() {
	if (typeof(methodpost_form_button_clicked) === 'undefined') {
		console.log('No pagination button click.');
		return false;
	} else {
		console.log(methodpost_form_button_clicked);
	}

	$('#method-post-start-value').val(methodpost_form_button_clicked);
	$('#pagination-method-post-form').submit();
}// jsMethodPostPagination


var methodpost_form_button_clicked;


$(function() {
	$('#pagination-method-post-form').on('click', 'button', function(e) {
		e.preventDefault();
		methodpost_form_button_clicked = $(this).val();
		jsMethodPostPagination();
	});
});