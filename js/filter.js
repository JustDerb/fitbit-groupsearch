function clearResults() {
	$('#filterResults').empty();
}

function getGroupItem(data) {
	var mainElement = $('<li class="groupitem"></li>');
	
	$('<a class="groupname textlimit" target="_blank"></a>').attr("href", "http://www.fitbit.com" + data.url).html(data.name).appendTo(mainElement);
	$('<span class="totalmembers"></span>').html(data.members + " Members").appendTo(mainElement); //
	$('<p class="description textlimit4"></p>').attr("title", data.description).html(data.description).appendTo(mainElement); //
	
	// Add extras div
	var extras = $('<div class="popover"></div>').appendTo(mainElement);
	$('<p></p>').html("<strong>Steps:</strong> "+data.esteps+" steps").appendTo(extras);
	$('<p></p>').html("<strong>Active Points:</strong> "+data.eactivepoints+" pts").appendTo(extras);
	$('<p></p>').html("<strong>Distance:</strong> "+data.edistance+" miles").appendTo(extras);
	$('<p></p>').html("<strong>Very Active:</strong> "+data.everyactive+" minutes").appendTo(extras);
	
	$(mainElement).mouseenter(function() {
		$(mainElement).popover({
			trigger: 'hover',
			position: "top",
			verticalOffset: -15,
			title: false,
			content: extras//,
			//classes: "wider"
		});
	});

	return mainElement;
}

function getResults(data) {
	$('#typeOut').html(' / '+$('input[name=type]:checked', '.tInput').attr('placeholder'));
	$.getJSON('api/filter.php', data, function(data) {
		clearResults();
		var items = [];
		
		$.each(data, function(key, val) {
			items.push(getGroupItem(val));
		});
		
		
		var mainResults = $('<div id="publicgroups"></div>');
		var sectionGroups = $('<div class="section groups"></div>').appendTo(mainResults);
		var results = $('<ul class="grouplist"></ul>').appendTo(sectionGroups);
		
		$.each(items, function(index, item) {
		//console.log(item);
			$(item).appendTo(results);
		});
		
		$(mainResults).appendTo('#filterResults');
	});
	$.setTextLimits();
}

function updateData() {
	var selected = $('input[name=type]:checked', '.tInput').val();
	clearResults();
	$('#filterResults').append($('<p class="innerpading"><br/>Please wait...</p>'));
	getResults({
		f: selected,
		limit: $('#shownumber').val()
	});
}

$('input[name=type]', '.tInput').change(function(event) {
	updateData();
});
$('#shownumber').change(function(event) {
	updateData();
});

