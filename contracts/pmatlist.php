<html>
<head><h1>Part Number Material List</h1></head>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
$id = $_GET['id'];
//Define SQL for drop down list
$sql = "SELECT * FROM pn WHERE id = $id";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$no = $row['no'];
$rev = $row['rev'];
?>
<body>
<form id = "main">
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
$table = 'matlist';
$ptable = 'material';
$sql = "SELECT $table.*, $ptable.pn AS pnpn, $ptable.des AS pndes FROM $table INNER JOIN $ptable ON $table.mat = $ptable.no WHERE $table.no = '$no' ORDER BY mat";
$result = mysql_query($sql);
?>
	<form id="lineitems">
	<table>
	<tr><th height="16"></th></tr>
	<tr><th colspan="2"><b>PN Materials</b></th></tr>
	<tr><th height="16"></th></tr>
	<tr>
	<th>Material</th>
	<th>Description</th>
	<th>Qty</th>
	<th>Unit</th>
	<th>PPP</th>
	</tr>
<?php
while($row = mysql_fetch_array($result))
{
?>
	<tr>
	<td><input type="text" name="mat" size="10" value="<?php echo $row['mat'];?>"/></td>
	<td><input type="text" name="pndes" size="30" value="<?php echo $row['pnpn'] . " " . $row['pndes'];?>"/></td>
	<td><input type="text" name="qty" size="5" value="<?php echo $row['qty'];?>"/></td>
	<td><input type="text" name="unit" size="5" value="<?php echo $row['unit'];?>"/></td>
	<td><input type="text" name="Per Panel" size="30" value="<?php if($row['ppp']){echo $row['ppp'] . " per " . $row['des'];} else echo $row['des'];?>"/></td>
	<td>_______________</td>
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
