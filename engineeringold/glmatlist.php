<html>
<head>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'matlist';
$primaryfield = 'mat';
$secondaryfield = 'id';
$ptable = 'material';
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
	case "New Part Number":
		mysql_query("INSERT INTO $table (no,mat,qty,unit,ppp,des) VALUES ('$linkvalue','$mat','$qty','$unit','$ppp','$des')");
		$sql = "SELECT $table.*, $ptable.pn AS pnpn, $ptable.des AS pndes FROM $table INNER JOIN $ptable ON $table.mat = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY mat";
		break;
	case "Delete Part Number":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT $table.*, $ptable.pn AS pnpn, $ptable.des AS pndes FROM $table INNER JOIN $ptable ON $table.mat = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY mat";
		break;

	case "Next":
	case "Prev":
	case "load":
	case "menu":
		//Initial load of Form
		$sql = "SELECT $table.*, $ptable.pn AS pnpn, $ptable.des AS pndes FROM $table INNER JOIN $ptable ON $table.mat = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY mat";
		break;
}
//create query
$result = mysql_query($sql);
//Define $i for New insert Line
$i = mysql_num_rows($result) + 1;
?>
	<form id="lineitems">
	<table>
	<tr>
	<th>Material</th>
	<th>Qty</th>
	<th>Units</th>
	<th>PPP</th>
	<th>Description/Panel Size</th>
	</tr>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="mat" size="10"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="30"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="5"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="ppp" size="5"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="des" size="30"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="new" value="New Part Number" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	<tr><th colspan="2"><b>PN Materials</b></th></tr>
	<tr><th height="16"></th></tr>
	<tr>
	<th>Material</th>
	<th>Description</th>
	<th>Qty</th>
	<th>Unit</th>
	<th>PPP</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="mat" size="10" value="<?php echo $row['mat'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pndes" size="30" value="<?php echo $row['pnpn'] . " " . $row['pndes'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="5" value="<?php echo $row['qty'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="5" value="<?php echo $row['unit'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="Per Panel" size="30" value="<?php if($row['ppp']){echo $row['ppp'] . " per " . $row['des'];} else echo $row['des'];?>"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete Part Number" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
<?php
}
?>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
