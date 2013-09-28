<?php
require_once('includes/localhostStuff.php');

$G_TITLE = "Relliker - Fitbit Group Search";
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
			<div class="hero-unit">
				<h1>A step in the right direction...</h1>
				<p>You are probably here because FitBit only lists their groups alphabetically, 
				right? Well, this is a search engine that does one better! Search for groups by 
				title and also by descriptions. Go over to the <a href="filter.php">Filter</a> page to see 
				statistics about all the groups on FitBit - like who is most active. Try to 
				search broad keywords, and clarify your search by entering your city or any 
				other information you might think of. Welcome to the new way to search FitBit 
				groups.</p>
				<form class="form-search" method="get" action="search.php" style="text-align:center">
					<input type="text" class="input-large search-query" placeholder="Search terms..." name="s">
					<button type="submit" class="btn btn-primary">Start searching now</button>
				</form>			
			</div>
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
