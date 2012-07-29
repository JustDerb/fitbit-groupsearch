<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>fitbit - Group Search</title>
<link rel="stylesheet" type="text/css" href="css/css1.css" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/css2.css" charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript"  src="js/setTextLimits.js"  charset="utf-8" ></script>
<script type="text/javascript" charset="utf-8">
	function addTerm(term) {
		$('#searchTerms').val($('#searchTerms').val()+' '+term);
	}
	function addHelpTerm(term) {
		$('<a href="#"></a>').text(term).click(function() {
	          addTerm(term);
	    }).appendTo('#locationSearch');
	    $('#locationSearch').append(' ');
	}
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26103835-1']);
  _gaq.push(['_setDomainName', 'relliker.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body class="fb-body droid">
	<a href="https://github.com/JustDerb/fitbit-groupsearch" target="_blank">
		<img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png" alt="Fork me on GitHub">
	</a>
	<div id="container" class="narrowcontainer">
		<?php 
			$headerPage = "filter";
			include 'includes/header.php' 
		?>
		<div id="content" class="allgroups">
			<div id="contenthead">
				<div class="groupnav">
					<a href="index.php">Filter</a><span id="type"></span>
				</div>
			</div>
			<div id="contentbody" class="primary">
				<div class="innerpading">
					<div class"alreadyMember panelSwitch panelSwitchConnected  left">
						<div class="column column1 form clearfix curvyIgnore">
							<h2 style="padding:0 0 20px 0">Filter Groups</h2>
							<p>Choose an option below to filter <strong>ALL</strong> groups.  There is no paging of data, so it might take awhile to load results.</p>
							<div class="tInput">
								<input placeholder="Top Members" name="type" value="f0" type="radio" id="fmembers" style="margin:10px 0 0 10px"/>
								<label for="fmembers" style="font-size:1.5em">Top Members</label>
							</div>							
							<div id="filterResults" style="min-height:400px"></div>
							<div style="clear:both;height:100px;"></div>
							<script type="text/javascript"  src="js/filter.js"  charset="utf-8" ></script>
							<script type="text/javascript" charset="utf-8" >$('#fmembers').click();</script>
						</div>
					</div>
				</div>
			</div>
			<div id="contentfooter">
				
			</div>
		</div>
	</div>
	<div id="siteinfowrapper">
		<footer id="siteinfo">
			
		</footer>
	</div>
</body>
</html>