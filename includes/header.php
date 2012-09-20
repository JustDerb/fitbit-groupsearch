<?php
	$selected = "class='current cur'";
?>
<header id="branding">
	<h1>
		<img alt="Fitbit" src="images/bg_branding_a.png"> Group Search
	</h1>
</header>
<nav id="sitenav">
	<ul>
		<li>
			<a href="http://www.fitbit.com/" target="_blank">fitbit</a>
		</li>
		<li <?php if ($headerPage == "search") echo $selected ?> >
			<a href="index.php">Search</a>
		</li>
		<li <?php if ($headerPage == "filter") echo $selected ?> >
			<a href="filter.php">Filter</a>
		</li>
		<li <?php if ($headerPage == "about") echo $selected ?>>
			<a href="about.php">About</a>
		</li>
	</ul>
</nav>
