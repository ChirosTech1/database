<html>
<head>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'poline';
$primaryfield = 'line';
$secondaryfield = 'id';
$ptable = 'po';
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
		$sql = "SELECT poline.no, po.supplier, DATE_FORMAT(po.date, '%m/%d/%Y') AS date, poline.line, poline.qty, poline.unit, poline.pn, poline.des FROM $table INNER JOIN $ptable ON poline.no = po.no WHERE mn = '$linkvalue' ORDER BY $pprimaryfield DESC";
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
	echo "No History to Display";

?>
	<form>
	<table>
	<tr>
	<th>PO Number</th>
	<th>Supplier</th>
	<th>Date</th>
	<th>Qty</th>
	<th>Units</th>
	<th>PN/Description</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="no" size="10" value="<?php echo $row['no'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="supplier" size="30" value="<?php echo $row['supplier'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="date" size="10" value="<?php echo $row['date'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="4" value="<?php echo $row['qty'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="4" value="<?php echo $row['unit'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="70" value="<?php echo $row['pn']. " " . $row['des'];?>"/></td>
	</tr>
	</form>
<?php
}
mysql_close($con);
?>
</body>
</html>
