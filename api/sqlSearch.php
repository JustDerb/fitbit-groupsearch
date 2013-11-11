<?php

function search_basic($search)
{
	$query = " FROM groups\n"
		    . "WHERE (\n"
		    . "name LIKE '%".$search."%'\n"
		    . "OR description LIKE '%".$search."%'\n"
		    . ")\n"
		    . "ORDER BY CASE WHEN name LIKE '%".$search."%'\n"
		    . "THEN 1 \n"
		    . "ELSE 2 \n"
		    . "END \n";
	return $query;
}

function search_keywordMatch($search, $sorting = "")
{
	//Get rid of punctuation
	$punctuation = array(",", "!", ".");
	$search = str_replace($punctuation, "", $search);
	
	//Trim whitespace at beginning and end
	$search = trim($search);

	// Only a single space between arguments
	$search = preg_replace('/\s+/', ' ',$search);

	//Keyword by spaces
	$searches = explode(" ", $search);
	
	// Searching name and description
	$weight = count($searches)*2;
	
	//Build query
	$query = ", max(weight) from ( \n";
	// Exact match
	$query .= "select id, name, description, members, url, groupid, steps, activepoints, distance, veryactive, ".($weight+4)." weight \n";
	$query .= "from groups \n";
	$query .= "    where name='".$search."' \n";
	$query .= "union \n";
	$query .= "select id, name, description, members, url, groupid, steps, activepoints, distance, veryactive, ".($weight+3)." weight \n";
	$query .= "from groups \n";
	$query .= "    where description='".$search."' \n";
	$query .= "union \n";
	//Partial search
	$query .= "select id, name, description, members, url, groupid, steps, activepoints, distance, veryactive, ".($weight+2)." weight \n";
	$query .= "from groups \n";
	$query .= "    where name like '%".$search."%' \n";
	$query .= "union \n";
	$query .= "select id, name, description, members, url, groupid, steps, activepoints, distance, veryactive, ".($weight+1)." weight \n";
	$query .= "from groups \n";
	$query .= "    where description like '%".$search."%' \n";
	$query .= "union \n";

	$first = true;
	foreach($searches as $keyword)
	{
		// Keyword match name
		if (!$first)
			$query .= "union \n";
		else
			$first = false;
						
		$query .= "select id, name, description, members, url, groupid, steps, activepoints, distance, veryactive, ".($weight)." weight \n";
		$query .= "from groups \n";
		$query .= "    where name like '%".$keyword."%' \n";

		// Keyword match description
		$query .= "union \n";
		$query .= "select id, name, description, members, url, groupid, steps, activepoints, distance, veryactive, ".($weight-1)." weight \n";
		$query .= "from groups \n";
		$query .= "    where description like '%".$keyword."%' \n";
		
		$weight -= 2;
	}
	
	$query .= ") as t \n";
	$query .= "group by \n";
	$query .= "    id, url \n";
	$query .= "ORDER BY ";
	$query .= $sorting;
	$query .= " max( weight ) DESC , id ASC \n";
	
	return $query;

}

?>