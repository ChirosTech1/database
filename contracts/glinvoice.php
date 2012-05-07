<html>
<head>
<script type="text/javascript" src="../script/ajax.js"/></script>
<script type="text/javascript" src="../script/certpo.js"/></script>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'invline';
$primaryfield = 'line';
$secondaryfield = 'id';
$ptable = 'woline';
$pprimaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
$callvalue = mysql_real_escape_string($_POST['callvalue']);
$linetaxable = mysql_real_escape_string($_POST['linetaxable']);
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
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' 
		ORDER BY $primaryfield";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue'
		ORDER BY $primaryfield";
		break;
	case "New":
		//Calculate WO Due qtys
		$woline = mysql_fetch_array(mysql_query("SELECT *, SUM(due - $qty) AS due FROM woline WHERE no = '$wo' AND line = '$line'"));
		$id = $woline['id'];
		$due = $woline['due'];
		//Update WO qtys
		mysql_query("UPDATE $ptable SET due = '$due' WHERE id = '$id'");
		//Calculate Line Price
		$lp = $qty * $woline['up'];
		$newquery .= "no, wo, line, qty, due, pn, rev, up, lp, type";
		$newvalues .= "'$linkvalue','".$woline['no']."','".$woline['line']."','$qty','$due','".$woline['pn']."','".$woline['rev']."','".$woline['up']."','$lp','".$woline['type']."'";
		//Insert new record "new" and display
		mysql_query("INSERT INTO $table ($newquery) VALUES ($newvalues)");
	case "update":
		if($q == 'update')
		{
		//Calculate Line price
		$_POST['lp'] = $qty * $up;
		//Update Query Variable
		for($i = 0; $i < $num; ++$i)
		{
			$f = $field[$i];
			$sqlescape = mysql_real_escape_string($_POST[$f]);
			if($sqlescape)
			{
				if($f != 'id')
				$updatequery .= "$f='$sqlescape', ";
			}
		}
		$updatequery .= "id = '$id'";
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
		}
	case "Delete":
		if($q == 'Delete')
		{
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		}
		//Update Due Qty
		$wolineqty = mysql_fetch_array(mysql_query("SELECT qty FROM woline WHERE no='$wo' AND line='$line'"));
		$wolineqty = $wolineqty['qty'];
		$invlineqty = mysql_fetch_array(mysql_query("SELECT SUM(qty) AS qty FROM invline WHERE wo='$wo' AND line='$line'"));
		$invlineqty = $invlineqty['qty'];
		$due = $wolineqty - $invlineqty;
		mysql_query("UPDATE $table SET due = '$due' WHERE id = '$id'");
		mysql_query("UPDATE $ptable SET due = '$due' WHERE no='$wo' AND line='$line'");
		//Update po table to reflect totals
		mysql_query("UPDATE inv SET tot = (SELECT SUM(lp) FROM invline WHERE no = '$linkvalue') + shpchrg WHERE no = '$linkvalue'");
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "load":
	case "menu":
	case "Delete PO":
	case "New PO":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
}
//create query
$result = mysql_query($sql);
?>	
<?php
if(mysql_num_rows($result)==0) 
	echo "No Line Items Entered";

?>
	<form id="lineitems">
	<table>
	<tr>
	<th>Line</th>
	<th>Qty</th>
	<th>Due</th>
	<th>Part Number/Description</th>
	<th>Unit Price</th>
	<th>Line Price</th>
	</tr>

<?php
for ($i = 0;$row = mysql_fetch_array($result);$i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<input type="hidden" id="<?php echo $i;?>" name="wo" value="<?php echo $row['wo'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $row['line'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="3" value="<?php echo $row['qty'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="due" size="2" value="<?php echo $row['due'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="50" value="<?php echo $row['pn'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10" value="<?php echo $row['up'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="lp" size="10" value="<?php echo $row['lp'];?>"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete" onclick="UpdateLine(this.value,this)"/></td>
	</tr>
<?php
}
	//Get WO
	$wo = mysql_fetch_array(mysql_query("SELECT wo FROM inv WHERE no = '$linkvalue'"));
	$wo = $wo['wo'];
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="wo" value="<?php echo $wo;?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="3" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="due" size="2"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="50"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="lp" size="10"/></td>
	</tr>
	</table>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
