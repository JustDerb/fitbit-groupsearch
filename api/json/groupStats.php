<?php 
require_once('../group.php');

// We will always spit out JSON
header('Content-Type: application/javascript');

$jsonResult = array();

if (isset($_GET['g']))// && 
    //isset($_GET['type']))
{
	// Grab our group info
	$group = NULL;
	try
	{
		$group = new FitbitGroup($_GET['g']);
		$valueName = "Unkown";
		$result = array();

		switch($_GET['type'])
		{
			case 'steps':
				$result = $group->getRangeSteps(30);
				$valueName = "Steps";
			break;
			case 'activepoints':
				$result = $group->getRangeActivePoints(30);
				$valueName = "Active Points";
			break;
			case 'distance':
				$result = $group->getRangeDistance(30);
				$valueName = "Distance";
			break;
			case 'veryactive':
				$result = $group->getRangeVeryActive(30);
				$valueName = "Very Active";
			break;
		}

		$jsonResult = array(
			'cols' => array(
				array(
					'label' => 'Date',
					'type' => 'string'
				),
				array(
					'label' => $valueName,
					'type' => 'number'
				)),
			'rows' => array()
			);

		foreach ($result as $key => $value) {
			$values = array();
			$dateTime = strtotime($value[0]);
			$values[] = array('v' => $dateTime, 'f' => date("m/d/y", $dateTime));
			$values[] = array('v' => $value[1]);
			$jsonResult['rows'][] = array('c' => $values);
		}
	}
	catch (NoGroupFound $ex)
	{
		// Don't echo anything (we should really echo an error message)
	}
}

echo(json_encode($jsonResult));

?>