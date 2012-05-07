<html>
<head>
<script type="text/javascript" src="../script/ajax.js"/></script>
<script type="text/javascript" src="../script/longpo.js"/></script>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'poline';
$primaryfield = 'line';
$secondaryfield = 'id';
$callfield = 'callno';
$ptable = 'po';
$pprimaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
if($_POST['callvalue'])
$callvalue = mysql_real_escape_string($_POST['callvalue']);
else
$callvalue = 0;
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
$newquery .= "no,callno";
$newvalues .= "'$linkvalue','$callvalue'";
//Check which action to update and execute SQL
switch ($q)
{
	case "Next":
		//Go to next record in set
		$limit = $limit + 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' 
		ORDER BY $primaryfield";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue'
		ORDER BY $primaryfield";
		break;
	case "New":
		//Insert new record "new" and display
		mysql_query("INSERT INTO $table ($newquery) VALUES ($newvalues)");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' 
		ORDER BY $primaryfield";
		break;
	case "update":
	case "taxable":
		//See if tax check was clicked and change value
		if ($q == 'taxable')
		$taxable = !$taxable;
		//calculate tax if order is taxable
		if($taxable)
		{
		$taxsql = "SELECT rate FROM $table WHERE id = '$id'";
		$taxresult = mysql_query($taxsql);
		$taxrow = mysql_fetch_assoc($taxresult);
		$taxrate = $taxrow['rate'];
		}
		else 
		{$taxrate = 0;}
		//Calculate Line price and tax
		$_POST['lp'] = $qty * $up;
		$tax = $_POST['lp'] * $taxrate;
		//Update Query Variable
		for($i = 0; $i < $num; ++$i)
		{
			$f = $field[$i];
			$sqlescape = mysql_real_escape_string($_POST[$f]);
			if($sqlescape)
			{
				if($f == 'taxable'){}
				if($f == 'tax'){}
				else
				$updatequery .= "$f='$sqlescape', ";
			}
		}
		$updatequery .= "taxable = '$taxable',tax = '$tax'";
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
	case "Delete":
		if($q == 'Delete')
		{
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' ORDER BY $primaryfield";
		}
		//Calculate Order Totals
		$totsql = "SELECT SUM(lp) AS lp, SUM(tax) AS tax, SUM(lp+tax) AS tot FROM poline WHERE no = '$linkvalue' AND $callfield = '$callvalue'";
		$totresult = mysql_query($totsql);
		$totrow = mysql_fetch_assoc($totresult);
		$subtotal = $totrow['lp'];
		$taxtotal = $totrow['tax'];
		$total = $totrow['tot'];
		//Update po table to reflect totals
		mysql_query("UPDATE $ptable SET subtot='$subtotal', taxtot='$taxtotal', tot='$total' WHERE no='$linkvalue' AND $callfield = '$callvalue'");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' ORDER BY $primaryfield";
		break;
	case "load":
	case "menu":
	case "Delete PO":
	case "New PO":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue'
		ORDER BY $primaryfield";
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
	echo "No Line Items Entered";

?>
	<form id="lineitems">
	<table>
	<tr>
	<th>Line</th>
	<th>Qty</th>
	<th>Units</th>
	<th>Part Number</th>
	<th>Description</th>
	<th>Unit Price</th>
	<th>Line Price</th>
	<th>Taxable</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result);$i++)
{
	if($row['taxable'])
	{
		$checked = "checked = 'checked'";
		$taxable = 1;
	}
	else
	{
		$checked = null;
		$taxable = 0;
	}
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<input type="hidden" id="<?php echo $i;?>" name="tax" value="<?php echo $row['tax'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $row['line'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="3" value="<?php echo $row['qty'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="2" value="<?php echo $row['unit'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="20" value="<?php echo $row['pn'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="des" size="75" value="<?php echo $row['des'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10" value="<?php echo $row['up'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="lp" size="10" value="<?php echo $row['lp'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="checkbox" <?php echo $checked;?> value="<?php echo $taxable;?>" id="<?php echo $i;?>" name="taxable" size="2" onclick="UpdateLine('taxable',this)"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
<?php
}
	$maxsql = "SELECT MAX(line) AS maxline FROM poline WHERE no = '$linkvalue' AND callno = '$callvalue'";
	$maxresult = mysql_query($maxsql);
	$maxrow = mysql_fetch_assoc($maxresult);
	$maxline = $maxrow['maxline'] + 1;
?>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $maxline;?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="3"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="2"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="20" onkeyup="LiveSearch(this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="des" size="75" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="lp" size="10"/></td>
	</tr>
	</table>
	<div style="background: white;position:absolute;left:160;" id="search" name="search"></div>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
