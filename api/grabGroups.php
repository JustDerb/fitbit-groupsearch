<?php
require_once '../nogit/fitbit_login.php';
require_once 'simple_html_dom.php';
require_once 'sql_functions.php';

// Cookie file for cURL
$dir = dirname(__FILE__);
$cookie=$dir."/../nogit/fitbit_cookie"; 

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
	
	return $result;
}

function fitbit_getGroupsPage($ch, $page)
{
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

	$result = curl_exec ($ch); 

	return $result;
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
		echo "PageNum: ".$pageNum."";
		$result = fitbit_getGroupsPage($ch, $pageNum);
		//echo($result);

		// Create DOM from URL
		$html = str_get_html($result);
		
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
		    
		    /*
		    $query =  "IF EXISTS (SELECT 1 FROM groups WHERE url='$href') \n";
			$query .= "    UPDATE groups SET name='$title', members='$members', description='$description' WHERE url='$href'; \n";
			$query .= "ELSE \n";
		    $query .= "    INSERT INTO  groups (id,name,members,description,url) VALUES (NULL ,  '$title',  '$members',  '$description',  '$href'); \n";
		    $query .= "END IF \n";
		    */
		    $query =  "INSERT INTO  groups (id,name,members,description,url) VALUES (NULL ,  '$title',  '$members',  '$description',  '$href') \n";
		    $query .= "ON DUPLICATE KEY \n";
		    $query .= "UPDATE name='$title', members='$members', description='$description'";
		    
		    $result = mysql_query($query, $st_sql);
		    // Did we error?
		    if (!$result)
		    {
		    	echo "\n".$query;
		    	echo "\n".mysql_error($st_sql);
		    	$error = true;
		    	break;
		    }		  
		}
		
		if ($error)
			break;
		
		//echo '<pre>';
		//var_dump($groups);
		//echo '</pre>';
		
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
		
		//flush();
		
		// Sleep so we don't DDOS the site
		sleep(2);
	}

	curl_close($ch);

?>