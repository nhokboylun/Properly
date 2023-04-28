$(document).ready(function () {
	$('#submit').click(function () {
		var searchQuery = $('#search').val();
		$.ajax({
			url: 'search.php',
			type: 'POST',
			data: { query: searchQuery },
			success: function (response) {
				$('#result').html(response);
			},
			error: function () {
				alert('Error');
			}
		});
	});
});