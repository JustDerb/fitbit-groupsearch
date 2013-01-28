<?php
require_once('includes/localhostStuff.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta property="og:title" content="Relliker - Fitbit Group Search" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://www.relliker.com/about.php" />
<meta property="og:description" content="A group search tool for fitbit." />
<meta property="og:image" content="http://relliker.com/images/bg_branding_a.png" />
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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=350050445089720";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
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
					<ol style="font-size:1.5em;padding:0 0 0 20px">
						<li><a href="#about1">What is this site?</a></li>
						<li><a href="#about2">This site looks a lot like FitBit's website.  What gives?</a></li>
						<li><a href="#about3">Why doesn't FitBit have a search feature?</a></li>
						<li><a href="#about4">I just made a group and I can't find it on here!</a></li>
						<li><a href="#about5">I got an issue or suggestion</a></li>
						<li><a href="#about6">Why is this website called Relliker.com?</a></li>
					</ol>
					<div style="height:20px"></div>
					<h2 id="about1">What is this site?</h2>
					<p>This is a website to help FitBit users easily search the fitbit 
					community 
					groups so that they don't have to wade through pages and pages of groups only 
					to get nowhere!</p>
					<br />
					<h2 id="about2">This site looks a lot like FitBit's website.  What gives?</h2>
					<p>The reason why this site looks so alike is because it is! I have taken and 
					reverse-engineered their website to create a look-alike website so that any FitBit 
					user know immediately how to the site.  <strong>This, in no way, is a phishing website.</strong>
					We don't even ask you to log in anywhere!</p>
					<br />
					<h2 id="about3">Why doesn't FitBit have a search feature?</h2>
					<p>I don't know!  You should ask them!</p>
					<br />
					<h2 id="about4">I just made a group and I can't find it on here!</h2>
					<p>I scrape FitBit's server about everyday so please wait at least 24 hours for 
					your group will show up.</p>
					<br />
					<h2 id="about5">I got an issue or suggestion</h2>
					<p>Great! Head on over to <a href="http://www.fitbit.com/group/229YCB" target="_blank">FitBit Group 
					Search</a> and create a topic discussion or email me at <u>admin [at] relliker.com</u></p>
					<br />
					<h2 id="about6">Why is this website called Relliker.com?</h2>
					<p>I don't know.  It was just a domain name I bought... I'll buy a better one eventually.</p>

				</div>
				<div style="padding:20px">
				    <div class="fb-like" data-href="http://relliker.com" data-send="true" data-width="450" data-show-faces="true" data-action="recommend"></div>
			    </div>
			    <div style="text-align:center">
<?php
	if (!defined("LOCALHOST")) {
		$googleAdsense = <<<ADSENSE
			<script type="text/javascript"><!--
			google_ad_client = "ca-pub-8861318913253064";
			/* Footer Ad (Long) */
			google_ad_slot = "5970958017";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
ADSENSE;
		echo($googleAdsense);
	}
	else
	{
		echo(getAdBlock(728,90));
	}
?>
				</div>
				<div style="height:100px"></div>
    			<div id="contentfooter">
    				<p>Join the <a href="http://www.fitbit.com/group/229YCB" target="_blank">FitBit Group Search</a>! <a href="http://beta.relliker.com/">Try out the new Relliker!</a></p>
    			</div>
		    </div>
	    </div>
	<div id="siteinfowrapper">
		<footer id="siteinfo">
			
		</footer>
	</div>
	</div>
</body>
</html>