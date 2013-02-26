function clearResults() {
	$('#filterResults').empty();
}

function getGroupItem(data) {
	var mainElement = $('<tr></tr>');
	
	//Tack on memebers
	$('<td></td>').html('<span class="label label-info">'+data.members+'</span>').appendTo(mainElement);
	
	// Tack on main info
	//var title = $('').attr("href",  + ).html();
	//var description = $('<p></p>').attr("title", data.description).html(data.description);
	$('<td></td>').html('<a target="_blank" href="http://www.fitbit.com'+data.url+'">'+data.name+'</a><br/>'+data.description).appendTo(mainElement);
	
	// Add extras (Change colors later)
	$('<td></td>').html('<span class="label">'+data.esteps+'</span>').appendTo(mainElement);
	$('<td></td>').html('<span class="label label-success">'+data.eactivepoints+'</span>').appendTo(mainElement);
	$('<td></td>').html('<span class="label label-warning">'+data.edistance+'</span>').appendTo(mainElement);
	$('<td></td>').html('<span class="label label-important">'+data.everyactive+'</span>').appendTo(mainElement);
	
	return mainElement;
}

function getResults(data) {
    var filterType = $('input[name=type]:checked', '.tInput').attr('placeholder');
    if (filterType) {
    	$.getJSON('api/filter.php', data, function(data) {
    		clearResults();
    		var items = [];
    		
    		$.each(data, function(key, val) {
    			items.push(getGroupItem(val));
    		});
    		    		
    		$.each(items, function(index, item) {
    		//console.log(item);
    			$(item).appendTo('#filterResults');
    		});
    	});
	}
}

function updateData() {
	var selected = $('input[name=type]:checked', '.tInput').val();
	if (selected) {
    	clearResults();
    	$('#filterResults').append($('<tr><td colspan="6">Please wait...</td></tr>'));
    	$('#filterResults').append($('<tr><td colspan="6"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></td></tr>'));
    	getResults({
    		f: selected,
    		limit: $('#shownumber').val()
    	});
	}
}

$('input[name=type]', '.tInput').change(function(event) {
	updateData();
});
$('#shownumber').change(function(event) {
	updateData();
});

