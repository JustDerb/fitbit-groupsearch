<?php
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>fitbit - Group Search</title>
<link rel="stylesheet" type="text/css" href="css/css1.css" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/css2.css" charset="utf-8">
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
			$headerPage = "about";
			include 'includes/header.php' 
		?>
		<div id="content" class="allgroups">
			<div id="contenthead">
				<div class="groupnav">
					<a href="about.php">About</a>
				</div>
			</div>
			<div id="contentbody">
				<div class="innerpading">
					<h2>What is this site?</h2>
					<p>This is a website to help fitbit users easily search the fitbit cummunity 
					groups so that they don't have to wade through pages and pages of groups only 
					to get nowhere!</p>
					<br />
					<h2>This site looks a lot like FitBit's website.  What gives?</h2>
					<p>The reason why this site looks so alike is because it is! I have taken and 
					reverse-engineered their website to create a look-alike website so that any FitBit 
					user know immediatly how to the site.  <b>This, in no way, is a phishing website.</b>
					We don't even ask you to log in anywhere!</p>
					<br />
					<h2>Why doesn't fitbit have a search feature?</h2>
					<p>I don't know!  You should ask them!</p>
					<br />
					<h2>I just made a group and I can't find it on here!</h2>
					<p>I scrape fitbit's server about everyday so please wait at least 24 hours for 
					your group will show up.</p>
					<br />
					<h2>I got an issue or suggestion</h2>
					<p>Great! Head on over to <a href="http://www.fitbit.com/group/229YCB">FitBit Group 
					Search</a> and create a topic discussion.</p>
				</div>
				<div style="height:300px"></div>
			</div>
		</div>
	</div>
	<div id="siteinfowrapper">
		<footer id="siteinfo">
			
		</footer>
	</div>
</body>
</html>