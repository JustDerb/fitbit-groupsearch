<?php
	$selected = "class='current cur'";
?>
<header id="branding">
	<h1>
		Fitbit Group Search
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
		<li <?php if ($headerPage == "about") echo $selected ?>>
			<a href="about.php">About</a>
		</li>
	</ul>
</nav>
