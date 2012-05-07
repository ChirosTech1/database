<html>
<head><h1>Part Number Document List</h1></head>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//define variables from GET data
$id = mysql_real_escape_string($_GET['id']);
//Define SQL fields
$sql = "SELECT * FROM pn WHERE id = '$id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$no = $row['no'];
$rev = $row['rev'];
$status = $row['status'];
?>
<!--<body onload="window.print()">--!>
<body>
<form id="main">
<input type="hidden" name="limit"/>
<input type="hidden" name="id"/>
<table>
<tr>
<th>Part Number</th>
<th>Rev</th>
</tr>
<tr>
<td><input type="text" size="40" name="no" value="<?php echo $no;?>"/></td>
<td><input type="text" size="10" name="rev" value="<?php echo $rev;?>"/></td>
</tr>
</table>
</form>
<?php
//Define line SQL
$table = 'speclist';
$ptable = 'spec';
$sql = "SELECT $table.no AS pn, $ptable.* FROM $table INNER JOIN $ptable ON $table.spec = $ptable.no WHERE $table.no = '$no' ORDER BY no";
//What gets you close to combining the two is 
//SELECT speclist.spec AS no, drawinglist.drawing AS no, spec.rev, drawing.rev FROM spec JOIN speclist ON spec.no = speclist.spec JOIN drawinglist ON speclist.no = drawinglist.no JOIN drawing ON drawinglist.drawing = drawing.no WHERE speclist.no = '846000' AND drawinglist.no = '846000'

$result = mysql_query($sql);
?>
	<form id="lineitems">
	<table>
	<tr><th height="16"></th></tr>
	<tr><th colspan="2"><b>PN Tooling</b></th></tr>
	<tr><th height="16"></th></tr>
	<tr>
	<th>Specification #</th>
	<th>Rev</th>
	<th>Changes</th>
	<th>Type</th>
	<th>Status</th>
	</tr>
<?php
while($row = mysql_fetch_array($result))
{
?>
	<tr>
	<td><input type="text" name="pn" size="20" value="<?php echo $row['no'];?>"/></td>
	<td><input type="text" name="rev" size="4" value="<?php echo $row['rev'];?>"/></td>
	<td><input type="text" name="chg" size="30" value="<?php echo $row['chg'];?>"/></td>
	<td><input type="text" name="type" size="10" value="<?php echo $row['type'];?>"/></td>
	<td><input type="text" name="status" size="5" value="<?php if($row['status']) echo $row['status'];else echo "GO";?>"/></td>
	</tr>
<?php
}
?>
	</table>
	</form>

<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
