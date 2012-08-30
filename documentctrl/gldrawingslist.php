<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'drawinglist';
$primaryfield = 'drawing';
$secondaryfield = 'id';
$ptable = 'drawing';
$pprimaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$list = $_POST['list'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
$drawing = mysql_real_escape_string($_POST['drawing']);
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
	case "New Drawing":
		mysql_query("INSERT INTO $table (no, drawing) VALUES ('$linkvalue','$drawing')");
		$sql = "SELECT $table.*, $ptable.rev, $ptable.note, $ptable.chg FROM $table INNER JOIN $ptable ON $table.drawing = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "Delete Drawing":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT $table.*, $ptable.rev, $ptable.note, $ptable.chg FROM $table INNER JOIN $ptable ON $table.drawing = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY $primaryfield";
		break;

	case "Next":
	case "Prev":
	case "load":
	case "menu":
		//Initial load of Form
		$sql = "SELECT $table.*, $ptable.rev, $ptable.note, $ptable.chg FROM $table INNER JOIN $ptable ON $table.drawing = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY $primaryfield";
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
	<th>Drawing No</th>
	</tr>
	<tr>
	<!--Add Select option for List of Drawings--!>
	<td><select name="drawing" id="<?php echo $i;?>">
		<option value="default">Select a Drawing</option>
		<?php 
		$toolsql = "SELECT DISTINCT no FROM drawing";
		$toolresult = mysql_query($toolsql);
		while ($toolrow = mysql_fetch_array($toolresult)){
		?>
		<option value="<?php echo $toolrow['no'];?>"><?php echo $toolrow['no'];?></option> 
		<?php
		}
		?>
	</select></td>
	<td><input type="button" id="<?php echo $i;?>" name="new" value="New Drawing" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	<tr><th colspan="2"><b>PN Drawings</b></th></tr>
	<tr><th height="16"></th></tr>
	<tr>
	<th>Drawing</th>
	<th>Rev</th>
	<th>Change</th>
	<th>Note</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="drawing" size="20" value="<?php echo $row['drawing'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="rev" size="10" value="<?php echo $row['rev'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="chg" size="50" value="<?php echo $row['chg'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="note" size=50" value="<?php echo $row['note'];?>"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete Drawing" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
<?php
}
?>
	</form>
</body>
</html>
<?php
mysql_close($con);
?>
