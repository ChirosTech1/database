<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'inv';
$table2 = 'customer';
$primaryfield = 'no';
$secondaryfield = 'wo';
//Define other variables
$q = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
$menu = mysql_real_escape_string($_POST['menu']);
if($_POST['linkvalue'])
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
//Convert Dates to SQL Readable
$_POST['date'] = date("Y-m-d",strtotime($_POST['date']));
//Convert SQL dates to Human readable
$dateformat = ", DATE_FORMAT(date, '%m/%d/%Y') AS date";
//Get Field names from Table
$fieldsql = "SELECT * FROM $table, $table2 LIMIT 1";
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
		if ($linkvalue)
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.wo='$wo' ORDER BY $table.$primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno ORDER BY $table.$primaryfield LIMIT $limit, 1";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		if ($linkvalue)
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.wo='$wo' ORDER BY $table.$primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno ORDER BY $table.$primaryfield LIMIT $limit, 1";
		break;
	case "load":
		//Initial load of Form
		$limit = 0;
		if($linkvalue)
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.wo='$wo' ORDER BY $table.$primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno ORDER BY $table.$primaryfield LIMIT $limit, 1";
		break;
	case "price":
		//Initial load of Form
		if($linkvalue)
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.wo='$wo' AND $table.no = '$no'";
		else
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.no = '$no'";
		break;
	case "Approve":
		mysql_query("UPDATE po SET ordered = '$qcid' WHERE no='$no'");
		$sql = "SELECT * $dateformat FROM $table WHERE ordered = '' AND no > '50000' AND no < '90000' ORDER BY $table.$primaryfield LIMIT $limit, 1";
		break;
	case "update":
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET date='$date' ,wo='$wo',po='$po',shp='$shp',nopgs='$nopgs',shpchrg='$shpchrg',terms='$terms' WHERE no = '$no'");
		if($linkvalue)
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.wo='$wo' ORDER BY $table.$primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.id = '$id'";
		break;
	case "New Invoice":
		//Get New Number
		$new = mysql_fetch_array(mysql_query("SELECT MAX(no) + 1 AS no FROM inv"));
		$new = $new['no'];
		//Get WO Info
		$woinfo = mysql_fetch_array(mysql_query("SELECT * FROM wo WHERE no = '$wo'"));
		//Todays Date
		$date = date("Y-m-d");
		//Creat Insert Variable
		$newfields = "no, date, wo, po, shp, custno";
		//Create New VAlues Variable
		$newvalues = "'$new','$date','$wo','".$woinfo['po']."','".$woinfo['shp']."','".$woinfo['custid']."'";
		//Insert New Values
		mysql_query("INSERT INTO $table ($newfields) VALUES ($newvalues)");
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.no='$new'";
		break;
	case "Delete Invoice":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		mysql_query("DELETE FROM invline WHERE no = '$no'");
		$limit = $limit - 1;
		if($supplierno)
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno WHERE $table.wo='$wo' ORDER BY $table.$primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table2 INNER JOIN $table ON $table2.id = $table.custno ORDER BY $table.$primaryfield LIMIT $limit, 1";
		break;
	case "Close":
		//Update all fields using updatequery variable
		$cdate = date("Y-m-d");
		mysql_query("UPDATE  $table SET cdate = '$cdate' WHERE id = '$id'");
		$sql = "SELECT * $dateformat FROM $table WHERE $table.$primaryfield = '$no'";
		break;
	case "menu":
		//Add a count row to query to change the limit
		if($linkvalue)
		$sql = "SELECT *$dateformat FROM $table2 INNER JOIN (SELECT @row := @row + 1 AS row, $table.* FROM (SELECT @row := 0) AS r, $table) AS $table ON $table2.id = $table.custno WHERE $table.wo = '$wo' AND $table.id = '$menu'";
		else
		$sql = "SELECT *$dateformat FROM $table2 INNER JOIN (SELECT @row := @row + 1 AS row, $table.* FROM (SELECT @row := 0) AS r, $table) AS $table ON $table2.id = $table.custno WHERE $table.id = '$menu'";


//SELECT *, DATE_FORMAT(date, '%m\/%d\/%Y') AS date FROM customer INNER JOIN (SELECT @row := @row + 1 AS row, inv.* FROM (SELECT @row := 0) AS r, inv) AS inv ON customer.id = inv.custno WHERE inv.wo = '786' AND inv.id = '301'


		//reset the limit for next action
		$limitresult = mysql_query($sql);
		$limitrow = mysql_fetch_array($limitresult);
		//retrieve limit from count row
		$limit = $limitrow['row'] - 1;
		break;
}
//create query
$result = mysql_query($sql);
if(!mysql_num_rows($result)) 
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
	${$table}['linkvalue'] = $linkvalue;
	${$table}['testvalue'] = $sql;
	//place array into variable for JSON Encoding
	$tables[] = $$table;
}
// output this after json encoding
header("Content-type: text/plain");
print(json_encode($tables));
mysql_close($con);
?>
