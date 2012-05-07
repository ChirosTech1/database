<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'contact';
$primaryfield = 'company';
$secondaryfield = 'id';
//Define other variables
$direction = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
if($_POST['live'])
$menu = mysql_real_escape_string($_POST['live']);
else
$menu = mysql_real_escape_string($_POST['menu']);
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
//Update Query Variable
for($i = 0; $i < $num - 1; ++$i)
{
$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
$updatequery .= "$field[$i] = '$sqlescape', ";
}
//Check which action to update and execute SQL
switch ($direction)
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
	case "cload":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "update":
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET $updatequery id = '$id' WHERE id = '$id'");
		$sql = "SELECT * FROM $table WHERE id='$id'";
		break;
	case "New":
		//Insert new record "new" and display
		mysql_query("INSERT INTO $table ($primaryfield) VALUES ('new')");
		$sql = "SELECT * FROM $table WHERE id=(SELECT MAX(id) FROM $table)";
		break;
	case "Delete":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "menu":
		//Add a count row to query to change the limit
		$sql = "SELECT * FROM (SELECT @row := @row + 1 AS row, t.* 
		FROM $table t, (SELECT @row := 0) AS r ORDER BY $primaryfield) h 
		WHERE $primaryfield = '$menu'";
		//reset the limit for next action
		$limitresult = mysql_query($sql);
		$limitrow = mysql_fetch_assoc($limitresult);
		//retrieve limit from count row
		$limit = $limitrow['row'] - 1;
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
{
	$$table["company"] = "No results found";
}
while ($row = mysql_fetch_array($result))
{
	//define array to store JSON data
	$$table = array();
	//Populate array with sql info based on column names
	for($i = 0; $i < $num; ++$i)
	{
		${$table}[$field[$i]] = $row[$field[$i]];
	}
	//define other variables
	${$table}['limit'] = $limit;
	${$table}['var'] = $updatequery;
	//place array into variable for JSON Encoding
	$tables[] = $$table;
}
// output this after json encoding
header("Content-type: text/plain");
print(json_encode($tables));
mysql_close($con);
?>
