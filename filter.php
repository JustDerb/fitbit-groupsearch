<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>fitbit - Group Search</title>
<link rel="stylesheet" type="text/css" href="css/css1.css" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/css2.css" charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript"  src="js/setTextLimits.js"  charset="utf-8" ></script>
<link rel="stylesheet" href="js/popover/popover.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/popover/jquery.popover-1.1.0.js"></script>
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
	<div id="container" class="narrowcontainer">
		<?php 
			$headerPage = "filter";
			include 'includes/header.php' 
		?>
		<div id="content" class="allgroups">
			<div id="contenthead">
				<div class="groupnav">
					<a href="filter.php">Filter</a><span id="typeOut"></span>
				</div>
			</div>
			<div id="contentbody" class="primary">
				<div class="innerpading">
					<div class="alreadyMember panelSwitch panelSwitchConnected">
						<div class="column column1 form clearfix curvyIgnore">
							<h2 style="padding:0 0 20px 0">Filter Groups</h2>
							<p>Choose an option below to filter <strong>ALL</strong> groups.  There is no paging of data, so it might take awhile to load results.</p>
							<div style="float:right">
								Number of results to show:
								<select id="shownumber">
									<option value="30">First 30</option>
									<option value="60">First 60</option>
									<option value="90">First 90</option>
									<option value="-1">Show All</option>
								</select>
							</div>
							<div class="tInput">
								<input placeholder="Top Members" name="type" value="members" type="radio" id="fmembers" style="margin:10px 0 0 10px"/>
								<label for="fmembers" style="font-size:1.5em">Top Members</label>
								<input placeholder="Most Steps (30 Days)" name="type" value="steps" type="radio" id="fsteps" style="margin:10px 0 0 10px"/>
								<label for="fsteps" style="font-size:1.5em">Most Steps (30 Days)</label>
								<br/>
								<input placeholder="Most Active Points (30 Days)" name="type" value="activepoints" type="radio" id="factivepoints" style="margin:10px 0 0 10px"/>
								<label for="factivepoints" style="font-size:1.5em">Most Active Points (30 Days)</label>
								<input placeholder="Top Distance (30 Days)" name="type" value="distance" type="radio" id="fdistance" style="margin:10px 0 0 10px"/>
								<label for="fdistance" style="font-size:1.5em">Top Distance (30 Days)</label>
								<br/>
								<input placeholder="Most Active (30 Days)" name="type" value="veryactive" type="radio" id="fveryactive" style="margin:10px 0 0 10px"/>
								<label for="fveryactive" style="font-size:1.5em">Most Active (30 Days)</label>
							</div>						
						</div>
					</div>
				</div>
				<div style="clear:both"></div>
				<div id="filterResults" style="min-height:400px"></div>
				<div style="clear:both;height:100px;"></div>
				<script type="text/javascript"  src="js/filter.js"  charset="utf-8" ></script>
				<script type="text/javascript" charset="utf-8" >updateData();</script>
			</div>
			<div id="contentfooter">
				<p>Join the <a href="http://www.fitbit.com/group/229YCB" target="_blank">FitBit Group Search</a>!</p>
			</div>
		</div>
	</div>
	<div id="siteinfowrapper">
		<footer id="siteinfo">
			
		</footer>
	</div>
	<a href="https://github.com/JustDerb/fitbit-groupsearch" target="_blank">
		<img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png" alt="Fork me on GitHub">
	</a>
</body>
</html>