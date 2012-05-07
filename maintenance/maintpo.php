<html>
<head><h1>Maintenance Purchase Order</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "PO";
var mainfile = "maintenance";
var mainform = "gmaintpo.php";
var lineform = "../purchasing/poline.php";
var noteform = "../purchasing/ponotes.php";
var printform = "pmaintpo.php";
var table = "poline";
var field = "pn";
var field2 = "des";
var liveform = "pnlivesearch.php";
//]]
</script>
<?php
$new = $_GET['new'];
if($new)
{
?>
<body onload="UpdateForm('<?php echo $new;?>')">
<?php
}
else
{
?>
<body onload="UpdateForm('load')">
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
	$sql = "SELECT no FROM po WHERE supplierid = '$supplierno' AND no > '300000'ORDER BY no DESC";
else
	$sql = "SELECT no FROM po WHERE no > '300000'ORDER BY no DESC";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete PO" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/></p>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id="menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Purchase Order:</option>
<?php
while ($row = mysql_fetch_array($result))
{
?>
<option value = "<?php echo $row['no'];?>"><?php echo $row['no'];?></option>
<?php
}
?>
</select>
</td>
</tr>
</table>
<input type="hidden" name="callno" id="callno"/>
<input type="hidden" name="supplierid" id="supplierid"/>
<input type="hidden" name="limit" id="limit"/>
<input type="hidden" name="id" id="id"/>
<input type="hidden" name="supplierno" id="supplierno" value="<?php echo $supplierno;?>"/>
<input type="hidden" name="address" id="address"/>
<input type="hidden" name="city" id="city"/>
<input type="hidden" name="state" id="state"/>
<input type="hidden" name="zip" id="zip"/>
<table>
<tr>
<th>Company Name</th>
<th>PO Number</th>
<th>Rev</th>
<th>Order Date</th>
</tr>
<tr>
<td><input type="text" name="supplier" id="supplier" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="no" id="no" value=""/></td>
<td><input type="text" name="rev" id="rev" value=""/></td>
<td><input type="text" name="date" id="date" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Ship Via</th>
<th>In House Date</th>
<th>For</th>
<th>Purchasing Agent</th>
<th>Terms</th>
</tr>
<tr>
<td><input type="text" name="shp" id="shp" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="inhousedate" id="indate" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="ordered" id="ordered" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="agent" id="agent" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="terms" id="terms" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table style="position:absolute;left:750px;top:500px;">
<tr>
<th>Subtotal</th>
<td><input type="text" name="subtot" id="subtot"/></td>
</tr>
<tr>
<th>Tax:</th>
<td><input type="text" name="taxtot" id="taxtot" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th>Total:</th>
<td><input type="text" name="tot" id="tot" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<div id="lineitem" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
<p><b>Notes:</b></p>
<div id="note" style="padding : 4px; width : 570px; height : 70px; overflow:auto;"></div>
<form id="printform">
<input type="button" value="Preview PO" onclick="PrintForm()"/>
<input type="button" value="Print PO" onclick="PrintForm('print')"/></p>
<table>
<tr>
<th>Original</th>
<th>Accounting</th>
<th>Receiving</th>
<th>Numeric</th>
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Original Purchase Order"/></td>
<td><input type="checkbox" checked="checked" id="2" value="Accounting Copy"/></td>
<td><input type="checkbox" checked="checked" id="3" value="Receiving Copy"/></td>
<td><input type="checkbox" checked="checked" id="4" value="Numeric Copy"/></td>
</tr>
</table>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
