<?php
require_once('includes/localhostStuff.php');
require_once('api/group.php');

$G_TITLE = "Relliker - Fitbit Group Search";
$G_DESCRIPTION = "A group search tool for fitbit.";

require_once ('includes/page_timer.php');
$timer = new page_timer();
$timer -> start();

// Get group
$groupID = "";
$groupObj = NULL;

if (isset($_GET['g']))
{
	$groupID = $_GET['g'];
	try {
		$groupObj = new FitbitGroup($groupID);
	} 
	catch (NoGroupFound $ex) {
	}
}

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
.g-graph {
	min-height: 200px;
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
<?php if (isset($groupObj)): ?>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
  google.load('visualization', '1', {packages:['gauge', 'corechart']});
  //google.setOnLoadCallback(drawActiveChart);
  google.setOnLoadCallback(drawStatsChart);
  function drawActiveChart() {
    var data = google.visualization.arrayToDataTable([
      ['Label', 'Value'],
      ['Activeness', <?php echo($groupObj->getActiveness()); ?>]
    ]);

    var options = {
      min: 0, max: 100,
      redFrom: 0, redTo: 33,
      yellowFrom: 33, yellowTo: 66,
      greenFrom: 66, greenTo: 100,
      minorTicks: 5
    };

    var chart = new google.visualization.Gauge(document.getElementById('chart_div_activeness'));
    chart.draw(data, options);
  }
  function drawStatsChartByCriteria(element, type) {
  	$.ajax({
      url: "api/json/groupStats.php",
      data: {
      	'g': '<?php echo($groupObj->getGroupID()) ?>',
      	'type' : type
      },
      dataType:"json",
      async: true
    }).done(function ( jsonData ) {
		var data = new google.visualization.DataTable(jsonData);

	    var options = {
	      animation:{
	        duration: 1000,
	        easing: 'out',
	      },
	      legend:{
	      	position: 'none'
	      }
	    };

	    var chart = new google.visualization.LineChart(element);
	    chart.draw(data, options);
	}).fail(function(msg) { 
		element.innerHTML = 'Error loading graph :(';
	});    
  }
  function drawStatsChart() {
  	drawStatsChartByCriteria(document.getElementById('chart_div_stats_steps'), 'steps');
  	drawStatsChartByCriteria(document.getElementById('chart_div_stats_activepoints'), 'activepoints');
    drawStatsChartByCriteria(document.getElementById('chart_div_stats_distance'), 'distance');
    drawStatsChartByCriteria(document.getElementById('chart_div_stats_veryactive'), 'veryactive');
  }
</script>
<?php endif ?>
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
			<div class="hidden-phone" style="height:1em">
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
<?php if (isset($groupObj)): ?>
			<div class="visible-desktop" style="height:1em">
			</div>
			<div class="row-fluid">
				<div class="span8">
					<h3><?php echo($groupObj->getName()); ?></h3>
					<h5><a href="http://www.fitbit.com/group/<?php echo($groupObj->getGroupID()); ?>/members" target="_blank">
						<?php echo($groupObj->getNumberOfMembers()); ?> members
					</a></h5>
					<p><?php echo($groupObj->getDescription()); ?></p>
					<p><a href="http://www.fitbit.com/group/<?php echo($groupObj->getGroupID()); ?>" class="btn btn-large btn-primary" target="_blank">
					Visit Group on Fitbit.com <i class="icon-chevron-right icon-white"></i>
					</a></p>
				</div>
				<div class="span4" style="text-align:center">
					<div id='chart_div_activeness' style="margin:0 auto;display:block"></div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<h4><i class="icon-time"></i> Latest stats (30 days average):</h4>
					<table class="table">
						<thead>
							<tr>
								<th>Steps</th>
								<th>Active Points</th>
								<th>Distance (miles)</th>
								<th>Very Active (minutes)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="label"><?php echo($groupObj->getLatestSteps()); ?></span></td>
								<td><span class="label label-success"><?php echo($groupObj->getLatestActivePoints()); ?></span></td>
								<td><span class="label label-warning"><?php echo($groupObj->getLatestDistance()); ?></span></td>
								<td><span class="label label-important"><?php echo($groupObj->getLatestVeryActive()); ?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12" style="min-height:300px">
					<h4><i class="icon-road"></i> Stats History (30 days average):</h4>
					<h5>Steps</h5>
					<div id="chart_div_stats_steps" class="g-graph">Loading...</div>
					<h5>Active Points</h5>
					<div id="chart_div_stats_activepoints" class="g-graph">Loading...</div>
					<h5>Distance (miles)</h5>
					<div id="chart_div_stats_distance" class="g-graph">Loading...</div>
					<h5>Very Active (minutes)</h5>
					<div id="chart_div_stats_veryactive" class="g-graph">Loading...</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<p class="muted"><i>
						This group was last updated with our servers on <?php echo($groupObj->getLastUpdateDateTime()); ?>.
					</i></p>
				</div>
			</div>
<?php else: ?>
			<div class="visible-desktop" style="height:8em">
			</div>
			<div class="hero-unit">
				<h1>No group found!</h1>
				<p>Try searching for a group in the top right!</p>
			</div>
<?php endif ?>
		</div>
	</div>
</div>
<?php include_once("includes/footer.php"); ?>
</body>

</html>
