<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'wo';
$table2 = 'customer';
$primaryfield = 'no';
$secondaryfield = 'custid';
//Define other variables
$q = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
$menu = mysql_real_escape_string($_POST['menu']);
$supplierno = mysql_real_escape_string($_POST['supplierno']);
//Convert Dates to SQL Readable
$_POST['date'] = date("Y-m-d",strtotime($_POST['date']));
$_POST['indate'] = date("Y-m-d",strtotime($_POST['indate']));
$_POST['cdate'] = date("Y-m-d",strtotime($_POST['cdate']));
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
		if ($supplierno)
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id WHERE custid = '$supplierno' ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		if ($supplierno)
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id WHERE custid = '$supplierno' ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "load":
		//Initial load of Form
		$limit = 0;
		if($supplierno)
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id WHERE custid = '$supplierno' ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id ORDER BY $primaryfield LIMIT $limit, 1";
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
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id WHERE $table.$primaryfield = '$no'";
		break;
	case "price":
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "New Invoice":
		$sql = "SELECT * $dateformat FROM $table INNER JOIN $table2 ON $table.custno = $table2.id WHERE $table.no='$new'";
		break;
	case "New WO":
		//Get New Number
		$new = mysql_fetch_array(mysql_query("SELECT MAX(no) + 1 AS no FROM wo"));
		$new = $new['no'];
		//Todays Date
		$date = date("Y-m-d");
		//Creat Insert Variable
		$newfields = "no, custid, date";
		//Create New VAlues Variable
		$newvalues = "'$new','$supplierno','$date'";
		mysql_query("INSERT INTO $table ($newfields) VALUES ($newvalues)");
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table INNER JOIN $table2 ON $table.custid = $table2.id WHERE $table.no='$new'";
		break;
	case "Delete WO":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		mysql_query("DELETE FROM poline WHERE no = '$no'");
		$limit = $limit - 1;
		if($supplierno)
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table WHERE custid = $supplierno ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Close":
		//Update all fields using updatequery variable
		$cdate = date("Y-m-d");
		mysql_query("UPDATE  $table SET cdate = '$cdate' WHERE id = '$id'");
		$sql = "SELECT $table.* $dateformat, $table2.co FROM $table WHERE $primaryfield = '$no'";
		break;
	case "menu":
		//Add a count row to query to change the limit
		if($supplierno)
		$sql = "SELECT *$dateformat FROM $table2 INNER JOIN (SELECT @row := @row + 1 AS row, $table.* FROM (SELECT @row := 0) AS r, $table WHERE $table.custid = '$supplierno') AS $table ON $table2.id = $table.custid WHERE $table.custid = '$supplierno' AND $table.id = '$menu'";
		else
		$sql = "SELECT *$dateformat FROM $table2 INNER JOIN (SELECT @row := @row + 1 AS row, $table.* FROM (SELECT @row := 0) AS r, $table) AS $table ON $table2.id = $table.custid WHERE $table.id = '$menu'";
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
