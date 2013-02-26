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
			<form>
				<fieldset style="width: 100%" class="tInput">
				<legend>Filter</legend>
				<label class="radio">
					<input placeholder="Top Members" name="type" value="members" type="radio" id="fmembers"/>
					Top Members
				</label>
				<label class="radio">
					<input placeholder="Most Steps (30 Days)" name="type" value="steps" type="radio" id="fsteps"/>
					Most Steps (30 Days)
				</label>
				<label class="radio">
					<input placeholder="Most Active Points (30 Days)" name="type" value="activepoints" type="radio" id="factivepoints"/>
					Most Active Points (30 Days)
				</label>
				<label class="radio">
					<input placeholder="Top Distance (30 Days)" name="type" value="distance" type="radio" id="fdistance"/>
					Top Distance (30 Days)
				</label>
				<label class="radio">
					<input placeholder="Most Active (30 Days)" name="type" value="veryactive" type="radio" id="fveryactive"/>
					Most Active (30 Days)
				</label>
				<br/>
				<div>
					Number of results to show:
					<select id="shownumber">
						<option value="30">First 30</option>
						<option value="60">First 60</option>
						<option value="90">First 90</option>
						<option value="-1">Show All</option>
					</select>
				</div>
				</fieldset>
			</form>
		</div>
		<div class="span9" style="min-height:500px">
			<h4>Choose your filters to see results</h4>
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
						<th>Distance (miles</th>
						<th>Very Active (minutes)</th>
					</tr>
				</thead>
				<tbody id="filterResults">
					<tr>
						<td colspan="6"><i class="icon-arrow-left"></i> Choose your filters to see results</td>
					</tr>
				</tbody>
			</table>
			<script type="text/javascript"  src="js/filter.js"  charset="utf-8" ></script>
			<script type="text/javascript" charset="utf-8" >updateData();</script>
		</div>
	</div>
</div>
<?php include_once("includes/footer.php"); ?>
</body>

</html>
