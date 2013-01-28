<?php
require_once('includes/localhostStuff.php');
require_once('api/feedbackGrabber.php');

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
<script src="js/feedback.js" type="text/javascript"></script>
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
			<form method="post" action="api/feedback.php">
				<fieldset style="width: 100%">
				<legend>Feedback</legend>
				<textarea class="input-block-level" placeholder="Enter feedback here" rows="3" name="feedback"></textarea>
				<div id="feedbackTypes">
					<label class="caption">Feedback type:</label>
					<label class="radio">
						<input type="radio" name="feedbackType" id="feedback1" value="Idea" checked>
						<span><i class="icon-star"></i> Idea</span>
					</label>
					<label class="radio">
						<input type="radio" name="feedbackType" id="feedback2" value="Remark">
						<span><i class="icon-briefcase"></i> Remark</span>
					</label>
					<label class="radio">
						<input type="radio" name="feedbackType" id="feedback2" value="Problem">
						<span><i class="icon-exclamation-sign"></i> Problem</span>
					</label>
				</div>
				<br/>
				<label class="checkbox muted">
					<input type="checkbox" value="true" name="private">
					Send as private feedback (it will be submitted in the system, but will not show up publicly)
				</label>
				<button class="btn btn-primary pull-right" type="submit">Submit</button>
				</fieldset>
			</form>
		</div>
		<div class="span9">
			<noscript>
			<div class="alert alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4>Uh-Oh!</h4>
				This page may not work correctly if JavaScript is disabled.
			</div>
			</noscript>
<?php
			if (isset($_GET['error']) && !empty($_GET['error'])) {
				$error = urldecode($_GET['error']);
$errorMessage = <<<ERROR
			<div class="alert alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4>Error!</h4>
				{$error}
			</div>
ERROR;
				echo($errorMessage);
			}
			if (isset($_GET['status']) && !empty($_GET['status'])) {
				$status = urldecode($_GET['status']);
$statusMessage = <<<STATUS
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Hey!</strong>
				{$status}
			</div>
STATUS;
				echo($statusMessage);
			}
?>
			<table class="table table-hover">
				<colgroup>
					<col span="1" style="min-width:100px;text-align:right">
					<col span="1" style="width:75px">
					<col span="1" style="width:10em">
					<col span="1">
					<col span="1" style="width:10em">
					<col span="1" style="width:150px">
				</colgroup>
				<thead>
					<tr>
						<th colspan="2" style="text-align:center">Points</th>
						<th></th>
						<th>Type</th>
						<th>Submitted</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
						$feedbackAPI = new feedbackGrabber();
						$data = $feedbackAPI->grabAll();
						
						foreach($data as $key => $value) {
							$points = ($value['points'] > 0 ? "+" : "").$value['points'];
							$type = '<i class="icon-briefcase"></i> Remark';
							if (strcasecmp($value['type'], "Idea") == 0)
							{
								$type = '<i class="icon-star"></i> Idea';
							}
							else if (strcasecmp($value['type'], "Remark") == 0)
							{
								$type = '<i class="icon-briefcase"></i> Remark';
							}
							else if (strcasecmp($value['type'], "Problem") == 0)
							{
								$type = '<i class="icon-exclamation-sign"></i> Problem';
							}
							
							$title = $value['title'];
							$added = $value['added'];
							$message = $value['message'];
							$id = $value['id'];
							
$feedbackRow = <<<FBROW
					<tr>
						<td style="text-align:right">{$points}</td>
						<td><a href="#" id="voteUp" fbid="{$id}"><span class="label label-info"><i class="icon-arrow-up icon-white"></i></span></a> 
						<a href="#" id="voteDown" fbid="{$id}"><span class="label label-info"><i class="icon-arrow-down icon-white"></i></span></a></td>
						<td>{$type}</td>
						<td>{$title}</td>
						<td class="muted">{$added}</td>
						<td><span class="label">{$message}</span></td>
					</tr>
FBROW;
							echo($feedbackRow);
						}
					?>
				</tbody>
			</table>
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