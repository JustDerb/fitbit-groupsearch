<?php

function clean($elem) 
{ 
    if(!is_array($elem)) 
        $elem = htmlspecialchars($elem); 
    else 
        foreach ($elem as $key => $value) 
            $elem[$key] = $this->clean($value); 
    return $elem; 
} 

	$path = "";
	$content = "";
	$footer = "";
	
	if ($_GET['s'])
	{
		$_GET['s'] = stripslashes($_GET['s']);
		$path = " / <a href='?s=".clean($_GET['s'])."'>".clean($_GET['s'])."</a>";
		
$content = <<<EOT
	<div id="publicgroups">
		<div class="section groups">
			<ul class="grouplist">
				<li class="groupitem">
					<a class="groupname textlimit" href="#">Group Name</a>
					<span class="totalmembers">### Members</span>
					<p class="description textlimit" title="Description here">Description here</p>
				</li>
				<li class="groupitem">
					<a class="groupname textlimit" href="#">Group Name</a>
					<span class="totalmembers">### Members</span>
					<p class="description textlimit" title="Description here">Description here</p>
				</li>
				<li class="groupitem">
					<a class="groupname textlimit" href="#">Group Name</a>
					<span class="totalmembers">### Members</span>
					<p class="description textlimit" title="Description here">Description here</p>
				</li>
				<li class="groupitem">
					<a class="groupname textlimit" href="#">Group Name</a>
					<span class="totalmembers">### Members</span>
					<p class="description textlimit" title="Description here">Description here</p>
				</li>

			</ul>
		</div> 
	</div>
EOT;
	

		$footer = <<<EOT
		<p>Showing # - ## of #,#### total groups.</p>
				<a href="?blah=1">Next page</a>
EOT;
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
					<input placeholder="Group Name or Description" name="s" class="text" type="text" style="width:100%" />
				</div>
				<div class="bLogIn right" style="clear:both;padding:20px 0 0 0">
					<input type="submit" class="ui-button curvyIgnore" />
				</div>
			</form>
		</div>
	</div>
</div>
EOT;

		$footer = <<<EOT
<p></p>
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
</head>
<body class="fb-body droid">
	<div id="container" class="narrowcontainer">
		<header id="branding">
			<h1>
				<a href="index.php" title="Go to homepage">Fitbit Group Search</a>
			</h1>
		</header>
		<nav id="sitenav">
			<ul>
				<li>
					<a href="http://www.fitbit.com/">fitbit</a>
				</li>
				<li class="current cur">
					<a href="index.php">Search</a>
				</li>
				<li>
					<a href="about.php">About</a>
				</li>
			</ul>
		</nav>
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