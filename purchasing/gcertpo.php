<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'po';
$primaryfield = 'no';
$secondaryfield = 'supplierid';
//Define other variables
$q = $_POST['q'];
$limit = mysql_real_escape_string($_POST['limit']);
$menu = mysql_real_escape_string($_POST['menu']);
$supplierno = mysql_real_escape_string($_POST['supplierno']);
$qcapprove = mysql_real_escape_string($_POST['qcapprove']);
if($_POST['qcid'])
$qcid = mysql_real_escape_string($_POST['qcid']);
//Convert Dates to SQL Readable
$_POST['date'] = date("Y-m-d",strtotime($_POST['date']));
if($_POST['inhousedate'])
$_POST['inhousedate'] = date("Y-m-d",strtotime($_POST['inhousedate']));
if($_POST['cdate'])
$_POST['cdate'] = date("Y-m-d",strtotime($_POST['cdate']));
//Convert SQL dates to Human readable
$dateformat = ", DATE_FORMAT(date, '%m/%d/%Y') AS date, DATE_FORMAT(inhousedate, '%m/%d/%Y') AS inhousedate, DATE_FORMAT(cdate, '%m/%d/%Y') AS cdate";
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
		if ($supplierno)
		$sql = "SELECT * $dateformat FROM $table WHERE supplierid = '$supplierno' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		else if ($qcapprove)
		$sql = "SELECT * $dateformat FROM $table WHERE ordered = '' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table WHERE no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		if ($supplierno)
		$sql = "SELECT * $dateformat FROM $table WHERE supplierid = '$supplierno' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		else if ($qcapprove)
		$sql = "SELECT * $dateformat FROM $table WHERE ordered = '' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table WHERE no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "load":
		//Initial load of Form
		$limit = 0;
		if($supplierno)
		$sql = "SELECT * $dateformat FROM $table WHERE supplierid = '$supplierno' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		else if ($qcapprove)
		$sql = "SELECT * $dateformat FROM $table WHERE ordered = '' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table WHERE no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Approve":
		mysql_query("UPDATE po SET ordered = '$qcid' WHERE no='$no'");
		$sql = "SELECT * $dateformat FROM $table WHERE ordered = '' AND no > '50000' AND no < '90000' ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "update":
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "price":
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "New PO":
		//Find New PO number
		$newsql = "SELECT MAX(no) AS no FROM $table WHERE no > '50000' AND no < '90000'";
		$newresult = mysql_query($newsql);
		$newrow = mysql_fetch_assoc($newresult);
		$new = $newrow['no'] + 1;
		//Get Supplier Information
		$supsql = "SELECT * FROM contact WHERE id = '$supplierno'";
		$supresult = mysql_query($supsql);
		$sup = mysql_fetch_assoc($supresult);
		$cnum = mysql_num_fields($supresult);
		$cfield = array();
		$sfield = array();
		for($i = 0; $i < $cnum; ++$i)
		{
			$cfields = mysql_fetch_field($supresult, $i);
			$cfield[$i] = $cfields->name;
			$sfield[$i] = $cfields->name;
		}
		for($i = 0; $i < $cnum; ++$i)
		{
			$$cfield[$i] = $sup[$cfield[$i]];
		}
		//Todays Date
		$date = date("Y-m-d");
		//FOB variable
		$fob = $city . ", " . $state;
		//Create New VAlues Variable
		$newvalues = "'$new','$supplierno','$company','$date','$address','$city','$state','$zip','$fob','$phone','$fax',";
		//Get ShipTo Infomration
		$shipsql = "SELECT * FROM contact WHERE id = '1'";
		$shipresult = mysql_query($shipsql);
		$ship = mysql_fetch_assoc($shipresult);
		for($i = 0; $i < $cnum; ++$i)
		{
			$$sfield[$i] = $ship[$sfield[$i]];
		}	
		//Continue the Values Variable for Shipping info
		$newvalues .= "'$company','$address','$city','$state','$zip'";
		//Creat Insert Variable
		$newfields = "$primaryfield,supplierid,supplier,date,address,city,state,zip,fob,phone,fax,sname,saddress,scity,sstate,szip";
		mysql_query("INSERT INTO $table ($newfields) VALUES ($newvalues)");
		//Insert Default Note
		mysql_query("INSERT INTO ponote (no, note) VALUES ('$new','For Resale CA Permit # SR-AC-13-081200')");
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$new'";
		break;
	case "Delete PO":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		mysql_query("DELETE FROM poline WHERE no = '$no'");
		mysql_query("DELETE FROM ponote WHERE no = '$no'");
		$limit = $limit - 1;
		if($supplierno)
		$sql = "SELECT * $dateformat FROM $table WHERE supplierid = $supplierno ORDER BY $primaryfield LIMIT $limit, 1";
		else
		$sql = "SELECT * $dateformat FROM $table ORDER BY $primaryfield LIMIT $limit, 1";
		break;
	case "Close":
		//Update all fields using updatequery variable
		$cdate = date("Y-m-d");
		mysql_query("UPDATE  $table SET cdate = '$cdate' WHERE id = '$id'");
		$sql = "SELECT * $dateformat FROM $table WHERE $primaryfield = '$no'";
		break;
	case "menu":
		//Add a count row to query to change the limit
		if($supplierno)
		$sql = "SELECT * $dateformat FROM (SELECT @row := @row + 1 AS row, t.* FROM $table t, (SELECT @row := 0) AS r WHERE supplierid = '$supplierno' AND no > '50000' AND no < '90000' ORDER BY $primaryfield)AS h WHERE id = '$menu'";
		else
		$sql = "SELECT * $dateformat FROM (SELECT @row := @row + 1 AS row, t.* FROM $table t, (SELECT @row := 0) AS r WHERE no > '50000' AND no < '90000' ORDER BY $primaryfield)AS h WHERE id = '$menu'";
		//reset the limit for next action
		$limitresult = mysql_query($sql);
		$limitrow = mysql_fetch_row($limitresult);
		//retrieve limit from count row
		$limit = $limitrow[0] - 1;
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
