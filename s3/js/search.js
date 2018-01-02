'use strict';

var API_ENDPOINT = 'https://api.relliker.com/v1';

var AD_SOURCE = '<ins class="adsbygoogle" ' +
  'style="display:block" ' +
  'data-ad-client="ca-pub-8861318913253064" ' +
  'data-ad-slot="4955514410" ' +
  'data-ad-format="link"></ins>';

var TEXT_ADS = 0;

function addResult(groupResults, result, searchTerm) {
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
  let groupHeading = $('<h4/>', { 'class': 'list-group-item-heading search-heading' });
  groupHeading.text(resultsSource.name);
  bolden(groupHeading, searchTerm);
  let groupDescription = $('<p/>', { 'class': 'list-group-item-text search-description' });
  groupDescription.text(resultsSource.description);
  bolden(groupDescription, searchTerm);
  groupMain.append(groupMembers);
  groupMain.append(groupHeading);
  groupMain.append(groupDescription);

  groupResults.append(groupMain);
}

function addAd(groupResults) {
  let adWrapper = $('<div/>', {
    'class': 'list-group-item'
  });
  let ad = $(AD_SOURCE);
  adWrapper.append(ad);
  groupResults.append(adWrapper);
  // Post a task to fill in the ad space since Adsense requires
  // the DOM element to be visible to figure out what type of responsive ad
  // to show
  setTimeout(function(){
    try {
      (adsbygoogle = window.adsbygoogle || []).push({});
    } catch (e) {
      console.log(e);
    }
  }, 1000);
}

function populateResults(searchTerm, results, empty) {
  let groupResults = $('#group-results');
  let searchResults = results.result.hits.hits;
  let resultsLength = results.result.hits.total;
  let timeMillis = results.result.took;
  $('#search-terms').text(searchTerm);
  $('#search-results-count').text(resultsLength + ' result' + (resultsLength != 1 ? 's' : ''));
  $('#search-results-time').text(timeMillis + ' millisecond' + (timeMillis != 1 ? 's' : ''));
  if (empty) {
    groupResults.empty();
    TEXT_ADS = 0;
  }
  for (let i = 0; i < searchResults.length; i++) {
    let result = searchResults[i];
    addResult(groupResults, result, searchTerm);
    if (i % 10 == 9 && TEXT_ADS < 3) {
      addAd(groupResults);
      TEXT_ADS++;
    }
  }

  $('#row-search-loading').hide();
  if (searchResults.length === 0) {
    $('#row-search-no-results').show();
  } else {
    $('#row-search-results').show();
  }

  if (resultsLength > results.next_offset) {
    $('#row-search-load-more').show();
    $('#row-search-load-more').data('offset', results.next_offset);
    $('#row-search-load-more').data('term', searchTerm);
  } else {
    $('#row-search-load-more').hide();
    $('#row-search-load-more').data('offset', -1);
    $('#row-search-load-more').data('term', '');
    $('#row-search-no-more').show();
  }
}

function loadMoreGroups() {
  $('#row-search-load-more').hide();
  getSearchResults(
    $('#row-search-load-more').data('term'),
    $('#row-search-load-more').data('offset'));
}

// http://stackoverflow.com/a/6969486
function escapeRegExp(str) {
  return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}

function bolden(element, term) {
  var escapedTerms = [];
  term.split(' ').forEach(function(term) {
    escapedTerms.push(escapeRegExp(term));
  });
  var html = element.html();
  var regex = '(' + escapedTerms.join('|') + ')';
  element.html(html.replace(
      new RegExp(regex, 'gi'), '<strong>$&</strong>'));
}

function getSearchResults(searchTerm, offset) {
  $.ajax({
    url: API_ENDPOINT + '/search',
    data: {
      s: searchTerm,
      o: offset
    },
    success: function (result) {
      populateResults(searchTerm, result, offset === 0);
    },
    error: function () {
      $('#row-search-loading').hide();
      $('#row-search-trottled').show();
    },
    datatype: 'json'
  });
}

function searchGroups(searchTerm) {
  let id = $(this).attr('id');
  if (id === 'form-search-topbar-button') {
    searchTerm = $('#form-search-topbar-text').val();
    $('#form-search-jumbotron-text').val(searchTerm);
  } else if (id === 'form-search-jumbotron-button') {
    searchTerm = $('#form-search-jumbotron-text').val();
    $('#form-search-topbar-text').val(searchTerm);
  } else {
    // Manually called function
    $('#form-search-jumbotron-text').val(searchTerm);
    $('#form-search-topbar-text').val(searchTerm);
  }

  // Initiate call to API
  $('#row-search-start').hide();
  $('#row-search-no-results').hide();
  $('#row-search-results').hide();
  $('#row-search-load-more').hide();
  $('#row-search-trottled').hide();
  $('#row-search-loading').show();
  getSearchResults(searchTerm, 0);
}

// $('#form-search-topbar-button').click(searchGroups);
// $('#form-search-jumbotron-button').click(searchGroups);
$('#row-search-load-more').click(loadMoreGroups);

// http://stackoverflow.com/a/901144
function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function maybePerformSearch() {
  var searchTerm = getParameterByName('s');
  if (searchTerm) {
    searchGroups(searchTerm);
  }
}

function searchGroupsForm(form) {
  let searchTerm = $(form).find('input.search-box').val();
  let url = '/?s='+encodeURIComponent(searchTerm);
  window.history.pushState('', '', url);
  ga('set', 'page', url);
  ga('send', 'pageview');
  searchGroups(searchTerm);
  return false;
}

window.onpopstate = maybePerformSearch;
maybePerformSearch();
