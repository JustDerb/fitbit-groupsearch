<?php
include_once 'includes/sqlSearch.php';

function clean($elem) 
{ 
    if(!is_array($elem)) 
        $elem = htmlspecialchars($elem); 
    else 
        foreach ($elem as $key => $value) 
            $elem[$key] = $this->clean($value); 
    return $elem; 
} 
function is_int2($v) {
  $i = intval($v);
  if ("$i" == "$v") {
    return TRUE;
  } else {
    return FALSE;
  }
}

	$path = "";
	$content = "";
	$footer = "";
	
	if ($_GET['s'])
	{
		require_once 'api/sql_functions.php';
		$numOfItems = 24;
		
		if (is_int2($_GET['p']))
		{
			$pageNum = st_mysql_encode($_GET['p'],$st_sql);
		}
		else
			$pageNum = 0;
			
		$startGroup = $pageNum*$numOfItems;
		
		$_GET['s'] = stripslashes($_GET['s']);
		$_GET['type'] = stripslashes($_GET['type']);
		
		$content =  "<div id='publicgroups'>";
		$content .= "<div class='section groups'>";
		$content .= "<ul class='grouplist'>";

		$search = st_mysql_encode($_GET['s'],$st_sql);
		
		$note = "";
		if (!$_GET['type'])
		{
			$baseQuery = search_basic($search);
			$note = "(<strong>Basic Search</strong>)";
		}
		else
		{
			$baseQuery = search_keywordMatch($search);
			$note = "(<strong>Experimental Search</strong> - Number of results cannot be calculated right now...)";
		}
		
		$query =  "select id, name, description, members, url ".$baseQuery." LIMIT ".$startGroup." , ".$numOfItems;
		// Remove when we figure out how to get the count
		if (!$_GET['type'])
		{
			$totalItemsQ = "SELECT COUNT( * ) AS total \n".$baseQuery;
			$result = mysql_query($totalItemsQ, $st_sql);
			$row = mysql_fetch_assoc($result);
			$totalItems = $row['total'];
		}
		else
		{
			$totalItems = 9999;
		}
		//echo $query."\n\n\n".$totalItemsQ."\n\n\n";
		
		$result = mysql_query($query, $st_sql);
				
		$displayed = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$content .= "<li class='groupitem'> \n";
			$content .= "	<a class='groupname textlimit' href='http://www.fitbit.com".$row['url']."'>".$row['name']."</a> \n";
			$content .= "	<span class='totalmembers'>".$row['members']." Members</span> \n";
			$content .= "	<p class='description textlimit4' title=\"".$row['description']."\">".$row['description']."</p> \n";
			$content .= "</li>\n";
			$displayed++;
		}
		
		if ($displayed == 0)
		{
			$content .= <<<EOT
<div class="innerpading">
	<div class"alreadyMember panelSwitch panelSwitchConnected  left">
		<div class="column column1 form clearfix curvyIgnore">
			<h2 style="padding:0 0 20px 0">No fitbit Groups Found!</h2>
			<p><a href="index.php">Try another search</a></p>
		</div>
	</div>
</div>
EOT;
		}
				
		$content .= "</ul><script type='text/javascript'>$.setTextLimits();</script>";
		$content .= "</div>";
		$content .= "</div>";
		
		
		$path =  " / <a href='?s=".clean($_GET['s'])."&type=".$_GET['type']."'>".clean($_GET['s'])."</a> (".number_format($totalItems)." results) ".$note."<div style='float:right'>";	
		
		$footer = "<p>Showing ".$startGroup." - ".($startGroup+$displayed)." of ".number_format($totalItems)." total groups. ".$note."</p>";
		if ($pageNum != 0) 
		{
			$path .= "<a href='?s=".$_GET['s']."&type=".$_GET['type']."&p=".($pageNum-1)."'>Previous page</a> ";
			$footer .= "<a href='?s=".$_GET['s']."&type=".$_GET['type']."&p=".($pageNum-1)."'>Previous page</a> ";
		}
		if (($startGroup+$displayed) < $totalItems)
		{
			$path .= "<a href='?s=".$_GET['s']."&type=".$_GET['type']."&p=".($pageNum+1)."'>Next page</a> ";
			$footer .= "<a href='?s=".$_GET['s']."&type=".$_GET['type']."&p=".($pageNum+1)."'>Next page</a>";
		}
			
		$path .= "</div>";
	}
	else
	{
		$path = "";
		$content = <<<EOT
<div class="innerpading">
	<div class"alreadyMember panelSwitch panelSwitchConnected  left">
		<div class="column column1 form clearfix curvyIgnore">
			<h2 style="padding:0 0 20px 0">Search fitbit Groups</h2>
			<form action="" method="get">
				<div class="tInput">
					<input placeholder="Group Keywords" name="s" class="text" type="text" style="width:100%" id="searchTerms" />
					<input placeholder="Experimental search" name="type" value="e1" type="checkbox" id="e1search" style="margin:10px 0 0 10px"/>
						<label for="e1search" style="font-size:1.5em">Experimental search (Improved searching algorithm.  Number of results cannot be calculated right now...)</label>
				</div>
				<div class="bLogIn right" style="clear:both;padding:20px 0 0 0">
					<input type="submit" class="ui-button curvyIgnore" />
				</div>
			</form>
		</div>
	</div>
</div>
<div style="height:400px"></div>
EOT;

		$footer = <<<EOT
<p>Join the <a href="http://www.fitbit.com/group/229YCB" target="_blank">FitBit Group Search</a>!</p>
EOT;
	}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>fitbit - Group Search</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/css1.css" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/css2.css" charset="utf-8">
<script type="text/javascript"  src="js/setTextLimits.js"  charset="utf-8" ></script>
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
	<a href="https://github.com/JustDerb/fitbit-groupsearch">
		<img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png" alt="Fork me on GitHub">
	</a>
	<div id="container" class="narrowcontainer">
		<?php 
			$headerPage = "search";
			include 'includes/header.php' 
		?>
		<div id="content" class="allgroups">
			<div id="contenthead">
				<div class="groupnav">
					<a href="index.php">Search</a><?php echo $path ?>
				</div>
			</div>
			<div id="contentbody" class="primary">
				<?php echo $content ?>
			</div>
			<div id="contentfooter">
				<?php echo $footer ?>
			</div>
		</div>
	</div>
	<div id="siteinfowrapper">
		<footer id="siteinfo">
			
		</footer>
	</div>
</body>
</html>