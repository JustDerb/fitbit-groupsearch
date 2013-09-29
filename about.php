<?php
require_once('includes/localhostStuff.php');

$G_TITLE = "About - Relliker";
$G_DESCRIPTION = "A group search tool for fitbit.";

require_once ('includes/page_timer.php');
$timer = new page_timer();
$timer -> start();
?>
<!DOCTYPE html>
<html>

<head>
<!-- OpenGraph -->
<meta content="<?php echo($G_TITLE); ?>" property="og:title" />
<meta content="website" property="og:type" />
<meta content="http://www.relliker.com/" property="og:url" />
<meta content="<?php echo($G_DESCRIPTION); ?>" property="og:description" />
<meta content="http://relliker.com/images/bg_branding_a.png" property="og:image" />
<meta property="fb:admins" content="1173840925" />
<!-- End OpenGraph -->
<title><?php echo($G_TITLE); ?></title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" media="screen" rel="stylesheet">
<style>
body { padding-top: 40px; }
@media screen and (max-width: 768px) {
    body { padding-top: 0px; }
}
</style>
<!-- Mobile friendly -->
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<!-- End Mobile friendly -->
<link href="css/custom.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<!-- End Bootstrap -->
<!-- Custom javascript -->
<!-- End Custom javascript --><?php
require_once ('includes/analytics.php');
?>
</head>

<body>
<?php include_once('includes/navbar.php'); ?>
<!-- Main content -->
<div class="container-fluid">
	<div class="row-fluid">
		<div style="height:4em"></div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			<h2>About this site</h2>
			<p>This site was made by a fellow fitbit-er to provide the community an easy way of 
			finding groups within the fitbit.com website. Currently, you can only page through 
			the groups in alphabetical order! So, this site provides keyword-based searching and
			a lot of other useful tools to find the right group for you.</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			<h2>FAQ</h2>
			<div class="well">
				<ol>
					<li><a href="#faq1">What is this site?</a></li>
					<li><a href="#faq2">Why doesn't FitBit have a search feature?</a></li>
					<li><a href="#faq3">I just made a group and I can't find it on here!</a></li>
					<li><a href="#faq4">I got an issue or suggestion</a></li>
					<li><a href="#faq5">Why is this website called Relliker.com?</a></li>
					<li><a href="#faq6">Why are there ads?</a></li>
				</ol>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			<ol class="unstyled">
				<li id="faq1">
					<h4>What is this site?</h4>
					<p>This is a website to help FitBit users easily search the fitbit community 
					groups so that they don't have to wade through pages and pages of groups only 
					to get nowhere!</p>
				</li>
				<li id="faq2">
					<h4>Why doesn't FitBit have a search feature?</h4>
					<p>I don't know! You should ask them!</p>
				</li>
				<li id="faq3">
					<h4>I just made a group and I can't find it on here!</h4>
					<p>I scrape FitBit's server about everyday so please wait at least 24 hours 
					for your group will show up.</p>
				</li>
				<li id="faq4">
					<h4>I got an issue or suggestion</h4>
					<p>Great! Head on over to <a href="http://www.fitbit.com/group/229YCB">FitBit Group Search</a> 
					and create a topic discussion or email me at <u>admin [at] relliker.com</u></p>
				</li>
				<li id="faq5">
					<h4>Why is this website called Relliker.com?</h4>
					<p>I don't know. It was just a domain name I bought... I'll buy a better one eventually.</p>
				</li>
				<li id="faq6">
					<h4>Why are there ads?</h4>
					<p>Well, since this website is a side project for me an I am paying for all hosting costs, I 
					want to offset some of the costs by putting Google Adsense Ads on the site. Some people do not 
					like this but it's something I have to do to not shut down the site.</p>
				</li>
			</ol>
		</div>
	</div>
	<div class="row-fluid">
		<div style="min-height:8em;text-align:center">
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
			<p><a href="about.php#faq6" class="muted">Why are there ads?</a></p>
		</div>
	</div>
</div>
<?php include_once("includes/footer.php"); ?>
</body>

</html>