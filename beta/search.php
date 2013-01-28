<?php
require_once('includes/localhostStuff.php');
require_once('api/search.php');

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
		<div class="span3">
			<div class="row-fluid">
				<div class="span12">
					<form method="get">
						<fieldset style="width: 100%">
						<legend>Search</legend>
						<input class="input-block-level" placeholder="Search terms..." type="text" name="s">
						<?php
/*						
						<legend class="muted">Additional Options</legend>
						<div id="searchOptions">
							<label for="minMembers">Number of members:</label>
							<div class="input-prepend">
								<span class="add-on">&gt;</span>
								<input name="minMembers" class="span4" placeholder="1" type="number" value="1">
							</div>
							<label for="minSteps">Average steps:</label>
							<div class="input-prepend input-append">
								<span class="add-on">&gt;</span>
								<input name="minSteps" class="span4" placeholder="0" type="number" value="0">
								<span class="add-on">steps</span>
							</div>
							<label for="minActivePoints">Average active points:</label>
							<div class="input-prepend input-append">
								<span class="add-on">&gt;</span>
								<input name="minActivePoints" class="span4" placeholder="0" type="number" value="0">
								<span class="add-on">pts</span>
							</div>
							<label for="minDistance">Average distance:</label>
							<div class="input-prepend input-append">
								<span class="add-on">&gt;</span>
								<input name="minDistance" class="span4" placeholder="0" type="number" value="0">
								<span class="add-on">miles</span>
							</div>
							<label for="minActiveMinutes">Average active minutes:</label>
							<div class="input-prepend input-append">
								<span class="add-on">&gt;</span>
								<input name="minActiveMinutes" class="span4" placeholder="0" type="number" value="0">
								<span class="add-on">min</span>
							</div>
						</div>
*/						
						?>
						<button class="btn btn-primary pull-right" type="submit">Submit</button>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="row-fluid hidden-phone">
				<div class="span12" style="text-align:center;overflow:auto">
<?php
	if (!defined("LOCALHOST")) {
		$googleAdsense = <<<ADSENSE
			<script type="text/javascript"><!--
			google_ad_client = "ca-pub-8861318913253064";
			/* Skyscraper - General */
			google_ad_slot = "5731148814";
			google_ad_width = 300;
			google_ad_height = 600;
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
		echo(getAdBlock(300,600));
	}
?>
					<p><a href="about.php#faq6" class="muted">Why are there ads?</a></p>
				</div>
			</div>
		</div>
		<div class="span9">
<?php if (@$_GET['s']): ?>
			<h4>Results for "<strong><?php echo(htmlentities($_GET['s'])); ?></strong>"</h4>
			<table class="table table-hover">
				<colgroup>
					<col span="1" style="width: 10%">
					<col span="1" style="width: 50%">
					<col span="1" style="width: 10%">
					<col span="1" style="width: 10%">
					<col span="1" style="width: 10%">
					<col span="1" style="width: 10%">
				</colgroup>
				<thead>
					<tr>
						<th>Members</th>
						<th>Group Name / Description</th>
						<th>Steps</th>
						<th>Active Points</th>
						<th>Distance (miles)</th>
						<th>Very Active (minutes)</th>
					</tr>
				</thead>
				<tbody>
				<?php
					// Lets search!
					//if (@$_GET['s'])
					//{
						// Get page number
						if (is_int2(@$_GET['p']))
						{
							$pageNum = st_mysql_encode($_GET['p'],$st_sql);
						}
						else
						{
							$pageNum = 0;
						}
							
						// Protect ourselves
						$_GET['s'] = stripslashes($_GET['s']);
						$search = st_mysql_encode($_GET['s'],$st_sql);
						
						// Search
						$searchAPI = new fitbitSearch();
						$numOfItems = 50;
						$searchResults = $searchAPI->search($search,$pageNum,$numOfItems);	
						
$advertisement = <<<SAMPLE
					<tr>
						<td colspan="6"><strong>This is an advertisment This is an advertisment This is an advertisment This is an advertisment This is an advertisment This is an advertisment This is an advertisment This is an advertisment This is an advertisment This is an advertisment </strong></td>
					</tr>
SAMPLE;
						$resultNum = 0;
						$numAds = 0;
						foreach ($searchResults as $key => $value) 
						{
							$resultNum++;
							$members = number_format($value['members']);
							$title = $value['title'];
							$description = $value['description'];
							$steps = number_format($value['steps']);
							$actPts = number_format($value['activepoints']);
							$dist = number_format($value['distance']);
							$va = number_format($value['veryactive']);
$result = <<<RESULT
					<tr>
						<td><span class="label label-info">{$members}</span></td>
						<td><a href="#" target="_blank">{$title}</a><br />
						{$description}</td>
						<td><span class="label">{$steps}</span></td>
						<td><span class="label label-success">{$actPts}</span></td>
						<td><span class="label label-warning">{$dist}</span></td>
						<td><span class="label label-important">{$va}</span></td>
					</tr>
RESULT;
							// Only put 3 ads
							if ($numAds < 3 && ($resultNum + 2) % 5 == 0) 
							{
								$googleAdsense = "";
								if (!defined("LOCALHOST")) {
$googleAdsense = <<<ADSENSE
			<script type="text/javascript"><!--
			google_ad_client = "ca-pub-8861318913253064";
			/* Search Results Ad */
			google_ad_slot = "4254415611";
			google_ad_width = 468;
			google_ad_height = 15;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
ADSENSE;
								}
								else
								{
									$googleAdsense = getAdBlock(468,15);
								}
								
$googleAdsenseHTML = <<<RESULT
					<tr>
						<td colspan="6" style="text-align:center">{$googleAdsense}</td>
					</tr>
RESULT;
								
								$numAds++;
								echo($googleAdsenseHTML);
							}
							echo($result);
						}
						
						// Results footer
						$startGroup = $pageNum*$numOfItems;
						$totalItemsFormatted = number_format($searchAPI->getTotal());
						$dispPlusStart = ($startGroup+count($searchResults));
$resultsFooter = <<<FOOTER
					<tr>
						<td colspan="6">Showing {$startGroup} - {$dispPlusStart} of {$totalItemsFormatted} total groups.</td>
					</tr>
FOOTER;
						echo($resultsFooter);
						
						// Pagination tabs
						$prevPage = "";
						$nextPage = "";
						if ($pageNum != 0) 
							$prevPage .= '<li class="previous"><a href="?s='.$_GET['s'].'&p='.($pageNum-1).'">&larr; Previous</a></li>';
						else
							$prevPage .= '<li class="previous disabled"><a href="#">&larr; Previous</a></li>';
						if (($startGroup+count($searchResults)) < $searchAPI->getTotal())
							$nextPage .= '<li class="next"><a href="?s='.$_GET['s'].'&p='.($pageNum+1).'">Next &rarr;</a></li>';
						else
							$nextPage .= '<li class="next disabled"><a href="#">Next &rarr;</a></li>';
						echo('<tr><td colspan="6"><ul class="pager">'.$prevPage.$nextPage.'</ul></td></tr>');
					//}					
				?>
				</tbody>
			</table>
<?php else: ?>
			<div class="visible-desktop" style="height:8em">
			</div>
			<div class="hero-unit">
				<h1>Looking for something?</h1>
				<p>Enter your search terms and begin finding your groups!</p>
			</div>
<?php endif ?>
		</div>
	</div>
</div>
<?php include_once("includes/footer.php"); ?>
</body>

</html>
