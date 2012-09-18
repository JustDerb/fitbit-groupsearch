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
		if ($_GET['type'] == 'e0')
		{
			$baseQuery = search_basic($search);
			$note = "(<strong>Basic Search</strong>)";
		}
		else //if ($_GET['type'] == 'e1')
		{
			$baseQuery = search_keywordMatch($search);
			$note = "(<strong>Keyword Search</strong>)";
		}
		
		$query =  "select id, name, description, members, url ".$baseQuery;
		// Remove when we figure out how to get the count
		if ($_GET['type'] == 'e0')
		{
			$totalItemsQ = "SELECT COUNT( * ) AS total \n".$baseQuery;
			$result = mysql_query($totalItemsQ, $st_sql);
			$row = mysql_fetch_assoc($result);
			$totalItems = $row['total'];
		}
		else //if ($_GET['type'] == 'e1')
		{
			$totalItemsQ = "SELECT COUNT( * ) AS total from ( \n".$query." ) as tt";
			$result = mysql_query($totalItemsQ, $st_sql);
			$row = mysql_fetch_assoc($result);
			$totalItems = $row['total'];
		}
		
		$query .= " LIMIT ".$startGroup." , ".$numOfItems;
		//echo $query."\n\n\n".$totalItemsQ."\n\n\n";
		
		$result = mysql_query($query, $st_sql);
				
		$displayed = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$content .= "<li class='groupitem'> \n";
			$content .= "	<a class='groupname textlimit' href='http://www.fitbit.com".$row['url']."' target='_blank'>".$row['name']."</a> \n";
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
		$content .= '<div style="padding:20px"><div class="fb-like" data-href="http://relliker.com" data-send="true" data-width="450" data-show-faces="true" data-action="recommend"></div>';
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
			<h1 style="padding:0 0 10px 0">Welcome!</h1>
			<p>You are probably here because FitBit only lists there groups alphabetically, right?  
			Well, this is a search engine that does one better!  Search for groups by title and also in their descriptions.
			Go over to the Filter page to see statistics about all the groups on FitBit - like who is most active.  Try an search
			broad keywords, and clarify your search by entering your city or any other information you might think of.  Welcome
			to the new way of FitBit Group Search.</p>
			<h2 style="padding:20px 0 10px 0">Search fitbit Groups</h2>
			<p>This will do a search on all group titles and descriptions.</p>
			<form action="" method="get">
				<div class="tInput">
					<input placeholder="Group Keywords" name="s" class="text" type="text" style="width:100%" id="searchTerms" />
					<input placeholder="Full Term Search" name="type" value="e0" type="radio" id="e0search" style="margin:10px 0 0 10px"/>
						<label for="e0search" style="font-size:1.5em">Full Term Search</label>
					<input placeholder="Keyword Search" name="type" value="e1" type="radio" id="e1search" style="margin:10px 0 0 10px" checked="checked"/>
						<label for="e1search" style="font-size:1.5em">Keyword Search</label>
				</div>
				<div class="bLogIn right" style="clear:both;padding:20px 0 0 0">
					<input type="submit" class="ui-button curvyIgnore" />
				</div>
				<div id="locationSearchHeader" style="float:left;font-size:1.5em;margin:1.5em 0 0 10px;width:80%"><div id="locationSearch" style="margin:0.5em 0 0 0"></div></div>
				<div style="clear:both"></div>
			</form>
			<script type="text/javascript">
				var visitorGeolocation = new geolocate(false, true, 'visitorGeolocation');
				 
				var callback = function(){
					$('#locationSearchHeader').prepend('Have you tried: ');
                	addHelpTerm(visitorGeolocation.getField('cityName'));
                	addHelpTerm(visitorGeolocation.getField('regionName'));
                	addHelpTerm(visitorGeolocation.getField('countryCode'));
                	addHelpTerm(visitorGeolocation.getField('zipCode'));
                };
				visitorGeolocation.checkcookie(callback);
			</script>
			<div style="padding:20px 0px 0px 0px"><div class="fb-like" data-href="http://relliker.com" data-send="true" data-width="450" data-show-faces="true" data-action="recommend"></div></div>
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
<link rel="stylesheet" type="text/css" href="css/css1.css" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/css2.css" charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript"  src="js/setTextLimits.js"  charset="utf-8" ></script>
<script type="text/javascript"  src="js/geolocate.js"  charset="utf-8" ></script>
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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=350050445089720";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<a href="https://github.com/JustDerb/fitbit-groupsearch" target="_blank">
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