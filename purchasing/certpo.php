<html>
<head><h1>Certified Purchase Order</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "PO";
var mainfile = "purchasing";
var mainform = "gcertpo.php";
var lineform = "glcertpo.php";
var noteform = "ponotes.php";
var printform = "pcertpo.php";
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
	$sql = "SELECT * FROM po WHERE supplierid = '$supplierno' AND no > '50000' AND no < '90000' ORDER BY no DESC";
else
	$sql = "SELECT * FROM po WHERE no > '50000' AND no < '90000' ORDER BY no DESC";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete PO" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="Close" onclick="UpdateForm(this.value)"/></p>
<form id = "main">
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
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
Closed: <input type="text" name="cdate" id="cdate" onchange="UpdateForm('update')"/>
<input type="hidden" name="limit" id="limit" value=""/>
<input type="hidden" name="id" id="id" value=""/>
<input type="hidden" name="supplierno" id="supplierno" value="<?php echo $supplierno;?>"/>
<input type="hidden" name="supplierid" id = "supplierid"/>
<table>
<tr>
<th colspan="3">Supplier</th>
<th colspan="3">Ship To</th>
<th>PO Number</th>
<th>Rev</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="supplier" id="supplier" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="sname" id="sname" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="no" id="no"/></td>
<td><input type="text" size="2" name="rev" id="rev" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="address" id="address" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="saddress" id="saddress" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="date" id="date" onchange="UpdateForm('update')"/></td>
<th>Date</th>
</tr>
<tr>
<td><input type="text" size="25" name="city" id="city" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="2" name="state"  id="state" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="10" name="zip"  id="zip" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="25" name="scity"  id="scity" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="2" name="sstate"  id="sstate" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="10" name="szip"  id="szip" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<td><input type="text" size="15" name="phone" id="phone" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="30" name="conf" id="conf" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="shp" id="shp" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="terms" id="terms" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="fob" id="fob" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="cont" id="cont" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="dorate" id="dorate" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="agent" id="agent" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td><input type="text" size="15" name="fax" id="fax" onchange="UpdateForm('update')"/></td>
<th>Contact</th>
<th>Ship Via</th>
<th>Terms</th>
<th>FOB</th>
<th>Contract</th>
<th>DO Rating</th>
<th>Agent</th>
</tr>
</table>
<table>
<tr>
<th>Total:</th>
<td><input type="text" name="tot" id="tot" onchange="UpdateForm('update')"/></td>
<th>QC Approval:</th>
<td><input type="text" size="2" name="ordered" id="ordered"/></td>
</tr>
</table>
</form>
<div id="lineitem" style="padding : 4px; width : 1200px; height : 250px; overflow:auto;"></div>
<p><b>Notes:</b></p>
<div id="note" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
<form id="printform">
<input type="button" value="Preview PO" onclick="PrintForm()"/>
<input type="button" value="Print PO" onclick="PrintForm('print')"/></p>
<table>
<tr>
<th>Original</th>
<th>Purchasing</th>
<th>Accounting</th>
<th>File</th>
<th>Planning</th>
<th>Receiving</th>
<th>QC</th>
<th>Acknowledgement</th>
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Original Purchase Order"/></td>
<td><input type="checkbox" checked="checked" id="2" value="Purchasing Copy"/></td>
<td><input type="checkbox" checked="checked" id="3" value="Accounting Copy"/></td>
<td><input type="checkbox" checked="checked" id="4" value="File Copy"/></td>
<td><input type="checkbox" checked="checked" id="5" value="Planning Copy"/></td>
<td><input type="checkbox" checked="checked" id="6" value="Receiving Copy"/></td>
<td><input type="checkbox" checked="checked" id="7" value="QC Copy"/></td>
<td><input type="checkbox" checked="checked" id="8" value="Acknowledgement Copy"/></td>
</tr>
</table>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
