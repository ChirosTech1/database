<html>
<head>
<script type="text/javascript" src="../script/ajax.js"/></script>
<script type="text/javascript" src="../script/debit.js"/></script>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'ponote';
$primaryfield = 'id';
$secondaryfield = 'id';
$callfield = 'callno';
$ptable = 'po';
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
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' ORDER BY $primaryfield";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' ORDER BY $primaryfield";
		break;
	case "New":
		//Insert new record and display
		mysql_query("INSERT INTO $table ($newquery) VALUES ($newvalues)");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue' ORDER BY $primaryfield";
		break;
	case "update":
		mysql_query("UPDATE  $table SET $updatequery 
		WHERE id = '$id'");
	case "Delete":
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' AND $callfield = '$callvalue'
		ORDER BY $primaryfield";
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
	echo "No Notes Entered";

?>
	<form>
	<table>
	<tr>
	<th>Notes:</th>
	</tr>
<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="note" size="75" value="<?php echo $row['note'];?>" onchange="UpdateNote('update',this)"/></td>
	<input type="button" id="<?php echo $i;?>" name="delete" value="Delete" onclick="UpdateNote(this.value,this)"/>
	</tr>
<?php
}
?>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="note" size="75" onchange="UpdateNote('New', this)"/></td>
	</tr>
	</table>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
