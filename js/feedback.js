$(function() {
	function submitVote(id, up, obj) {
		$.ajax({
			success : function(data, textStatus, jqXHR) {
				/*
				$(obj).parent().children('a span').removeClass('label-info label-important label-success');
				if (up) {
					$(obj).children('span').addClass('label-success');
				}
				else {
					$(obj).children('span').addClass('label-important');
				}
				*/
				alert('Thanks for the feedback!');
			},
			error : function(jqXHR, textStatus, errorThrown) {
				alert('Could not submit vote at this time.  Try again later.');
			},
			url : 'api/feedback.php',
			data : {
				'id' : id,
				'v' : (up ? 'u' : 'd')
			}
		});
	};

	$('a[id=voteUp]').each(function() {
		$(this).click(function(obj) {
			submitVote($(this).attr('fbid'), true, $(this));
		});
	});
	
	$('a[id=voteDown]').each(function() {
		$(this).click(function(obj) {
			submitVote($(this).attr('fbid'), false, $(this));
		});
	});

});