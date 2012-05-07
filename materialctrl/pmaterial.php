<html>
<head><h1>Hamby Materials</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Material";
var mainfile = "materialctrl";
var mainform = "gmaterial.php";
var lineform = "glmaterial.php";
var noteform = "gnmaterial.php";
var printform = "pmaterial.php";
var table = "material";
var field = "pn";
var field2 = "des";
var liveform = "pnlivesearch.php";
//]]
</script>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
$id = mysql_real_escape_string($_GET['id']);
$table = 'material';
$ptable = 'po';
$ltable = 'poline';
//Define Material SQL
$sql = "SELECT * FROM $table WHERE id = '$id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$mn = $row['no'];
?>
<p/>
</table>
<input type="hidden" name="limit"/>
<input type="hidden" name="id"/>
<input type="hidden" name="callno"/>
<table>
<tr>
<th>Material No.</th>
<th>Part Number</th>
<th>Description</th>
</tr>
<tr>
<td><input type="text" name="no" value="<?php echo $row['no']; ?>"/></td>
<td><input type="text" name="pn" value="<?php echo $row['pn']; ?>"/></td>
<td><input type="text" size="50" name="des" value="<?php echo $row['des']; ?>"/></td>
</tr>
</table>
<table>
<tr>
<th>Manufacturer</th>
<th>Specification</th>
<th>Type</th>
</tr>
<tr>
<td><input type="text" name="man" value="<?php echo $row['man']; ?>"/></td>
<td><input type="text" name="spec" value="<?php echo $row['spec']; ?>"/></td>
<td><input type="text" name="type" value="<?php echo $row['type']; ?>"/></td>
</tr>
</table>
<table>
<tr>
<th>Stock</th>
<th>Obsolete</th>
<th>QAL</th>
<th>Receiving Insp.</th>
</tr>
<tr>
<td><input type="text" name="stk" value="<?php echo $row['stk']; ?>"/></td>
<td><input type="text" name="obs" value="<?php echo $row['obs']; ?>"/></td>
<td><input type="text" name="qal" value="<?php echo $row['qal']; ?>"/></td>
<td><input type="text" name="rec" value="<?php echo $row['rec']; ?>"/></td>
</tr>
</table>
<p><b>Material Order History</b><p>
<?php
//Define Lines SQL
$sql = "SELECT poline.no, po.supplier, DATE_FORMAT(po.date, '%m/%d/%Y') AS date, poline.line, poline.qty, poline.unit, poline.pn, poline.des FROM $ltable INNER JOIN $ptable ON poline.no = po.no WHERE mn = '$mn' ORDER BY no DESC";
$result = mysql_query($sql);
?>
	<table width="100%">
	<tr>
	<th>PO Number</th>
	<th>Supplier</th>
	<th>Date</th>
	<th>Qty</th>
	<th>Units</th>
	<th>PN/Description</th>
	</tr>
<?php
while ($row = mysql_fetch_array($result))
{
?>
	<tr>
	<td><input type="text" name="no" size="10%" value="<?php echo $row['no'];?>"/></td>
	<td><input type="text" name="supplier" size="20%" value="<?php echo $row['supplier'];?>"/></td>
	<td><input type="text" name="date" size="10%" value="<?php echo $row['date'];?>"/></td>
	<td><input type="text" name="qty" size="5%" value="<?php echo $row['qty'];?>"/></td>
	<td><input type="text" name="unit" size="5%" value="<?php echo $row['unit'];?>"/></td>
	<td><input type="text" name="pn" size="40%" value="<?php echo $row['pn']. " " . $row['des'];?>"/></td>
	</tr>
<?php
}?>
	</table>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
