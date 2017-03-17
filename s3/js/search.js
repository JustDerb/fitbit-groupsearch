'use strict';

$(function() {

var API_ENDPOINT = 'https://bn5co2yv54.execute-api.us-west-2.amazonaws.com/Prod';

function populateResults(searchTerm, results) {
  console.log(results);
  let groupResults = $('#group-results');
  let searchResults = results.result.hits.hits;
  let resultsLength = results.result.hits.total;
  let timeMillis = results.result.took;
  $('#search-terms').text(searchTerm);
  $('#search-results-count').text(resultsLength + ' result' + (resultsLength != 1 ? 's' : ''));
  $('#search-results-time').text(timeMillis + ' millisecond' + (timeMillis != 1 ? 's' : ''));
  groupResults.empty();
  for (let i = 0; i < searchResults.length; i++) {
    let result = searchResults[i];
    let groupMain = $('<a/>', {
      'class': 'list-group-item',
      'href': 'https://fitbit.com/group/' + result._id + '?utm_source=relliker&utm_medium=referral&utm_term=' + encodeURIComponent(searchTerm),
      'target': '_blank'
    });
    let resultsSource = result._source;
    let groupMembers = $('<span/>', { 'class': 'badge' });
    if (resultsSource.members !== undefined) {
      groupMembers.text(resultsSource.members + ' member' + (resultsSource.members != 1 ? 's' : ''));
    }
    let groupHeading = $('<h4/>', { 'class': 'list-group-item-heading' });
    groupHeading.text(resultsSource.name);
    let groupDescription = $('<p/>', { 'class': 'list-group-item-text' });
    groupDescription.text(resultsSource.description);
    groupMain.append(groupMembers);
    groupMain.append(groupHeading);
    groupMain.append(groupDescription);
    
    groupResults.append(groupMain);
  }
  
  $('#row-search-results').show();
}

function getSearchResults(searchTerm) {
  $.get(API_ENDPOINT + '/search', {
    s: searchTerm,
    o: 0
  }, function (result) {
    populateResults(searchTerm, result);
  }, 'json');
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
