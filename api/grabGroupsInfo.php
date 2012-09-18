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

function fitbit_getGroupsPageExtras($ch, $group)
{
	echo "Extras: ".$group."\n";
	$extras = array();
	$stripchars = array(",");
	$pattern = '/[\d,.]+/i';
	
	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysVeryActive($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	preg_match($pattern, $subject, $matches);
	$extras['veryactive'] = trim(str_replace($stripchars, "", $matches[0]));
	if (empty($extras['veryactive'])) {
		$extras['veryactive'] = "0";
	}	
	$html->clear();
	unset($html);
	unset($matches);
	unset($subject);
	$html = null;
	
	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysSteps($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	preg_match($pattern, $subject, $matches);
	$extras['steps'] = trim(str_replace($stripchars, "", $matches[0]));
	if (empty($extras['steps'])) {
		$extras['steps'] = "0";
	}
	$html->clear();
	unset($html);
	unset($matches);
	unset($subject);
	$html = null;

	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysActivityPoints($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	preg_match($pattern, $subject, $matches);
	$extras['activepoints'] = str_replace($stripchars, "", $matches[0]);
	if (empty($extras['activepoints'])) {
		$extras['activepoints'] = "0";
	}
	$html->clear();
	unset($html);
	unset($matches);
	unset($subject);
	$html = null;

	// -------------------------------------------------------------------------
	$result = fitbit_getPage($ch, get30DaysDistance($group)); 
	
	// Create DOM from URL
	$html = str_get_html($result);
	unset($result);
	
	$subject = $html->find('div[id=groupAggregate]', 0)->plaintext;
	preg_match($pattern, $subject, $matches);
	$extras['distance'] = str_replace($stripchars, "", $matches[0]);
	if (empty($extras['distance'])) {
		$extras['distance'] = "0";
	}
	$html->clear();
	unset($html);
	unset($matches);
	unset($subject);
	$html = null;
	
	unset($pattern);
	unset($stripchars);

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
	
	$query =  "SELECT * FROM groupinfoqueue";
	$result = mysql_query($query, $st_sql);

	while ($row = mysql_fetch_assoc($result)) {
		if (!empty($row['groupid']))
		{
			$extras = fitbit_getGroupsPageExtras($ch, $row['groupid']);
			
			$infoQuery =  "UPDATE groups SET steps='".$extras['steps']."', ";
			$infoQuery .= " activepoints='".$extras['activepoints']."', ";
			$infoQuery .= " distance='".$extras['distance']."', ";
			$infoQuery .= " veryactive='".$extras['veryactive']."' ";
			$infoQuery .= " WHERE groupid='".$row['groupid']."'";
			
			unset($extras);
			
			echo 'Updating...';
			$infoResult = mysql_query($infoQuery, $st_sql);
			// Did we error?
			if (!$infoResult)
			{
				echo "\n".$infoQuery;
				echo "\n\n".mysql_error($st_sql);
				break;
			}
							    
			unset($infoQuery);
			
			echo "Removing...\n";
			$removeQuery =  "DELETE FROM groupinfoqueue WHERE groupid='".$row['groupid']."'";
			$removeResult = mysql_query($removeQuery, $st_sql);
			// Did we error?
			if (!$removeResult)
			{
				echo "\n".$removeQuery;
				echo "\n\n".mysql_error($st_sql);
				break;
			}

			unset($removeQuery);
			
			// Sleep so we don't DDOS the site
			sleep(2);
		}
	}
	
	mysql_free_result($result);
	
	echo "Adding all groups back into pool...\n";
	
	$addQuery =  "INSERT INTO groupinfoqueue ";
	$addQuery .= "SELECT T2.groupid ";
	$addQuery .= "FROM groups T2 ";
	$addQuery .= "LEFT JOIN groupinfoqueue T1 ON T1.groupid = T2.groupid ";
	$addQuery .= "WHERE T1.groupid IS NULL AND T2.groupid IS NOT NULL ";
	
	$addResult = mysql_query($addQuery, $st_sql);
	// Did we error?
	if (!$addResult)
	{
		echo "\n".$addQuery;
		echo "\n\n".mysql_error($st_sql);
		break;
	}
	
	unset($addQuery);

	curl_close($ch);
	unset($ch);
	unset($cookie);
	unset($dir);
?>