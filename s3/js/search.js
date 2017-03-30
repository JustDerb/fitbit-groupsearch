'use strict';

var API_ENDPOINT = 'https://api.relliker.com/v1';

var AD_SOURCE = '<ins class="adsbygoogle" ' +
  'style="display:block" ' +
  'data-ad-client="ca-pub-8861318913253064" ' +
  'data-ad-slot="4955514410" ' +
  'data-ad-format="link" ' +
  'data-adtest="on"></ins>';
  
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
  (adsbygoogle = window.adsbygoogle || []).push({});
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
  populateResults(searchTerm,
    {
  "errorMessage":null,
  "next_offset":51,
  "result":{
    "hits":{
      "hits":[
        {
          "_score":2.4921715,
          "_type":"group_info",
          "_id":"22MQ53",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.926152",
            "name":"Washington State FitBiters",
            "description":"People in Washington getting fit",
            "members":"47",
            "id":"22MQ53"
          },
          "_index":"group-index"
        },
        {
          "_score":2.4921715,
          "_type":"group_info",
          "_id":"22G6Z4",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.968370",
            "name":"Washington Stepping Crew",
            "description":"Washington Walkaholics",
            "members":"5",
            "id":"22G6Z4"
          },
          "_index":"group-index"
        },
        {
          "_score":2.4921715,
          "_type":"group_info",
          "_id":"223PSY",
          "_source":{
            "timestamp":"2017-03-20T02:59:41.459387",
            "name":"Puyallup Washington Fitbit Users",
            "description":"Users from Puyallup, Washington.",
            "members":"71",
            "id":"223PSY"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"22C43F",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.609129",
            "name":"Washington Bandits",
            "description":"",
            "members":"1",
            "id":"22C43F"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"22CJ7N",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.692118",
            "name":"Washington D.C Fitbit",
            "description":"",
            "members":"2",
            "id":"22CJ7N"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"22DBM5",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.739650",
            "name":"washington DC",
            "description":"",
            "members":"2",
            "id":"22DBM5"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"223J9G",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.779497",
            "name":"washington hammer",
            "description":"",
            "members":"4",
            "id":"223J9G"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"22GLJL",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.849592",
            "name":"Washington Restaurant Association",
            "description":"",
            "members":"1",
            "id":"22GLJL"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"22DHDN",
          "_source":{
            "timestamp":"2017-03-20T02:41:47.050121",
            "name":"Washington, DC - FitBiters",
            "description":"",
            "members":"2",
            "id":"22DHDN"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"222DVL",
          "_source":{
            "timestamp":"2017-03-20T02:59:59.789861",
            "name":"Redmond, Washington",
            "description":"",
            "members":"4",
            "id":"222DVL"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"2335KQ",
          "_source":{
            "timestamp":"2017-03-23T03:37:51.277188",
            "name":"Nuvasive Oregon/Washington",
            "description":"",
            "members":"4",
            "id":"2335KQ"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"2244V8",
          "_source":{
            "timestamp":"2017-03-23T03:43:07.444346",
            "name":"Dusty, Washington Fitbits?",
            "description":"",
            "members":"1",
            "id":"2244V8"
          },
          "_index":"group-index"
        },
        {
          "_score":2.2027893,
          "_type":"group_info",
          "_id":"22FR8H",
          "_source":{
            "timestamp":"2017-03-23T03:45:31.609755",
            "name":"GPC Washington",
            "description":"",
            "members":"28",
            "id":"22FR8H"
          },
          "_index":"group-index"
        },
        {
          "_score":2.18065,
          "_type":"group_info",
          "_id":"22MV87",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.659944",
            "name":"Washington County ,Oregon",
            "description":"Anyone who lives in Washington County is invited",
            "members":"1",
            "id":"22MV87"
          },
          "_index":"group-index"
        },
        {
          "_score":2.18065,
          "_type":"group_info",
          "_id":"22GJH7",
          "_source":{
            "timestamp":"2017-03-20T02:59:13.922827",
            "name":"Pierce County Washington",
            "description":"Anyone in the Pierce County area of Washington State!",
            "members":"5",
            "id":"22GJH7"
          },
          "_index":"group-index"
        },
        {
          "_score":2.18065,
          "_type":"group_info",
          "_id":"22267C",
          "_source":{
            "timestamp":"2017-03-23T03:36:02.403591",
            "name":"LaVida of Washington",
            "description":"Fitbit gals of LaVida Massage in Washington twp. MI",
            "members":"1",
            "id":"22267C"
          },
          "_index":"group-index"
        },
        {
          "_score":2.18065,
          "_type":"group_info",
          "_id":"22RCWT",
          "_source":{
            "timestamp":"2017-03-23T03:41:02.742903",
            "name":"Bonney Lake Washington",
            "description":"For those living in the Bonney Lake, Washington area.",
            "members":"3",
            "id":"22RCWT"
          },
          "_index":"group-index"
        },
        {
          "_score":1.9076715,
          "_type":"group_info",
          "_id":"22K2JZ",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.939570",
            "name":"WASHINGTON STATE LEADERBOARD RUN-UPS",
            "description":"If you live in Washington state and want to challenge other Washington state Fitbit users on a leaderboard, join this group.",
            "members":"1",
            "id":"22K2JZ"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22MX96",
          "_source":{
            "timestamp":"2017-03-20T02:37:36.197986",
            "name":"Sioux Falls Washington High School Class of '88",
            "description":"Sioux Falls, South Dakota\n\nWashington High School\n\nClass of 1988",
            "members":"1",
            "id":"22MX96"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22KS9J",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.836554",
            "name":"Washington Peoples",
            "description":"This group is for people who live in the state of Washington! No one else:(",
            "members":"1",
            "id":"22KS9J"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"229L9C",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.908708",
            "name":"Washington State Fitbit Users",
            "description":"Any fitbiters in Washington state? Let's show some support and motivate each other!",
            "members":"1120",
            "id":"229L9C"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22NVY6",
          "_source":{
            "timestamp":"2017-03-20T02:42:21.855998",
            "name":"Wisconsin: Ozaukee, Sheboygan, Washington Counties",
            "description":"For all Wisconsinites that call Ozaukee, Sheboygan or Washington county their home.",
            "members":"39",
            "id":"22NVY6"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"2242GH",
          "_source":{
            "timestamp":"2017-03-20T02:59:41.432384",
            "name":"Puyallup & Tacoma Washington",
            "description":"Do you live in Puyallup or Tacoma Washington? If so let's do this together!",
            "members":"272",
            "id":"2242GH"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22N6K9",
          "_source":{
            "timestamp":"2017-03-20T02:59:59.320520",
            "name":"redbox's Western Washington Walkers",
            "description":"For people who wark at redbox and live  in Western Washington.",
            "members":"10",
            "id":"22N6K9"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22HQ5L",
          "_source":{
            "timestamp":"2017-03-23T03:35:13.452158",
            "name":"Iowa Washington County",
            "description":"Anyone in Washington County looking for a walking partner here you go. #GetFit",
            "members":"1",
            "id":"22HQ5L"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"233GHK",
          "_source":{
            "timestamp":"2017-03-23T03:35:42.810898",
            "name":"Kent Washington Fitbit Group",
            "description":"For anyone is Kent Washington looking to either meetup or just track your progress with us!",
            "members":"1",
            "id":"233GHK"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22PJ6C",
          "_source":{
            "timestamp":"2017-03-23T03:41:41.587072",
            "name":"Central Washington Fitbit",
            "description":"This is a group for all fitbit addicts in Central Washington State.",
            "members":"3",
            "id":"22PJ6C"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22ZJJH",
          "_source":{
            "timestamp":"2017-03-23T03:42:12.402517",
            "name":"Compass Health Washington",
            "description":"This group is for employees of Compass Health located in Washington state.",
            "members":"1",
            "id":"22ZJJH"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"223S7W",
          "_source":{
            "timestamp":"2017-03-23T03:44:34.773117",
            "name":"Fitbit Washington",
            "description":"A Fitbit Group for Those who Live in the State of Washington.",
            "members":"184",
            "id":"223S7W"
          },
          "_index":"group-index"
        },
        {
          "_score":1.8691287,
          "_type":"group_info",
          "_id":"22GDDJ",
          "_source":{
            "timestamp":"2017-03-23T03:45:50.802008",
            "name":"Hay Group Washington DC Metro",
            "description":"For all Hay Group employees working in the Washington DC Metro office.",
            "members":"13",
            "id":"22GDDJ"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22MTS9",
          "_source":{
            "timestamp":"2017-03-20T02:38:11.384374",
            "name":"St. Elizabeth Hospital Enumclaw Washington",
            "description":"",
            "members":"3",
            "id":"22MTS9"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"2224FT",
          "_source":{
            "timestamp":"2017-03-20T02:41:00.447584",
            "name":"University of Washington Radiology",
            "description":"",
            "members":"3",
            "id":"2224FT"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"224CQ8",
          "_source":{
            "timestamp":"2017-03-20T02:41:31.746699",
            "name":"WA Fitbit",
            "description":"Washington people with fitbit",
            "members":"89",
            "id":"224CQ8"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22D345",
          "_source":{
            "timestamp":"2017-03-20T02:41:44.884443",
            "name":"wapo",
            "description":"The Washington Post",
            "members":"1",
            "id":"22D345"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"222396",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.550723",
            "name":"Washco-Md.net",
            "description":"Residents & those Employed in Washington County Maryland",
            "members":"7",
            "id":"222396"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22NTD2",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.592505",
            "name":"Washington (Plant 2): Shoelace Express",
            "description":"",
            "members":"3",
            "id":"22NTD2"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"228TNX",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.712716",
            "name":"Washington D.C.",
            "description":"For Fitbit users in our Nation's Capitol",
            "members":"644",
            "id":"228TNX"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22BFQC",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.726339",
            "name":"Washington D.C. metropolitan area",
            "description":"",
            "members":"2",
            "id":"22BFQC"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22QVQJ",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.862519",
            "name":"Washington School Dixon, IL",
            "description":"",
            "members":"1",
            "id":"22QVQJ"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22L84C",
          "_source":{
            "timestamp":"2017-03-20T02:41:46.875309",
            "name":"washington Sporlan named Tim",
            "description":"",
            "members":"2",
            "id":"22L84C"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22D34C",
          "_source":{
            "timestamp":"2017-03-20T02:41:47.063592",
            "name":"washingtonpost",
            "description":"The Washington Post",
            "members":"8",
            "id":"22D34C"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22GRJ6",
          "_source":{
            "timestamp":"2017-03-20T02:59:24.435467",
            "name":"Port Orchard",
            "description":"Everyone who lives in Port Orchard Washington!!!",
            "members":"4",
            "id":"22GRJ6"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22N4ZC",
          "_source":{
            "timestamp":"2017-03-20T03:04:44.552432",
            "name":"Sammamish - Washington State",
            "description":"Anyone in the Sammamish area",
            "members":"35",
            "id":"22N4ZC"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22NCXY",
          "_source":{
            "timestamp":"2017-03-23T03:35:05.537390",
            "name":"ILI Washington",
            "description":"Literally working together to achieve healthier lifestyles.",
            "members":"2",
            "id":"22NCXY"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22KLSH",
          "_source":{
            "timestamp":"2017-03-23T03:35:43.728832",
            "name":"Ketel Fun",
            "description":"2016 Washington Nationals FitBit Challenge",
            "members":"3",
            "id":"22KLSH"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22LYRL",
          "_source":{
            "timestamp":"2017-03-23T03:36:13.760352",
            "name":"Limitless",
            "description":"For Washington,MO plant 1 and friends!",
            "members":"1",
            "id":"22LYRL"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22D5G2",
          "_source":{
            "timestamp":"2017-03-23T03:37:46.344315",
            "name":"Northwest Washington Synod ELCA",
            "description":"",
            "members":"1",
            "id":"22D5G2"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"227Q7M",
          "_source":{
            "timestamp":"2017-03-23T03:38:13.686554",
            "name":"Pahlow Groupies",
            "description":"Pahlow Family in Washington",
            "members":"1",
            "id":"227Q7M"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22H7XK",
          "_source":{
            "timestamp":"2017-03-23T03:38:13.186380",
            "name":"Pacific County Washington Fitbiters!",
            "description":"Anyone in  Pacific County!",
            "members":"2",
            "id":"22H7XK"
          },
          "_index":"group-index"
        },
        {
          "_score":1.7622313,
          "_type":"group_info",
          "_id":"22P7M9",
          "_source":{
            "timestamp":"2017-03-23T03:39:37.534849",
            "name":"ACME Crossfit",
            "description":"Enumclaw Washington - ACME Crossfit FitBit Group!!!",
            "members":"1",
            "id":"22P7M9"
          },
          "_index":"group-index"
        }
      ],
      "total":179,
      "max_score":2.4921715
    },
    "_shards":{
      "successful":1,
      "failed":0,
      "total":1
    },
    "took":4,
    "timed_out":false
  }
},
    offset === 0);
  return;
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
  console.log(id);
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
  var url = '/?s='+encodeURIComponent(searchTerm);
  window.history.pushState('', '', url);
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
  searchGroups($(form).find('input.search-box').val());
  return false;
}

window.onpopstate = maybePerformSearch;
maybePerformSearch();

