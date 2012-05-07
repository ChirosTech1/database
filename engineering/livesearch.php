<?php
//make sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
$rowno = $_POST['row'];
$field = mysql_real_escape_string($_POST['field']);
$field2 = "description";
$search = mysql_real_escape_string($_POST['search']);
$sql = "SELECT $field, 'Artwork' as description FROM artwork WHERE $field LIKE '%" . $search . "%' UNION SELECT $field, 'Drawing' as description FROM drawing WHERE $field LIKE '%" . $search . "%' UNION SELECT $field, 'Procedure' as description FROM proc WHERE $field LIKE '%" . $search . "%' UNION SELECT $field, 'Specification' as description FROM spec WHERE $field LIKE '%" . $search . "%' UNION SELECT $field, 'Tooling' as description FROM tool WHERE $field LIKE '%" . $search . "%' UNION SELECT $field, 'Supp Drawing' as description FROM suppdrawing WHERE $field LIKE '%" . $search . "%'";
$result = mysql_query($sql);
?>
<table stye="border-collapse:collapse;">
<?php
while($row = mysql_fetch_array($result))
{
?>
<tr><th colspan="2"><input type="text" style="border: 0px" size="40" value="<?php echo htmlspecialchars($row[$field]) . " " . htmlspecialchars($row[$field2]);?>" onClick="NewLine('<?php echo htmlspecialchars($row[$field]);?>','<?php echo htmlspecialchars($row[$field2]);?>','<?php echo $rowno;?>')" onkeypress="{if (event.keyCode==13)NewLine(<?php echo htmlspecialchars($row[$field]);?>);}"/></th></tr>
<?php
}
?>
</table>
<?php
mysql_close($con);
?>
