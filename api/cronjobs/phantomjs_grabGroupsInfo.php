<?php
$PJSGGI_dir = dirname(__FILE__);
$DR = DIRECTORY_SEPARATOR;
require_once ($PJSGGI_dir.$DR.'..'.$DR.'..'.$DR.'nogit'.$DR.'fitbit_login.php');
require_once ($PJSGGI_dir.$DR.'simple_html_dom.php');
require_once ($PJSGGI_dir.$DR.'..'.$DR.'sql_functions.php');

// Cookie file for cURL
$cookie=($PJSGGI_dir.$DR."..".$DR."..".$DR."nogit".$DR."fitbit_cookie");
$cookie_js=($PJSGGI_dir.$DR."..".$DR."..".$DR."nogit".$DR."fitbit_cookie_js");

// Phantom JS location
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$phantom_js=($PJSGGI_dir.$DR."phantomjs.exe");
} else {
	$phantom_js=($PJSGGI_dir.$DR."phantomjs");
}
$phantom_js_dir=($PJSGGI_dir.$DR."phantomjs_scripts");
$grab_groups_js=($phantom_js_dir.$DR."grab_group_info.js");
$log_in_js=($phantom_js_dir.$DR."log_in.js");

function get_group_page($group)
{
	return "http://www.fitbit.com/group/".$group;
}

function exec_phantom($call) 
{
	global $phantom_js,$cookie_js;
	$toCall = $phantom_js." ".escapeshellarg("--ignore-ssl-errors=yes")." ".escapeshellarg("--cookies-file=".$cookie_js)." ".$call;
	$toRet = shell_exec($toCall);
	if ($toRet === NULL)
		exit(1);
	else
		return $toRet;
}

function fitbit_login($username, $password)
{
	global $log_in_js;
	$extras = exec_phantom($log_in_js." ".escapeshellarg($username)." ".escapeshellarg($password));
	
	if (!empty($extras))
	{
		echo($extras."\n");
		exit(1);
	}
}

function get_number($text) {
	$stripchars = array(",");
	$pattern = '/[\d,.]+/i';
	$toRet = "0";
	preg_match($pattern, $text, $matches);
	if (sizeof($matches) > 0)
	{
		$toRet = trim(str_replace($stripchars, "", $matches[0]));
		if (empty($toRet)) {
			$toRet = "0";
		}
	}

	return $toRet;
}

function fitbit_getGroupsPageExtras($group)
{
	global $grab_groups_js;
	echo date('m/d/Y H:i:s')." Extras: ".$group."\n";
	
	$json = exec_phantom($grab_groups_js." ".$group);
	$json = json_decode($json, true);

	if (!empty($json['error']))
	{
		echo 'ERROR: '.$json['error'];
		exit(1);
	}
	$extras = array();
	$extras['pagetitle'] = $json['pagetitle'];
	$extras['title'] = $json['title'];
	$extras['description'] = $json['description'];
	$extras['members'] = get_number($json['numMembers']);
	$extras['steps'] = get_number($json['numSteps']);
	$extras['distance'] = get_number($json['numMiles']);
	$extras['veryactive'] = get_number($json['numVeryActiveMinutes']);
	$extras['activepoints'] = "0"; // Why u take this away fitbit!?
	$extras['daysremaining'] = get_number($json['numDaysRemaining']);
	return $extras;
}

function stripSpacesOut($text)
{
	$text = trim($text);
	$text = preg_replace('/\s{2,}/',' ',$text);
	return $text;
}
	
	//fitbit_login($username,$password);
	
	$query =  "SELECT * FROM groupinfoqueue";
	$result = mysql_query($query, $st_sql);

	while ($row = mysql_fetch_assoc($result)) {
		if (!empty($row['groupid']))
		{
			$extras = fitbit_getGroupsPageExtras($row['groupid']);
			
			$infoQuery =  "UPDATE groups SET steps='".$extras['steps']."', ";
			$infoQuery .= " activepoints='".$extras['activepoints']."', ";
			$infoQuery .= " distance='".$extras['distance']."', ";
			$infoQuery .= " veryactive='".$extras['veryactive']."' ";
			$infoQuery .= " WHERE groupid='".$row['groupid']."'";
			
			echo 'Updating...';
			$infoResult = mysql_query($infoQuery, $st_sql);
			
			//===============================================
			// NEW TABLE UPDATE
			$groupMetaQuery  = "INSERT INTO `groupsmetafitbit` ";
			$groupMetaQuery .= "(`id`, `added`, `members`, `stat30steps`, `stat30activepoints`, `stat30distance`, `stat30veryactive`, `activity`) ";
			$groupMetaQuery .= "VALUES ('".$row['groupid']."', ";
			$groupMetaQuery .= "NOW(), '-1', ";
			$groupMetaQuery .= "'".$extras['steps']."', '".$extras['activepoints']."', '".$extras['distance']."', '".$extras['veryactive']."', '0') ";
			$infoResult2 = mysql_query($groupMetaQuery, $st_sql);
			//===============================================
			
			
			unset($extras);
			
			// Did we error?
			if (!$infoResult || !$infoResult2)
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
	
	$addQuery =  "INSERT IGNORE INTO groupinfoqueue ";
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
	unset($cookie);
	unset($PJSGGI_dir);
?>