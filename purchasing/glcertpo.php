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
$callvalue = mysql_real_escape_string($_POST['callvalue']);
$linetaxable = mysql_real_escape_string($_POST['linetaxable']);
//Convert Dates
if($_POST['indate'])
$_POST['indate'] = date("Y-m-d",strtotime($_POST['indate']));
$dateformat = ", DATE_FORMAT(indate, '%m/%d/%Y') AS indate";
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
		if($mn)
		{
			$nsql = "SELECT * FROM material WHERE no = '$mn'";
			$nresult = mysql_query($nsql);
			$nrow = mysql_fetch_assoc($nresult);
			$cert = $nrow['spec'];
			$ssql = "SELECT * FROM spec WHERE no = '$cert'";
			$sresult = mysql_query($ssql);
			$srow = mysql_fetch_assoc($sresult);
			$newquery .= ",pn,des,cert,qal,rec";
			$pn = $nrow['pn'];
			$des = $nrow['des'];
			$cert .= $srow['rev'] .= " ";$cert .= $srow['chg'] .= " ";$cert .= $srow['note'];
			$qal = "2/"; $qal .= $nrow['qal'];
			$rec = "HP0165/"; $rec .= $nrow['rec'];
			$newvalues .= ",'$pn','$des','$cert','$qal','$rec'";
			//Insert new record "new" and display
			mysql_query("INSERT INTO $table ($newquery) VALUES ($newvalues)");
		}
		else
		{
			mysql_query("INSERT INTO $table (no,line,des,pn) VALUES ('$linkvalue','$line','$des','$pn')");
		}
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "update":
	case "early":
		//See if tax check was clicked and change value
		if ($q == 'early')
		{
		echo $erow['early'];
		if($_POST['early'])
		$early = 0;
		else
		$early = 1;
		}
		//Calculate Line price
		$_POST['lp'] = $qty * $up;
		//Update Query Variable
		for($i = 0; $i < $num; ++$i)
		{
			$f = $field[$i];
			$sqlescape = mysql_real_escape_string($_POST[$f]);
			if($sqlescape)
			{
				if($f != 'id' && $f != 'early')
				$updatequery .= "$f='$sqlescape', ";
			}
		}
		$updatequery .= "early = '$early'";
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
	case "Delete":
		if($q == 'Delete')
		{
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue'
		ORDER BY $primaryfield";
		}
		//Calculate Order Totals
		$totsql = "SELECT SUM(lp) AS lp FROM poline WHERE no = '$linkvalue'";
		$totresult = mysql_query($totsql);
		$totrow = mysql_fetch_assoc($totresult);
		$total = $totrow['lp'];
		//Update po table to reflect totals
		mysql_query("UPDATE $ptable SET tot='$total' WHERE no='$linkvalue'");
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "load":
	case "menu":
	case "Delete PO":
	case "New PO":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue'
		ORDER BY $primaryfield";
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
<?php
for ($i = 0;$row = mysql_fetch_array($result);$i++)
{
	if($row['early'] == 1)
	{
		$checked = "checked = 'checked'";
		$early = 1;
	}
	else
	{
		$checked = null;
		$early = 0;
	}

?>
	<table>
	<tr>
	<th>Line</th>
	<th>Qty</th>
	<th>Units</th>
	<th>Material</th>
	<th>Part Number</th>
	<th>Description</th>
	<th>Unit Price</th>
	<th>Line Price</th>
	</tr>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $row['line'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="3" value="<?php echo $row['qty'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="2" value="<?php echo $row['unit'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="mat" size="20" value="<?php echo $row['mn'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="20" value="<?php echo $row['pn'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="des" size="75" value="<?php echo htmlspecialchars($row['des']);?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10" value="<?php echo $row['up'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="lp" size="10" value="<?php echo $row['lp'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete" onclick="UpdateLine(this.value,this)"/></td>
	</tr>
	</table>
	<table>
	<tr>
	<th>WO</th>
	<th>In House</th>
	<th>Early OK</th>
	<th>QAL Attach</th>
	<th>Cert To</th>
	<th>Rec Insp</th>
	</tr>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="wo" size="" value="<?php echo $row['wo'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="indate" size="" value="<?php echo $row['indate'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="checkbox" <?php echo $checked;?> size="2" id="<?php echo $i;?>" name="early" value="<?php echo $early;?>" onclick="UpdateLine('early',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qal" size="" value="<?php echo $row['qal'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" size="40" id="<?php echo $i;?>" name="cert" size="" value="<?php echo $row['cert'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rec" size="" value="<?php echo $row['rec'];?>" onchange="UpdateLine('update',this)"/></td>
	</tr>
	</table>
	<hr>
<?php
}
	$maxsql = "SELECT MAX(line) AS maxline FROM poline WHERE no = '$linkvalue'";
	$maxresult = mysql_query($maxsql);
	$maxrow = mysql_fetch_assoc($maxresult);
	$maxline = $maxrow['maxline'] + 1;
?>
	<table>
	<tr>
	<th>Line</th>
	<th>Qty</th>
	<th>Units</th>
	<th>Material</th>
	<th>Part Number</th>
	<th>Description</th>
	<th>Unit Price</th>
	<th>Line Price</th>
	</tr>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="line" size="2" value="<?php echo $maxline;?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qty" size="3"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="unit" size="2"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="mn" size="20" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="pn" size="20" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="des" size="75" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="up" size="10"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="lp" size="10"/></td>
	</tr>
	</table>
	<table>
	<tr>
	<th>WO</th>
	<th>In House</th>
	<th>Early OK</th>
	<th>QAL Attach</th>
	<th>Cert To</th>
	<th>Rec Insp</th>
	</tr>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="wo" size=""/></td>
	<td><input type="text" id="<?php echo $i;?>" name="indate" size=""/></td>
	<td><input type="checkbox" id="<?php echo $i;?>" name="early" size=""/></td>
	<td><input type="text" id="<?php echo $i;?>" name="qal" size=""/></td>
	<td><input type="text" size="40" id="<?php echo $i;?>" name="cert" size=""/></td>
	<td><input type="text" id="<?php echo $i;?>" name="res" size=""/></td>
	</tr>
	</table>
	</form>
<?php
mysql_close($con);
?>

