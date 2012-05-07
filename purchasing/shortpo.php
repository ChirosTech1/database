<html>
<head><h1>Short Purchase Order</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "PO";
var mainfile = "purchasing";
var mainform = "gshortpo.php";
var lineform = "poline.php";
var noteform = "ponotes.php";
var printform = "pshortpo.php";
var table = "poline";
var field = "pn";
var field2 = "des";
var field3 = "";
var liveform = "pnlivesearch.php";
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
	$sql = "SELECT no FROM po WHERE supplierid = '$supplierno' AND no < '20000' ORDER BY no DESC";
else
	$sql = "SELECT no FROM po WHERE no < '20000' ORDER BY no DESC";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete PO" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="Close" onclick="UpdateForm(this.value)"/></p>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
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
<input type="hidden" name="supplierid" id = "supplierid"/>
<input type="hidden" name="limit" id="limit"/>
<input type="hidden" name="id" id="id"/>
<input type="hidden" name="supplierno" id="supplierno" value="<?php echo $supplierno;?>"/>
<table>
<tr>
<th>Company Name</th>
<th>PO Number</th>
<th>Rev</th>
<th>Order Date</th>
<th>Closed Date</th>
</tr>
<tr>
<td><input type="text" name="supplier" id="supplier" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="no" id="no" value=""/></td>
<td><input type="text" name="rev" id="rev" value=""/></td>
<td><input type="text" name="date" id="date" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="cdate" id="cdate" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Confirming With</th>
<th>Deliver To</th>
<th>Ship Via</th>
<th>In House Date</th>
</tr>
<tr>
<td><input type="text" name="conf" id="conf" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="sname" id="sname" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="shp" id="shp" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="inhousedate" id="inhousedate" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Ordered By</th>
<th>Checked By</th>
<th>Charge To Job</th>
</tr>
<tr>
<td><input type="text" name="ordered" id="ordered" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="agent" id="agent" onchange="UpdateForm('update')"/></td>
<td><select name="job" id="job" onchange="UpdateForm('update')">
<option value="default">Select a Job Number</option>
<?php
$jsql = "SELECT * FROM pojob";
$jresult = mysql_query($jsql);
while($jrow = mysql_fetch_array($jresult))
{
?>
<option value="<?php echo $jrow['no']." ".$jrow['des'];?>"><?php echo $jrow['no']." ".$jrow['des'];?></option>
<?php
}
?>
</td>
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
<p><b>Notes: (Maximum of 3 notes)</b></p>
<div id="note" style="padding : 4px; width : 570px; height : 70px; overflow:auto;"></div>
<form id="printform">
<input type="button" value="Preview PO" onclick="PrintForm()"/>
<input type="button" value="Print PO" onclick="PrintForm('print')"/></p>
<table>
<tr>
<th>Original</th>
<th>Numeric</th>
<th>Receiving</th>
<th>Accounting</th>
<th>Requisitioner</th>
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Original Purchase Order"/></td>
<td><input type="checkbox" checked="checked" id="2" value="Numeric Copy"/></td>
<td><input type="checkbox" checked="checked" id="3" value="Recieving Copy"/></td>
<td><input type="checkbox" checked="checked" id="4" value="Accounting Copy"/></td>
<td><input type="checkbox" checked="checked" id="5" value="Requisitioner Copy"/></td>
</tr>
</table>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
