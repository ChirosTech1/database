<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'matreq';
$primaryfield = 'no';
$secondaryfield = 'hpn';
$nos = "";
//Define other variables
$q = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
$menu = mysql_real_escape_string($_POST['menu']);
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
//Get Field names from Table
$_POST['date'] = date("Y-m-d",strtotime($_POST['date']));
$dateformat = ", DATE_FORMAT(date, '%m/%d/%Y') AS date";
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
	if($sqlescape)
	$updatequery .= "$field[$i] = '$sqlescape', ";
}
$updatequery .= "id = '$id'";
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
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "price":
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "New Requisition":
		//Todays Date
		$date = date("Y-m-d");
		//Get New Req number
		$new = mysql_fetch_array(mysql_query("SELECT MAX(no) AS no FROM matreq"));
		$new = $new['no'] + 1;
		//P/N info sql
		$psql = "SELECT * FROM material WHERE no = '$linkvalue'";
		$presult = mysql_query($psql);
		$prow = mysql_fetch_array($presult);
		//Create New VAlues Variable
		$newvalues = "'$new','$date','$linkvalue','".$prow['pn']."','".$prow['des']."'";
		//Creat Insert Variable
		$newfields = "$primaryfield, date, hpn, manpn, des";
		mysql_query("INSERT INTO $table ($newfields) VALUES ($newvalues)");
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$new'";
		break;
	case "Delete Requisition":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$limit = $limit - 1;
		$sql = "SELECT * $dateformat FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "menu":
		//Add a count row to query to change the limit
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
	${$table}['testvalue'] = $new;
	//place array into variable for JSON Encoding
	$tables[] = $$table;
}
// output this after json encoding
header("Content-type: text/plain");
print(json_encode($tables));
mysql_close($con);
?>
