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
$stable = 'speclist';
$sptable = 'spec';
$dtable = 'drawinglist';
$dptable = 'drawing';
$list = "SELECT * FROM $stable s WHERE s.no='$no' UNION SELECT * FROM $dtable d WHERE d.no='$no'";
$main = "SELECT s.no,s.rev,s.chg,s.note,s.type,s.status FROM $sptable s UNION SELECT d.no,d.rev,d.chg,d.note,d.cust,d.status FROM $dptable d";
$sql = "SELECT * FROM ($list) list JOIN ($main) main ON list.spec = main.no ORDER BY spec";
$result = mysql_query($sql);
?>
	<form id="lineitems">
	<table>
	<tr><th height="16"></th></tr>
	<tr><th colspan="2"><b>PN Documents</b></th></tr>
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
