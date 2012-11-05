<html>
<head>
<script type="text/javascript" src="../script/ajax.js"/></script>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'wo';
$ltable = 'woline';
$primaryfield = 'no';
$sql = "SELECT $ltable.*, $table.stat FROM $table, $ltable WHERE $table.stat = 'Open' AND $ltable.no = $table.no GROUP BY $ltable.id ORDER BY $ltable.no";
$result = mysql_query($sql);
?><table><?php
while($row = mysql_fetch_array($result))
{
//Set WO Variables
$oldwo = $wo;
$wo = $row['no'];
if($oldwo != $wo)
	{
	if($oldwo)
	{
?>
	<tr>
	<th colspan="5" style="text-align:right">Total:</th>
	<td></td>
	</tr>
<?php
	}
	$tottot = $tottot + $tot;
	$tot = $row['due'] * $row['up'];
?>
	<tr><td colspan="7">------------------------------------------------------------------------------------------------------------------</td></tr>
	<tr><th><h3><?php echo $row['no'];?></h3></th></tr>
	<tr>
	<th>Line</th>
	<th>Part Number</th>
	<th>Rev</th>
	<th>Qty</th>
	<th>Qty Due</th>
	<th>Unit Price</th>
	<th>Type</th>
	</tr>
	<tr>
	<td><?php echo $row['line'];?></td>
	<td><?php echo $row['pn'];?></td>
	<td><?php echo $row['rev'];?></td>
	<td><?php echo $row['qty'];?></td>
	<td><?php echo $row['due'];?></td>
	<td></td>
	<td><?php echo $row['type'];?></td>
	</tr>
	
<?php
	}
else
	{
	$tot = $tot + $row['due'] * $row['up'];
?>
	<tr>
	<td><?php echo $row['line'];?></td>
	<td><?php echo $row['pn'];?></td>
	<td><?php echo $row['rev'];?></td>
	<td><?php echo $row['qty'];?></td>
	<td><?php echo $row['due'];?></td>
	<td></td>
	<td><?php echo $row['type'];?></td>
	</tr>
<?php
	}
?>
<?php
}
	$tottot = $tottot + $tot;
?>
	<tr>
	<th colspan="5" style="text-align:right">Total:</th>
	<td></td>
	</tr>
	<tr><td colspan="7">------------------------------------------------------------------------------------------------------------------</td></tr>
	<tr>
	<th colspan="5" style="text-align:right">Total:</th>
	<td></td>
	</tr>
</table>
</body>
</html>
