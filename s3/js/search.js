'use strict';

$(function() {

function populateResults(searchTerm, results) {
  console.log(results);
  let groupResults = $('#group-results');
  let resultsLength = results.length;
  $('#search-terms').text(searchTerm);
  $('#search-results-count').text(resultsLength + ' result' + (resultsLength != 1 ? 's' : ''));
  groupResults.empty();
  for (let i = 0; i < resultsLength; i++) {
    let result = results[i];
    let groupMain = $('<a/>', {
      'class': 'list-group-item',
      'href': 'https://fitbit.com/group/' + result.id + '?utm_source=relliker&utm_medium=referral&utm_term=' + encodeURIComponent(searchTerm),
      'target': '_blank'
    });
    let groupMembers = $('<span/>', { 'class': 'badge' });
    groupMembers.text(result.members + ' member' + (result.members != 1 ? 's' : ''));
    let groupHeading = $('<h4/>', { 'class': 'list-group-item-heading' });
    groupHeading.text(result.name);
    let groupDescription = $('<p/>', { 'class': 'list-group-item-text' });
    groupDescription.text(result.description);
    groupMain.append(groupMembers);
    groupMain.append(groupHeading);
    groupMain.append(groupDescription);
    
    groupResults.append(groupMain);
  }
  
  $('#row-search-results').show();
}

function getSearchResults(searchTerm) {
  populateResults(searchTerm, [{
    name: 'Test name',
    description: 'Test description',
    members: 3,
    id: 'IDGAF'
  }]);
}

function searchGroups() {
  let id = $(this).attr('id');
  let searchTerm = '';
  if (id === 'form-search-topbar-button') {
    searchTerm = $('#form-search-topbar-text').val();
    $('#form-search-jumbotron-text').val(searchTerm);
  } else if (id === 'form-search-jumbotron-button') {
    searchTerm = $('#form-search-jumbotron-text').val();
    $('#form-search-topbar-text').val(searchTerm);
  }
  
  // Initiate call to API
  $('#row-search-no-results').hide(); 
  $('#row-search-results').hide();
  getSearchResults(searchTerm);
}

$('#form-search-topbar-button').click(searchGroups);
$('#form-search-jumbotron-button').click(searchGroups);

});
