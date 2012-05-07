<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'speclist';
$primaryfield = 'spec';
$secondaryfield = 'id';
$ptable = 'spec';
$pprimaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$list = $_POST['list'];
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




//Check to see if page call is for Ajax list reply
if($list)
{
	$type = mysql_real_escape_string($_POST['stype']);
	if ($type != 'default')
		$type = "WHERE type = '$type'";
	else
		$type = '';
	?>
	<option value="default">Select a Spec</option>
	<?php 
	$sql = "SELECT DISTINCT no FROM spec $type";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)){
	?>
	<option value="<?php echo $row['no'];?>"><?php echo $row['no'];?></option> 
	<?php
	}
}




else
{
//Check which action to update and execute SQL
switch ($q)
{	
	case "New Specification":
		mysql_query("INSERT INTO $table (no, spec) VALUES ('$linkvalue','$spec')");
		$sql = "SELECT $table.*, $ptable.type, $ptable.rev, $ptable.note, $ptable.chg FROM $table INNER JOIN $ptable ON $table.spec = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY spec";
		break;
	case "Delete Specification":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT $table.*, $ptable.type, $ptable.rev, $ptable.note, $ptable.chg FROM $table INNER JOIN $ptable ON $table.spec = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY spec";
		break;

	case "Next":
	case "Prev":
	case "load":
	case "menu":
		//Initial load of Form
		$sql = "SELECT $table.*, $ptable.type, $ptable.rev, $ptable.note, $ptable.chg FROM $table INNER JOIN $ptable ON $table.spec = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY spec";
		break;
}
//create query
$result = mysql_query($sql);
//Define $i for New insert Line
$i = mysql_num_rows($result) + 1;
?>
<html>
<body>
	<form id="lineitems">
	<table>
	<tr>
	<th>Type</th>
	<th>Spec No</th>
	</tr>
	<tr>
	<!--Add Select option for List of Tools--!>
	<td><select name="type" id="<?php echo $i;?>" onchange="UpdateList('list',this.value)">
		<option value="default">Select a Type</option>
		<?php 
		$tsql = "SELECT DISTINCT type FROM spec";
		$tresult = mysql_query($tsql);
		while ($trow = mysql_fetch_array($tresult)){
		?>
		<option value="<?php echo $trow['type'];?>"><?php echo $trow['type'];?></option> 
		<?php
		}
		?>
	</select></td>
	<!--Add Select option for List of Tools--!>
	<td><select name="spec" id="<?php echo $i;?>">
		<option value="default">Select a Spec</option>
		<?php 
		$toolsql = "SELECT DISTINCT no FROM spec";
		$toolresult = mysql_query($toolsql);
		while ($toolrow = mysql_fetch_array($toolresult)){
		?>
		<option value="<?php echo $toolrow['no'];?>"><?php echo $toolrow['no'];?></option> 
		<?php
		}
		?>
	</select></td>
	<td><input type="button" id="<?php echo $i;?>" name="new" value="New Specification" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
	</table>
	<table>
	<tr><th colspan="5" height="16"></th></tr>
	<tr><th colspan="5"><b>PN Specifications</b></th></tr>
	<tr><th colspan="5" height="16"></th></tr>
	<tr>
	<th>Spec No</th>
	<th>Rev</th>
	<th>Change</th>
	<th>Note</th>
	<th>Type</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="spec" size="10" value="<?php echo $row['spec'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rev" size="5" value="<?php echo $row['rev'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="chg" size="10" value="<?php echo $row['chg'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="note" size=50" value="<?php echo $row['note'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="type" size="10" value="<?php echo $row['type'];?>"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete Specification" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
<?php
}
?>
	</form>
</body>
</html>
<?php
}
mysql_close($con);
?>
