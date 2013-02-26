<?php
require_once('sql_functions.php');
require_once('feedbackVote.php');

$status = "";
$error = "";

if (isset($_POST['feedback']))
{
	$charMin = 10;
	
	if (strlen($_POST['feedback']) > $charMin)
	{
		// Default to remark
		$type = "Remark";
		if (isset($_POST['feedbackType']))
		{
			if (strcasecmp($_POST['feedbackType'], "Idea") == 0)
			{
				$type = "Idea";
			}
			else if (strcasecmp($_POST['feedbackType'], "Remark") == 0)
			{
				$type = "Remark";
			}
			else if (strcasecmp($_POST['feedbackType'], "Problem") == 0)
			{
				$type = "Problem";
			}
		}
		
		$private = 0;
		if (isset($_POST['private']))
		{
			if (strcasecmp($_POST['private'], "true") == 0)
			{
				$private = 1;
			}
		}
		
		$email = "";
		if (isset($_POST['email'])) {
			$email = st_mysql_encode($_POST['email'], $st_sql);
		}
		
		$title = st_mysql_encode($_POST['feedback'], $st_sql);
		
		$query = "INSERT INTO feedback (`id`, `title`, `type`, `private`, `added`, `email`) VALUES (NULL, '$title', '$type', '$private', NOW(), '$email');";
		$result = mysql_query($query, $st_sql);
		
		if ($result) {
			if ($private)
			{
				$status = "Your feedback has been sent to the admin.";
			}
			else
			{
				$status = "Your feedback has been added.";
			}
		}
		else
		{
			$error = "There was an error adding your feedback.";
		}
	}
	else
	{
		$error = "You must enter more than $charMin characters to submit feedback.";
	}
}

$queryString = "?status=".urlencode($status)."&error=".urlencode($error);
header('location:../feedback.php'.htmlentities($queryString));
exit();
?>