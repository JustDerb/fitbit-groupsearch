function clearResults() {
	$('#filterResults').empty();
}

function getGroupItem(url, name, members, description) {
	var mainElement = $('<li class="groupitem"></li>');
	
	$('<a class="groupname textlimit" target="_blank"></a>').attr("href", "http://www.fitbit.com" + url).html(name).appendTo(mainElement);
	$('<span class="totalmembers"></span>').html(members + " Members").appendTo(mainElement); //
	$('<p class="description textlimit4"></p>').attr("title", description).html(description).appendTo(mainElement); //

	return mainElement;
}

function getResults(data) {
	$.getJSON('api/filter.php', data, function(data) {
		clearResults();
		var items = [];
		
		$.each(data, function(key, val) {
			items.push(getGroupItem(val.url, val.name, val.members, val.description));
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

$('#fmembers').click(function() {
	clearResults();
	$('#filterResults').append($('<p>Please wait...</p>'));
	getResults({
		f: "members"
	});
});