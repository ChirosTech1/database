<?php
//make sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
$table = mysql_real_escape_string($_POST['table']);
$field = mysql_real_escape_string($_POST['field']);
$field2 = mysql_real_escape_string($_POST['field2']);
$search = mysql_real_escape_string($_POST['search']);
$sql = "SELECT DISTINCT $field, $field2 FROM $table WHERE $field LIKE '%" . $search . "%' OR $field2 LIKE '%" . $search . "%'";
$result = mysql_query($sql);
?>
<table stye="border-collapse:collapse;">
<?php
while($row = mysql_fetch_array($result))
{
?>
<tr><th colspan="2"><input type="text" style="border: 0px" size="40" value="<?php echo htmlspecialchars($row[$field]) . " " . htmlspecialchars($row[$field2]);?>" onClick="NewLine('<?php echo htmlspecialchars($row[$field]);?>','<?php echo htmlspecialchars($row[$field2]);?>')" onkeypress="{if (event.keyCode==13)NewLine(<?php echo htmlspecialchars($row[$field]);?>);}"/></th></tr>
<?php
}
?>
</table>
<?php
mysql_close($con);
?>
