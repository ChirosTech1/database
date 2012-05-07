<html>
<head><h1>Work Order</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript" src="../script/wo.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "WO";
var mainfile = "contracts";
var mainform = "gwo.php";
var lineform = "glwo.php";
var noteform = "gnwo.php";
var printform = "pwo.php";
//]]
</script>
<?php
$new = $_GET['new'];
if($new)
{
?>
<body onload="UpdateForm('<?php echo $new;?>');document.getElementsByName('menu')[0].focus();">
<?php
}
else
{
?>
<body onload="UpdateForm('load');document.getElementsByName('menu')[0].focus();">
<?php
}
?>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
$supplierno = mysql_real_escape_string($_GET['supplier']);
//Define SQL for drop down list
if ($supplierno)
	$sql = "SELECT * FROM wo WHERE custid = '$supplierno' ORDER BY no DESC";
else
	$sql = "SELECT * FROM wo ORDER BY no DESC";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete WO" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/></p>

<form id = "main">
<select name="menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Work Order:</option>
<?php
while ($row = mysql_fetch_array($result))
{
$callno = $row['callno'];
if($callno){
?>
<option value = "<?php echo $row['id'];?>"><?php echo $row['no'];?> Call#<?php echo $row['callno'];?></option>
<?php
}
else{
?>
<option value = "<?php echo $row['id'];?>"><?php echo $row['no'];?></option>
<?php
}
}
?>
</select>
<p></p>
<input type="hidden" name="limit"/>
<input type="hidden" name="id" id="id"/>
<input type="hidden" name="callno"/>
<input type="hidden" name="supplierno" value="<?php echo $supplierno;?>"/>
<table>
<tr>
<th>Work Order</th>
<td><input type="text" name="no" id="no" onchange="UpdateForm('update')"/></td>
<th>Rev</th>
<td><input type="text" name="rev" onchange="UpdateForm('update')"/></td>
<th>PO Number</th>
<td><input type="text" name="po" onchange="UpdateForm('update')"/></td>
<th>Date</th>
<td><input type="text" name="date" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th>Customer</th>
<td><input type="text" name="co" onchange="UpdateForm('update')"/></td>
<th>Cust No.</th>
<td><input type="text" name="custid" onchange="UpdateForm('update')"/></td>
<th>Req Ship</th>
<td><input type="text" name="shp" onchange="UpdateForm('update')"/></td>
<th>Status</th>
<td><input type="text" name="stat"onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table style="position:absolute;left:10px;top:500px;">
<tr>
<th>Quote No.</th>
<td><input type="text" name="quote" onchange="UpdateForm('update')"/></td>
<th>Source</th>
<td><input type="text" size="2" name="src" onchange="UpdateForm('update')"/></td>
<th>BFE</th>
<td><input type="text" size="2" name="bfe" onchange="UpdateForm('update')"/></td>
<td width="50"></td>
<th>Total</th>
<td><input type="text" name="tot" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<div id="lineitem" style="padding : 4px; width : 1000px; height : 250px; overflow:auto;"></div>
<div style="height:100px;"></div>
<div id="note" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
<form id="printform">
<input type="button" value="Preview WO" onclick="PrintForm()"/>
<input type="button" value="Print WO" onclick="PrintForm('print')"/>
<input type="button" value="Invoices" onclick="Invoices(this.value)"/>
<input type="button" value="New Invoice" onclick="Invoices(this.value)"/></p>
<table>
<tr>
<th>Original</th>
<th>Contracts</th>
<th>Planning</th>
<th>QC</th>
<th>Kit</th>
<th>Prodcution</th>
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Work Order"/></td>
<td><input type="checkbox" checked="checked" id="2" value="Contracts Work Order Copy"/></td>
<td><input type="checkbox" checked="checked" id="3" value="Planning Work Order Copy"/></td>
<td><input type="checkbox" checked="checked" id="4" value="QC Work Order Copy"/></td>
<td><input type="checkbox" checked="checked" id="5" value="Kit Work Order Copy"/></td>
<td><input type="checkbox" checked="checked" id="6" value="Production Work Order"/></td>
</tr>
</table>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
