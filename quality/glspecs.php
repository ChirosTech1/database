<html>
<head>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'speclist';
$primaryfield = 'no';
$secondaryfield = 'id';
$ptable = 'pn';
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
		$sql = "SELECT $table.no, $ptable.rev, $ptable.eo FROM $table INNER JOIN $ptable ON $ptable.no = $table.no WHERE $table.spec = '$linkvalue' ORDER BY $pprimaryfield DESC";
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
	<th>Part Number</th>
	<th>Rev</th>
	<th>EO</th>
	<th>Itar</th>
	<th>Program Name</th>
	<th>Status</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="no" size="15" value="<?php echo $row['no'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rev" size="5" value="<?php echo $row['rev'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="eo" size="30" value="<?php echo $row['eo'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="itar" size="4" value="<?php echo $row['itar'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="prog" size="15" value="<?php echo $row['prog'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="status" size="10" value="<?php echo $row['status'];?>"/></td>
	</tr>
	</form>
<?php
}
mysql_close($con);
?>
</body>
</html>
