<footer class="footer">
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span4 visible-desktop" style="text-align:right">
				<p class="muted">Information from:</p>
				<a href="http://www.fitbit.com/"><img src="img/fitbit_logo_dark_100px.png" alt="fitbit.com"></a>
				<div>
					<span class="pull-right">
						<p class="muted">History by:</p>
						<a href="https://github.com/JustDerb/fitbit-groupsearch"><img src="img/github_logo.png" alt="github.com"></a>
					</span>
					<span class="pull-right" style="width:20px">&nbsp;</span>
					<span class="pull-right">
						<p class="muted">Written in:</p>
						<img src="img/php-med-trans.png" alt="php.net">
					</span>
				</div>
			</div>
			<div class="span4 visible-desktop" style="text-align:center">
			</div>
			<div class="span4" style="text-align:left">
				<ul class="unstyled">
					<li><a href="index.php" class="muted">Home</a></li>
					<li><a href="search.php" class="muted">Search</a></li>
					<li><a href="filter.php" class="muted">Filter</a></li>
					<!--
					<li><a href="forum/" class="muted">Forum</a></li>
					-->
					<li><a href="feedback.php" class="muted">Feedback</a></li>
					<li><a href="about.php" class="muted">About</a></li>
				</ul>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4 offset4" style="text-align:center">
				<p class="muted">Made with <i class="icon-heart"></i> by <a href="https://github.com/JustDerb" class="muted">JustDerb</a></p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12 muted" style="text-align:right">
				<?php
				if (isset($timer)) {
				$timer -> stop();
				echo('<p><small><em>t</em> = ');
				echo($timer -> elapsed(8));
				echo('</small></p>');
				}
			?></div>
		</div>
	</div>
</footer>