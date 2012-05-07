<html>
<head>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'woline';
$primaryfield = 'line';
$secondaryfield = 'id';
$ptable = 'wo';
$pprimaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
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
//New Query Variable
for($i = 0; $i < $num; ++$i)
{
	$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
	if($sqlescape)
	{
		$newquery .= "$field[$i],";
		$newvalues .= "'$sqlescape',";
	}
}
$newquery .= "no";
$newvalues .= "'$linkvalue'";
//Check which action to update and execute SQL
switch ($q)
{
	case "Next":
	case "Prev":
	case "load":
	case "menu":
	case "Delete PO":
	case "New PO":
		//Initial load of Form
		$sql = "SELECT woline.no, wo.cust, wo.po, DATE_FORMAT(wo.date, '%m/%d/%Y') AS date, woline.qty, woline.rev FROM $table INNER JOIN $ptable ON woline.no = wo.no WHERE woline.pn = '$linkvalue' ORDER BY $pprimaryfield DESC";
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
	echo "No History to Display";

?>
	<form id="lineitems">
	<table>
	<tr>
	<th>WO</th>
	<th>Customer</th>
	<th>Date</th>
	<th>Qty</th>
	<th>Rev</th>
	<th>PO Number</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="no" size="10" value="<?php echo $row['no'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="cust" size="20" value="<?php echo $row['cust'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="date" size="10" value="<?php echo $row['date'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="4" value="<?php echo $row['qty'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rev" size="4" value="<?php echo $row['rev'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="po" size="30" value="<?php echo $row['po'];?>"/></td>
	</tr>
	</form>
<?php
}
mysql_close($con);
?>
</body>
</html>
