<?php 
require_once('../group.php');

// We will always spit out JSON
header('Content-Type: application/javascript');

$jsonResult = array();

if (isset($_GET['g']) && 
    isset($_GET['type']))
{
	// Grab our group info
	$group = NULL;
	try
	{
		$group = new FitbitGroup($_GET['g']);
	}
	catch (NoGroupFound $ex)
	{
	}
	
	
	if (isset($group))
	{
		// Found our group, grab our data
		switch($_GET['type'])
		{
			case 'steps':
				$jsonResult = $group->getRangeSteps();
			break;
			case 'activepoints':
				$jsonResult = $group->getRangeActivePoints();
			break;
			case 'distance':
				$jsonResult = $group->getRangeDistance();
			break;
			case 'veryactive':
				$jsonResult = $group->getRangeVeryActive();
			break;
		}
	}
}

echo(json_encode($jsonResult));

?>