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
		$type = "";
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
		
			if (!empty($type))
			{
				// Optional
				$private = 0;
				if (isset($_POST['private']))
				{
					if (strcasecmp($_POST['private'], "true") == 0)
					{
						$private = 1;
					}
				}
				
				// Optional
				$email = "";
				if (isset($_POST['email'])) {
					$email = st_mysql_encode($_POST['email'], $st_sql);
				}

				require_once('../includes/recaptchalib.php');
				require_once('../nogit/captcha.php');
				$resp = recaptcha_check_answer ($CAPTCHA_privatekey,
					$_SERVER["REMOTE_ADDR"],
					$_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]);

				if (!$resp->is_valid) {
					$error = "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
				} else {
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
			}
			else
			{
				$error = "Please select a type of feedback.";
			}
		}
		else
		{
			$error = "Please select a type of feedback.";
		}
	}
	else
	{
		$error = "You must enter more than $charMin characters to submit feedback.";
	}
}

$queryString = "?status=".urlencode($status)."&error=".urlencode($error);
// Only post back form if there are errors
if (!empty($error)) 
{
	if (isset($_POST['feedback']))
		$queryString .= "&f=".urlencode($_POST['feedback']);
	if (isset($_POST['feedbackType']))
		$queryString .= "&ft=".urlencode($_POST['feedbackType']);
	if (isset($_POST['private']))
		$queryString .= "&p=".urlencode($_POST['private']);
	if (isset($_POST['email']))
		$queryString .= "&e=".urlencode($_POST['email']);
}
header('location:../feedback.php'.$queryString);
exit();
?>