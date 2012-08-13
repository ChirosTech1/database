<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'jsi';
$primaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
$menu = mysql_real_escape_string($_POST['menu']);
echo $menu;
//Get Field names from Table
$fieldsql = "SELECT * FROM $table LIMIT 1";
$fieldresult = mysql_query($fieldsql);
$num = mysql_num_fields($fieldresult);
$field = array();
for($i = 0; $i < $num; ++$i)
{
	$fields = mysql_fetch_field($fieldresult, $i);
	$field[$i] = $fields->name;
}
//Define Variables based on SQL Field names
for($i = 0; $i < $num; ++$i)
{
	$$field[$i] = mysql_real_escape_string($_POST[$field[$i]]);
}
//Check which action to update and execute SQL
switch ($q)
{
	case "Next":
		//Go to next record in set
		$limit = $limit + 1;
		$sql = "SELECT * FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "load":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * FROM $table  WHERE id = '1' ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "update":
		//Update Query Variable
		for($i = 0; $i < $num - 1; ++$i)
		{
			$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
			if($sqlescape)
			$updatequery .= "$field[$i] = '$sqlescape', ";
		}
		$updatequery .= "id = '$id'";
		//Update all fields using updatequery variable
		mysql_query("UPDATE $table SET $updatequery WHERE id = '$id'");
		$sql = "SELECT * FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "New JSI":
		//Error Checking to see if pn exists already
		$sql = "SELECT * FROM $table WHERE $primaryfield = '$menu'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		//Add New Row if not already in system
		if(!$row)
		mysql_query("INSERT INTO $table ($primaryfield) VALUES ('$menu')");
		//Display new data
		$sql = "SELECT * FROM $table WHERE $primaryfield = '$menu'";
		//Create Limit Variable for New Enry
		break;
	case "Delete JSI":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "menu":
		//Add a count row to query to change the limit
		$sql = "SELECT * FROM (SELECT @row := @row + 1 AS row, t.* FROM $table t, (SELECT @row := 0) AS r ORDER BY $primaryfield)AS h WHERE id = '$menu'";
		//reset the limit for next action
		$limitresult = mysql_query($sql);
		$limitrow = mysql_fetch_array($limitresult);
		//retrieve limit from count row
		$limit = $limitrow['row'] - 1;
		break;

}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
{
	$$table['none'] = "No results found";
}
$num = mysql_num_fields($result);
while ($row = mysql_fetch_array($result))
{
	//define array to store JSON data
	$$table = array();
	//Populate array with sql info based on column names
	for($i = 0; $i < $num; $i++)
	{
		${$table}[$field[$i]] = $row[$field[$i]];
	}
	//define other variables
	${$table}['co'] = $row['co'];
	${$table}['limit'] = $limit;
	${$table}['supplierno'] = $supplierno;
	${$table}['sql'] = $sql;
	//place array into variable for JSON Encoding
	$tables[] = $$table;
}
// output this after json encoding
header("Content-type: text/plain");
print(json_encode($tables));
mysql_close($con);
?>
