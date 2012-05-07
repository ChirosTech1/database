<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'tool';
$primaryfield = 'no';
$secondaryfield = 'type';
$nos = "";
//Define other variables
$q = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
$tooling = mysql_real_escape_string($_POST['tooling']);
$menu = mysql_real_escape_string($_POST['menu']);
//Convert Dates to SQL Readable
$_POST['date'] = date("Y-m-d",strtotime($_POST['date']));
$dateformat = ", DATE_FORMAT(date, '%m/%d/%Y') AS date";
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
		$sql = "SELECT * $dateformat FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * $dateformat FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "load":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * $dateformat FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "update":
		//Update all fields using updatequery variable
		//Update Query Variable
		for($i = 0; $i < $num - 1; ++$i)
		{
			$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
			if($sqlescape)
			$updatequery .= "$field[$i] = '$sqlescape', ";
		}
		$updatequery .= "id = '$id'";
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "New Tooling":
		$sql = "SELECT DISTINCT type FROM tool WHERE substring(no,1,2) = substring('$tooling',1,2)";
		$result = mysql_query($sql);
		$type = mysql_result($result,0);
		$isql = "INSERT INTO $table (no,type) VALUES ('$tooling','$type')";
		mysql_query($isql);
		$limit = 0;
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$tooling'";
		break;
	case "Delete Tooling":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$limit = $limit - 1;
		$sql = "SELECT * $dateformat FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "menu":
		//Add a count row to query to change the limit
		if($supplierno)
		$sql = "SELECT * $dateformat FROM (SELECT @row := @row + 1 AS row, t.* FROM $table t, (SELECT @row := 0) AS r WHERE supplierid = '$supplierno' ORDER BY $primaryfield)AS h WHERE $primaryfield = '$menu'";
		else
		$sql = "SELECT * $dateformat FROM (SELECT @row := @row + 1 AS row, t.* FROM $table t, (SELECT @row := 0) AS r ORDER BY $primaryfield)AS h WHERE $primaryfield = '$menu'";
		//reset the limit for next action
		$limitresult = mysql_query($sql);
		$limitrow = mysql_fetch_row($limitresult);
		//retrieve limit from count row
		$limit = $limitrow[0] - 1;
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
{
	$$table['none'] = "No results found";
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
	${$table}['supplierno'] = $supplierno;
	//place array into variable for JSON Encoding
	$tables[] = $$table;
}
// output this after json encoding
header("Content-type: text/plain");
print(json_encode($tables));
mysql_close($con);
?>
