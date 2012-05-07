<html>
<head><h1>Hamby Material Requisition</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Requisition";
var mainfile = "materialctrl";
var mainform = "greq.php";
var lineform = "";
var noteform = "";
var printform = "preq.php";
var table = "matreq";
var field = "no";
var field2 = "pn";
var liveform = "pnlivesearch.php";
//]]
</script>
<?php
$new = $_GET['q'];
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
$linkvalue = mysql_real_escape_string($_GET['linkvalue']);
//Define SQL for drop down list
if($linkvalue)
$sql = "SELECT no FROM matreq WHERE hpn = '$linkvalue' ORDER BY no DESC";
else
$sql = "SELECT no FROM matreq ORDER BY no DESC";
$result = mysql_query($sql);
?>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Requisition:</option>
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
<input type="hidden" name="limit"/>
<input type="hidden" name="linkvalue" value="<?php echo $linkvalue;?>"/>
<input type="hidden" name="id" id="id"/>
<input type="hidden" name="callno"/>
<table>
<tr>
<th>Material No.</th>
<th>Requisition No.</th>
<th>Date</th>
</tr>
<tr>
<td><input type="text" name="hpn" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="no"/></td>
<td><input type="text" name="date"/></td>
</tr>
</table>
<table>
<tr>
<th>Customer</th>
<th>Work Order</th>
<th>Quantity</th>
<th>Units</th>
</tr>
<tr>
<td><input type="text" name="cust"/></td>
<td><input type="text" name="wo" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="qty" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="unit" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Used On</th>
<th>Manufaturer P/N</th>
<th>Description</th>
</tr>
<tr>
<td><input type="text" name="used" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="manpn" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="des" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<p><b>Notes:</b></p>
<table>
<td><textarea cols="70" rows="5" name="note" onchange="UpdateForm('update')"></textarea></td>
</table>
</form>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete Req" onclick="UpdateForm('Delete Requisition')"/>
<form id="printform">
<input type="button" value="Print" onclick="PrintForm()"/>
<input type="hidden" checked="checked" id="1" value="Original Purchase Order"/>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
