<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'toollist';
$primaryfield = 'tool';
$secondaryfield = 'id';
$ptable = 'tool';
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
	?>
	<option value="default">Select a Type</option>
	<?php 
	$toolsql = "SELECT DISTINCT no FROM tool WHERE type = '$type' ORDER BY no";
	$toolresult = mysql_query($toolsql);
	while ($toolrow = mysql_fetch_array($toolresult)){
	?>
	<option value="<?php echo $toolrow['no'];?>"><?php echo $toolrow['no'];?></option> 
	<?php
	}
}
else
{
//Check which action to update and execute SQL
switch ($q)
{	
	case "New Tooling":
		mysql_query("INSERT INTO $table (no,tool) VALUES ('$linkvalue','$tool')");
		$sql = "SELECT $table.*, $ptable.type AS pntype, $ptable.no AS pnno, $ptable.note AS pnnote FROM $table INNER JOIN $ptable ON $table.tool = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY tool";
		break;
	case "Delete Tooling":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT $table.*, $ptable.type AS pntype, $ptable.no AS pnno, $ptable.note AS pnnote FROM $table INNER JOIN $ptable ON $table.tool = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY tool";
		break;

	case "Next":
	case "Prev":
	case "load":
	case "menu":
		//Initial load of Form
		$sql = "SELECT $table.*, $ptable.type AS pntype, $ptable.no AS pnno, $ptable.note AS pnnote FROM $table INNER JOIN $ptable ON $table.tool = $ptable.no WHERE $table.no = '$linkvalue' ORDER BY tool";
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
	<th>Tool No</th>
	</tr>
	<tr>
	<!--Add Select option for List of Tools--!>
	<td><select name="type" id="<?php echo $i;?>" onchange="UpdateList('list',this.value)">
		<option value="default">Select a Type</option>
		<?php 
		$tsql = "SELECT DISTINCT type FROM tool";
		$tresult = mysql_query($tsql);
		while ($trow = mysql_fetch_array($tresult)){
		?>
		<option value="<?php echo $trow['type'];?>"><?php echo $trow['type'];?></option> 
		<?php
		}
		?>
	</select></td>
	<!--Add Select option for List of Tools--!>
	<td><select name="tool" id="<?php echo $i;?>">
		<option value="default">Select a Tool</option>
		<?php 
		$toolsql = "SELECT DISTINCT no FROM tool";
		$toolresult = mysql_query($toolsql);
		while ($toolrow = mysql_fetch_array($toolresult)){
		?>
		<option value="<?php echo $toolrow['no'];?>"><?php echo $toolrow['no'];?></option> 
		<?php
		}
		?>
	</select></td>
	<td><input type="button" id="<?php echo $i;?>" name="new" value="New Tooling" onclick="UpdateLine(this.value,this)"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	<tr><th colspan="2"><b>PN Tooling</b></th></tr>
	<tr><th height="16"></th></tr>
	<tr>
	<th>Type</th>
	<th>Tool No</th>
	<th>Note</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="type" size="20" value="<?php echo $row['pntype'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="no" size="50" value="<?php echo $row['pnno'];?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="note" size=50" value="<?php echo $row['pnnote'];?>"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete Tooling" onclick="UpdateLine(this.value,this)"/><td>
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
