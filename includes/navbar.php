<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=350050445089720";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Navbar -->
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="index.php">relliker</a>
			<form class="navbar-form pull-left" method="get" action="search.php">
				<input class="search-query" placeholder="Search terms..." type="text" name="s" id="navSearch">
				<button type="submit" class="btn btn-small btn-inverse"><i class="icon-search icon-white"></i></button>
			</form>
			<ul class="nav pull-right">
				<li><a href="search.php"><i class="icon-search"></i> Search</a></li>
				<li><a href="filter.php"><i class="icon-filter"></i> Filter</a></li>
				<!--
				<li><a href="forum/"><i class="icon-comment"></i> Forum</a></li> 
				-->
				<li><a href="feedback.php"><i class="icon-bullhorn"></i> Feedback</a></li>
				<!--
				<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-user"></i> Account<b class="caret"></b></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="#">Login</a></li>
				</ul>
				</li>
				-->
			</ul>
			<div class="pull-right" id="socialbuttons" style="padding:10px 0px;overflow:hidden;max-height:20px">
				<div class="fb-like" data-href="http://www.relliker.com/" data-send="true" data-layout="button_count" data-width="400" data-show-faces="false" data-action="recommend"></div>
				<div class="g-plusone" data-size="medium" data-href="www.relliker.com"></div>
			</div>
		</div>
	</div>
</div>