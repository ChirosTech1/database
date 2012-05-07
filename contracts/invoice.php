<html>
<head><h1>Work Order Invoice</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Invoice";
var mainfile = "contracts";
var mainform = "ginvoice.php";
var lineform = "glinvoice.php";
var noteform = "";
var printform = "pinvoice.php";
//]]
</script>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
if($_GET['linkvalue'])
{
$linkvalue = mysql_real_escape_string($_GET['linkvalue']);
$wo = $linkvalue;
}
$new = $_GET['new'];
if($new)
{
//Check to see if all Departments have released WO
$rsql = "SELECT eng,qc,mat,purch,plan,cont FROM wo WHERE no = '$wo'";
$rresult = mysql_query($rsql);
$num = mysql_num_fields($rresult);
$field = array();
for($i = 0;$rrow = mysql_fetch_array($rresult);$i++)
{
	if(!$rrow['eng'])
		$field['eng'] = "Engineering";
	if(!$rrow['qc'])
		$field['qc'] = "Quality Control";
	if(!$rrow['mat'])
		$field['mat'] = "Material Control";
	if(!$rrow['purch'])
		$field['purch'] = "Purchasing";
	if(!$rrow['plan'])
		$field['plan'] = "Planning";
	if(!$rrow['cont'])
		$field['cont'] = "Contracts";
	
}
if($field)
{
	if($field['eng'])
	$clear .= $field['eng'] . ", ";
	if($field['qc'])
	$clear .= $field['qc'] . ", ";
	if($field['mat'])
	$clear .= $field['mat'] . ", ";
	if($field['purch'])
	$clear .= $field['purch'] . ", ";
	if($field['plan'])
	$clear .= $field['plan'] . ", ";
	if($field['cont'])
	$clear .= $field['cont'] . ", ";

?>
	<script>alert("The Following Departments Have Not releaseded this work order for Invoicing: <?php echo $clear;?> please release these before invoicing.");window.close();</script>
<?php
}
else
{
?>
<body onload="UpdateForm('<?php echo $new;?>');document.getElementsByName('menu')[0].focus();">
<?php
}
}
else
{
?>
<body onload="UpdateForm('load');document.getElementsByName('menu')[0].focus();">
<?php
}
?>
<?php
//Define SQL for drop down list
if ($linkvalue)
	$sql = "SELECT * FROM inv WHERE wo = '$linkvalue' ORDER BY no DESC";
else
	$sql = "SELECT * FROM inv ORDER BY no DESC";
$result = mysql_query($sql);
?>
<form id = "main">
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select an Invoice:</option>
<?php
while ($row = mysql_fetch_array($result))
{
?>
<option value = "<?php echo $row['id'];?>"><?php echo $row['no'];?></option>
<?php
}
?>
</select>
<input type="hidden" name="limit" id="limit" value=""/>
<input type="hidden" name="linkvalue" id="linkvalue" value="<?php echo $linkvalue;?>"/>
<input type="hidden" name="id" id="id"/>
<input type="hidden" name="callno" id="callno"/>
<input type="hidden" name="custno" id="custno"/>
<table>
<tr>
<th colspan="3">Supplier</th>
<th colspan="3">Ship To</th>
<th>Invoice Number</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="bco" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="sco" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="no"/></td>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="bcont" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="scont" onchange="UpdateForm('update')"/></td>
<th>Date</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="baddress" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="saddress" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="date" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td><input type="text" size="25" name="bcity" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="2" name="bstate" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="10" name="bzip" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="25" name="scity" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="2" name="sstate" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="10" name="szip" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<td><input type="text" size="15" name="wo" value="<?php echo $wo;?>" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="30" name="po" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="shp" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="nopgs" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="15" name="terms" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="5" name="shpchrg" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th>Work Order</th>
<th>Purchase Order</th>
<th>Ship Via</th>
<th>No. Pkgs</th>
<th>Terms</th>
<th>Ship Charge</th>
</tr>
</table>
<table style="position:absolute;top:550px;left:500px;">
<tr>
<th>Total:</th>
<td><input type="text" name="tot" id="tot" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<div id="lineitem" style="padding : 4px; width : 800px; height : 250px; overflow:auto;"></div>
<div id="note"></div>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete Invoice" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="Close" onclick="UpdateForm(this.value)"/></p>
<form id="printform">
<input type="button" value="Preview Invoice" onclick="PrintForm()"/>
<input type="button" value="Print Invoice" onclick="PrintForm('print')"/></p>
<table>
<tr>
<th>Original</th>
<th>Copy</th>
<th>Copy 2</th>
<th>Shipper</th>
<th>Shipping Copy</th>
<th>Accounting</th>
<th>File</th>
<th>Sales</th>
<th>GSI</th>
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Original Invoice"/></td>
<td><input type="checkbox" checked="checked" id="2" value="Invoice Copy"/></td>
<td><input type="checkbox" checked="checked" id="3" value="Invoice Copy 2"/></td>
<td><input type="checkbox" checked="checked" id="4" value="Shipping"/></td>
<td><input type="checkbox" checked="checked" id="5" value="Shipping Copy"/></td>
<td><input type="checkbox" checked="checked" id="6" value="Accounting Copy"/></td>
<td><input type="checkbox" checked="checked" id="7" value="File Copy"/></td>
<td><input type="checkbox" checked="checked" id="8" value="Sales Copy"/></td>
<td><input type="checkbox" checked="checked" id="9" value="GSI"/></td>
</tr>
</table>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
