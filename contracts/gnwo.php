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
$table = 'wonote';
$primaryfield = 'id';
$secondaryfield = 'id';
$ptable = 'wo';
$pprimaryfield = 'no';
//Define other variables
$q = $_POST['q'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
if($_POST['ndate'])
$_POST['ndate'] = date("Y-m-d",strtotime($_POST['ndate']));
$dateformat = ", DATE_FORMAT(ndate, '%m/%d/%Y') AS ndate";
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
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "New":
		//New Query Variable
		for($i = 0; $i < $num; ++$i)
		{
			$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
			if($sqlescape)
			{
				if($field[$i] != 'no' && $sqlescape)
				{
				$newquery .= "$field[$i],";
				$newvalues .= "'$sqlescape',";
				}
			}
		}
		$newquery .= "no";
		$newvalues .= "'$linkvalue'";
		//Insert new record and display
		mysql_query("INSERT INTO $table ($newquery) VALUES ($newvalues)");
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "update":
		//Update Query Variable
		for($i = 0; $i < $num - 1; ++$i)
		{
			$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
			if($sqlescape)
			$updatequery .= "$field[$i] = '$sqlescape', ";
		}
		$updatequery .= "id = '$id'";
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
	case "Delete":
		if($q == 'Delete')
		{
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		}
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		break;
	case "load":
	case "menu":
	case "Delete PO":
	case "New PO":
		if($q=='New PO')
		mysql_query("INSERT INTO $table (no,note) VALUES ('$linkvalue','For Resale CA Permit # SR-AC-13-081200')");
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * $dateformat FROM $table WHERE no = '$linkvalue' 
		ORDER BY $primaryfield";
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
	echo "No Notes Entered";

?>
	<form id="notes">
	<table>
	<tr>
	<th>Notes:</th>
	</tr>
<?php
for ($i=0;$row = mysql_fetch_array($result);$i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="note" size="75" value="<?php echo htmlspecialchars($row['note']);?>" onchange="UpdateNote('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="auth" size="1" value="<?php echo $row['auth'];?>" onchange="UpdateNote('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="date" size="10" value="<?php echo $row['ndate'];?>" onchange="UpdateNote('update',this)"/></td>
	</tr>
<?php
}
?>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="note" size="75"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="auth" size="1" onchange="UpdateNote('New', this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="ndate" size="10" value="<?php echo date('m/d/Y');?>"/></td>
	</tr>
	</table>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
