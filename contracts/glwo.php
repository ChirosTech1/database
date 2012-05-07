<html>
<head>
<script type="text/javascript" src="../script/ajax.js"/></script>
<script type="text/javascript" src="../script/wo.js"/></script>
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
$dtable = 'wodate';
//Define other variables
$q = $_POST['q'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
$callvalue = mysql_real_escape_string($_POST['callvalue']);
$linetaxable = mysql_real_escape_string($_POST['linetaxable']);
$dline = mysql_real_escape_string($_POST['dline']);
$did = mysql_real_escape_string($_POST['did']);
//Convert Dates
if($_POST['indate'])
$_POST['indate'] = date("Y-m-d",strtotime($_POST['indate']));
if($_POST['ddue'])
$_POST['ddue'] = date("Y-m-d",strtotime($_POST['ddue']));
$dateformat = ", DATE_FORMAT(ddue, '%m/%d/%Y') AS ddue";
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
$newquery .= "no,taxable";
$newvalues .= "'$linkvalue','0'";

//Get Date Field names from Table
$dfieldsql = "SELECT * FROM $dtable LIMIT 1";
$dfieldresult = mysql_query($dfieldsql);
$dnum = mysql_num_fields($dfieldresult);
$dfield = array();
for($i = 0; $i < $num; ++$i)
{
	$dfields = mysql_fetch_field($dfieldresult, $i);
	$dfield[$i] = $dfields->name;
}
//Define Date Variables based on SQL Field names
for($i = 0; $i < $dnum; ++$i)
{
	$$dfield[$i] = mysql_real_escape_string($_POST[$dfield[$i]]);
}

//Check which action to update and execute SQL
switch ($q)
{
	case "Next":
		//Go to next record in set
		$limit = $limit + 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "New":
		//Get P/N Information
		$rev = mysql_fetch_array(mysql_query("SELECT rev from pn WHERE no = '$pn'"));
		$rev = $rev['rev'];
		//Make insert query
		mysql_query("INSERT INTO $table (no, line, pn, rev) VALUES ('$linkvalue','$line','$pn','$rev')");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "dNew":
		//Insert New Line Date
		mysql_query("INSERT INTO $dtable (no, line, dqty) VALUES ('$linkvalue','$dline','$dqty')");
		//Update Lines
		mysql_query("UPDATE $table SET due = (due + '$dqty'), qty = (SELECT SUM(dqty) FROM $dtable WHERE no = '$linkvalue' AND line = '$dline')  WHERE no = '$linkvalue' AND line = '$dline'");
		//Update Totals
		mysql_query("UPDATE $ptable SET tot = (SELECT SUM(qty * up) FROM $table WHERE no = '$linkvalue') WHERE no = '$linkvalue'");
		
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "dupdate":
		//Update Date Query Variable
		for($i = 0; $i < $dnum; ++$i)
		{
			$df = $dfield[$i];
			$dsqlescape = mysql_real_escape_string($_POST[$df]);
			if($dsqlescape)
			{
				if($dsqlescape && $df != 'id' && $df != 'line')
				$dupdatequery .= "$df='$dsqlescape', ";
			}
		}
		$dupdatequery .= "line = '$dline'";
		//Update Date
		mysql_query("UPDATE $dtable SET $dupdatequery WHERE id = '$did'");
	case "dDelete":
		if($q == 'dDelete')
		{
		//Delete Date
		mysql_query("DELETE FROM $dtable WHERE id = '$did'");
		}
		//Update Lines
		mysql_query("UPDATE $table SET due = (due - qty + (SELECT SUM(dqty) FROM $dtable WHERE no = '$linkvalue' AND line = '$dline')), qty = (SELECT SUM(dqty) FROM $dtable WHERE no = '$linkvalue' AND line = '$dline')  WHERE no = '$linkvalue' AND line = '$dline'");
		//Update Totals
		mysql_query("UPDATE $ptable SET tot = (SELECT SUM(qty * up) FROM $table WHERE no = '$linkvalue') WHERE no = '$linkvalue'");
		//Display the Order
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "update":
		if($q == 'update')
		{
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
		mysql_query("DELETE FROM $dtable WHERE line = '$line' AND no = '$linkvalue'");
		}
		//Update Totals
		mysql_query("UPDATE $ptable SET tot = (SELECT SUM(qty * up) FROM woline WHERE no='$linkvalue') WHERE no='$linkvalue'");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "load":
	case "menu":
	case "Delete PO":
	case "New PO":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
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
	<th width="2">Line</th>
	<th width="150">Part Number</th>
	<th>Rev</th>
	<th>Qty</th>
	<th>Due</th>
	<th>Unit Price</th>
	<th>Type</th>
	</tr>

<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
/*	if($row['taxable'])
	{
		$checked = "checked = 'checked'";
		$early = 1;
	}
	else
	{
		$checked = null;
		$early = 0;
	}
*/
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $row['line'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="20" value="<?php echo $row['pn'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rev" size="2" value="<?php echo htmlspecialchars($row['rev']);?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="4" disabled value="<?php echo $row['qty'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="due" size="4" disabled value="<?php echo $row['due'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10" value="<?php echo $row['up'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="type" size="2" value="<?php echo $row['type'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete" onclick="UpdateLine(this.value,this)"/></td>
	</tr>
		<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<th>Qty</th>
		<th>Due Date</th>
		</tr>
		<?php
		$rowline = $row['line'];
		$dresult = mysql_query($dsql);
		for ($j = $i;$drow = mysql_fetch_array($dresult); $j++)
		{
		if($drow['line'] == $rowline)
		{
		?>
		<input type="hidden" id="<?php echo $j;?>" name='dline' value="<?php echo $rowline;?>"/>
		<input type="hidden" id="<?php echo $j;?>" name='did' value="<?php echo $drow['id'];?>"/>
		<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><input type="text" id="<?php echo $j;?>" name="dqty" size="4" value="<?php echo $drow['dqty'];?>" onchange="UpdateLine('dupdate',this)"/></td>
		<td><input type="text" id="<?php echo $j;?>" name="ddue" size="10" value="<?php echo $drow['ddue'];?>" onchange="UpdateLine('dupdate',this)"/></td>
		<td><input type="button" id="<?php echo $j;?>" name="ddelete" value="Delete" onclick="UpdateLine('dDelete',this)"/></td>
		</tr>
		<?php
		}
		}?>
		<tr>
		<input type="hidden" id="<?php echo $j;?>" name='dline' value="<?php echo $rowline;?>"/>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><input type="text" id="<?php echo $j;?>" name="dqty" size="4" value="<?php echo $drow['dqty'];?>" onchange="UpdateLine('dNew',this)"/></td>
		<td><input type="text" id="<?php echo $j;?>" name="ddue" size="10" value="<?php echo $drow['ddue'];?>" onchange="UpdateLine('dNew',this)"/></td>
		</tr>	
<?php
}
	$maxsql = "SELECT MAX(line) AS maxline FROM woline WHERE no = '$linkvalue'";
	$maxresult = mysql_query($maxsql);
	$maxrow = mysql_fetch_assoc($maxresult);
	$maxline = $maxrow['maxline'] + 1;
	$i = $i + 1;
?>
	</table>
	<table>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $maxline;?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="20" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rev" size="2"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="4"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="due" size="4"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="type" size="2"/></td>
	</tr>
	</table>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
