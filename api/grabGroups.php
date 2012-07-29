<?php
require_once '../nogit/fitbit_login.php';
require_once 'simple_html_dom.php';
require_once 'sql_functions.php';

// Cookie file for cURL
$dir = dirname(__FILE__);
$cookie=$dir."/../nogit/fitbit_cookie"; 

function get30DaysVeryActive($group) {
	return "http://www.fitbit.com/stats/leaders?rankByStatistic=cum_mins_very_active_30_days&cnid=4.".$group."&includeViewerValues=false&start=0&count=3&extraStatistics=engaged_avg_mins_very_active_30_days&valueWhenNotAvailable=--&label=mins&updateRate=DAILY";
}

function get30DaysSteps($group) {
	return "http://www.fitbit.com/stats/leaders?rankByStatistic=cum_steps_30_days&cnid=4.".$group."&includeViewerValues=false&start=0&count=3&extraStatistics=engaged_avg_steps_30_days&valueWhenNotAvailable=--&label=steps&updateRate=DAILY";
}

function get30DaysActivityPoints($group) {
	return "http://www.fitbit.com/stats/leaders?rankByStatistic=cum_activity_30_days&cnid=4.".$group."&includeViewerValues=false&start=0&count=3&extraStatistics=&valueWhenNotAvailable=--&label=pts&updateRate=DAILY";
}

function get30DaysDistance($group) {
	return "http://www.fitbit.com/stats/leaders?rankByStatistic=cum_distance_miles_30_days&cnid=4.".$group."&includeViewerValues=false&start=0&count=3&extraStatistics=engaged_avg_distance_miles_30_days&valueWhenNotAvailable=--&label=miles&updateRate=DAILY";
}

function fitbit_login($ch, $username, $password)
{
	global $cookie;

	// Login page
	$loginurl="https://www.fitbit.com/login"; 
	$postdata = "email=".$username."&password=".$password."&rememberMe=true&login=".urlencode("Log In")."&includeWorkflow=&redirect="; 
	
	curl_setopt ($ch, CURLOPT_URL, $loginurl); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
	curl_setopt ($ch, CURLOPT_REFERER, "http://www.fitbit.com/"); 
	
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata); 
	curl_setopt ($ch, CURLOPT_POST, 1); 
	$result = curl_exec ($ch); 
	
	unset($loginurl);
	unset($postdata);
		
	return $result;
}

function fitbit_getPage($ch, $url) {
	global $cookie;

	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie); 
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
	curl_setopt ($ch, CURLOPT_REFERER, "http://www.fitbit.com/"); 
	
	curl_setopt ($ch, CURLOPT_POSTFIELDS, ""); 
	curl_setopt ($ch, CURLOPT_POST, 0); 
	curl_setopt ($ch, CURLOPT_HTTPGET, 1);

	return curl_exec ($ch); 
}

function fitbit_getGroupsPage($ch, $page) {
 	global $cookie;
 
	// Group page
	$groupsurl="http://www.fitbit.com/groups"; 
	if ($page > 0)
	{
		// GET var for paging
		$pageVar="?start=".($page*12);
	}
	else
		$pageVar = "";
	
	$url = $groupsurl.$pageVar;
	return  fitbit_getPage($ch, $url);
}

function fitbit_getGroupsPageExtras($ch, $group)
{
	echo '';
	$extras = array();
	$stripchars = array(",");
	
	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysVeryActive($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	$pattern = '/[\d,.]+/i';
	preg_match($pattern, $subject, $matches);
	$extras['veryactive'] = trim(str_replace($stripchars, "", $matches[0]));
	if (empty($extras['veryactive'])) {
		$extras['veryactive'] = "0";
	}	
	
	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysSteps($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	$pattern = '/[\d,.]+/i';
	preg_match($pattern, $subject, $matches);
	$extras['steps'] = trim(str_replace($stripchars, "", $matches[0]));
	if (empty($extras['steps'])) {
		$extras['steps'] = "0";
	}

	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysActivityPoints($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	$pattern = '/[\d,.]+/i';
	preg_match($pattern, $subject, $matches);
	$extras['activepoints'] = str_replace($stripchars, "", $matches[0]);
	if (empty($extras['activepoints'])) {
		$extras['activepoints'] = "0";
	}

	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysDistance($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	$pattern = '/[\d,.]+/i';
	preg_match($pattern, $subject, $matches);
	$extras['distance'] = str_replace($stripchars, "", $matches[0]);
	if (empty($extras['distance'])) {
		$extras['distance'] = "0";
	}
	
	return $extras;
}

function stripSpacesOut($text)
{
	$text = trim($text);
	$text = preg_replace('/\s{2,}/',' ',$text);
	return $text;
}
	
	$ch = curl_init(); 
	fitbit_login($ch, $username,$password);
	
	$pageNum = 0;
	$prevPageSet = -1;
	while ($pageNum < 2600)
	{
		echo "Memory used: ".memory_get_usage()."\n";
		echo "PageNum: ".$pageNum."\n";
		$result = fitbit_getGroupsPage($ch, $pageNum);
		//echo($result);

		// Create DOM from URL
		$html = str_get_html($result);
		unset($result);
		
		$error = false;
		// Find all article blocks
		foreach($html->find('li[class=groupItem]') as $group) {
			$groupName = $group->find('a[class=groupName]', 0);
		    $title       = st_mysql_encode(stripSpacesOut($groupName->innertext),$st_sql);
		    $href        = st_mysql_encode($groupName->href,$st_sql);
		    $members     = stripSpacesOut($group->find('span[class=totalMembers]', 0)->plaintext,$st_sql);
		    preg_match_all('/\d+/', $members, $matches);
			if (count($matches[0]) > 0)
				$members = st_mysql_encode($matches[0][0],$st_sql);
			else
			{
				echo "ERROR: No member number found it: ".$members;
				$error = true;
				break;
			}
		    $description = st_mysql_encode(stripSpacesOut($group->find('p[class=description]', 0)->plaintext),$st_sql);
		    $group = st_mysql_encode(substr($groupName->href, 7),$st_sql);
		    
		    $query =  "INSERT INTO  groups (id,name,members,description,url,groupid) VALUES (NULL ,  '$title',  '$members',  '$description',  '$href', '$group') \n";
		    $query .= "ON DUPLICATE KEY \n";
		    $query .= "UPDATE name='$title', members='$members', description='$description', groupid='$group'";
		    
		    unset($groupName);
		    unset($title);
		    unset($members);
		    
		    $result = mysql_query($query, $st_sql);
		    // Did we error?
		    if (!$result)
		    {
		    	echo "\n".$query;
		    	echo "\n\n".mysql_error($st_sql);
		    	$error = true;
		    	break;
		    }		 
		    
			// Get extras
			$extras = fitbit_getGroupsPageExtras($ch, $group);
			
			$query =  "UPDATE groups SET steps='".$extras['steps']."', ";
			$query .= " activepoints='".$extras['activepoints']."', ";
			$query .= " distance='".$extras['distance']."', ";
			$query .= " veryactive='".$extras['veryactive']."' ";
			$query .= " WHERE url='".$href."'";
		    
		    unset($group);
		    unset($href);
		    
		    $result = mysql_query($query, $st_sql);
		    // Did we error?
		    if (!$result)
		    {
		    	echo "\n".$query;
		    	echo "\n\n".mysql_error($st_sql);
		    	//$error = true;
		    	//break;
		    }
		}
				
		if ($error)
			break;
				
		$footer = $html->find('div[id=contentFooter]', 0);
		$pTag = $footer->find('p', 0);
		$aTag = $footer->find('a');
		
		if (count($aTag) > 0)
		{
			preg_match_all('/\d+/', $aTag[count($aTag)-1]->href, $matches);
			if (count($matches[0]) > 0)
			{
				echo " | Next ?Start=: ".$matches[0][0]."\n";
				if ($matches[0][0] == $prevPageSet)
					break;
				$prevPageSet = $matches[0][0];
				$pageNum++;
			}
			else
				break;
		}
			
		// Clean up
		unset($result);
		$html->clear();
		unset($html);
		$html = null;
		unset($footer);
		unset($pTag);
		unset($aTag);
		
		// Sleep so we don't DDOS the site
		sleep(2);
	}

	curl_close($ch);
	unset($ch);
	unset($pageNum);
	unset($prevPageSet);
	unset($cookie);
	unset($dir);
?>