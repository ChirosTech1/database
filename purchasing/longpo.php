<html>
<head><h1>Long Purchase Order</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "PO";
var mainfile = "purchasing";
var mainform = "glongpo.php";
var lineform = "poline.php";
var noteform = "ponotes.php";
var printform = "plongpo.php";
var table = "poline";
var field = "pn";
var field2 = "des";
var field3 = "";
var liveform = "pnlivesearch.php";
var callvalue = document.getElementsByName('callno')[0].value;
//]]
</script>
<?php
$new = $_GET['new'];
if($new)
{
?>
<body onload="UpdateForm('<?php echo $new;?>');document.getElementsByName('menu')[0].focus();">
<?php
$readonlybuttons = "disabled='disabled'";
}
else
{
?>
<body onload="UpdateForm('load');document.getElementsByName('menu')[0].focus();">
<?php
$readonlybuttons = "";
}
?>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
$supplierno = mysql_real_escape_string($_GET['supplier']);
//Define SQL for drop down list
if ($supplierno)
	$sql = "SELECT * FROM po WHERE supplierid = '$supplierno' AND no > '20000' AND no < '50000' ORDER BY no DESC";
else
	$sql = "SELECT * FROM po WHERE no > '20000' AND no < '50000' ORDER BY no DESC";
$result = mysql_query($sql);
?>
<input type="button" <?php echo $readonlybuttons;?> value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" <?php echo $readonlybuttons;?> value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete PO" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="Close" onclick="UpdateForm(this.value)"/></p>
<form id="main">
<input type="hidden" name="limit" id="limit"/>
<input type="hidden" name="id" id="id"/>
<input type="hidden" name="supplierno" id="supplierno" value="<?php echo $supplierno;?>"/>
<input type="hidden" name="supplierid" id="supplierid"/>
<table>
<tr>
<td colspan="3">
<select name="menu" id="menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Purchase Order:</option>
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
</td>
<th>Closed: </th><th><input type="text" name="cdate" id="cdate" size="10" onchange="UpdateForm('update')"/></th><th>Date:</th><td><input type="text" size="10" name="date" id="date"/></td>
</tr>
<tr>
<th colspan="3">Supplier</th>
<th colspan="3">Ship To</th>
<th>PO Number</th>
<th>Rev</th>
</tr>
<tr>
<td colspan="3"><input type = "text" name="supplier" size="50" id="supplier" readonly="readonly"/></td>
<td colspan="3"><input type = "text" name="sname" size="50" id="sname" readonly="readonly"/></td>
<td><input type="text" size="15" name="no" id="no" onchange="UpdateForm('update');"/></td>
<td><input type="text" size="2" name="rev" id="rev" onchange="RevDate('<?php echo date("m/d/Y");?>');UpdateForm('update');"/></td>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="address" id="address" readonly="readonly"/></td>
<td colspan="3"><input type="text" size="50" name="saddress" id="saddress" readonly="readonly"/></td>
<th>Call #</th>
<td><input type="text" size="2" name="callno" id="callno"/></td>
</tr>
<tr>
<td><input type="text" size="25" name="city" id="city" readonly="readonly"/></td>
<td><input type="text" size="2" name="state"  id="state" readonly="readonly"/></td>
<td><input type="text" size="10" name="zip"  id="zip" readonly="readonly"/></td>
<td><input type="text" size="25" name="scity"  id="scity" readonly="readonly"/></td>
<td><input type="text" size="2" name="sstate"  id="sstate" readonly="readonly"/></td>
<td><input type="text" size="10" name="szip"  id="szip" readonly="readonly"/></td>
</tr>
</table>
<table>
<tr>
<td><input type="text" size="15" name="phone" id="phone" readonly="readonly"/></td>
<td><input type="text" size="30" name="cont" id="cont" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="10" name="inhousedate" id="inhousedate" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="shp" id="shp" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="worma" id="worma" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="terms" id="terms" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="agent" id="agent" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td><input type="text" size="15" name="fax" id="fax" readonly="readonly"/></td>
<th>Contract/Ref</th>
<th>In House Date</th>
<th>Ship Via</th>
<th>WO</th>
<th>Terms</th>
<th>Agent</th>
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
<div name="lineitem" id="lineitem" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
<p><b>Notes:</b></p>
<div name="lineitem" id="note" style="padding : 4px; width : 710px; height : 150px; overflow:auto;"></div>
<form id="printform">
<input type="button" value="Preview PO" onclick="PrintForm()"/>
<input type="button" value="Print PO" onclick="PrintForm('print')"/></p>
<table>
<tr>
<th>Original</th>
<th>Purchasing</th>
<th>Accounting</th>
<th>Receiving</th>
<th>Shipping</th>
<th>Shipping2</th>
<th>File</th>
<th>QC</th>
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Original Purchase Order"/></td>
<td><input type="checkbox" checked="checked" id="2" value="Purchasing Copy"/></td>
<td><input type="checkbox" checked="checked" id="3" value="Accounting Copy"/></td>
<td><input type="checkbox" checked="checked" id="4" value="Receiving Copy"/></td>
<td><input type="checkbox" checked="checked" id="5" value="Shipping"/></td>
<td><input type="checkbox" checked="checked" id="6" value="Shipping Copy"/></td>
<td><input type="checkbox" checked="checked" id="7" value="File Copy"/></td>
<td><input type="checkbox" checked="checked" id="8" value="QC Copy"/></td>
</tr>
</table>
</form>

<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
