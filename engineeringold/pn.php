<html>
<head><h1>Customer Part Numbers</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Part Number";
var mainfile = "engineering";
var mainform = "gpn.php";
var lineform = "glpn.php";
var noteform = "";
var printform = "";
var table = "pn";
var field = "no";
var field2 = "des";
var liveform = "pnlivesearch.php";
function Requisition(q)
{
	var id = document.getElementsByName('no')[0].value;
	window.open("req.php?linkvalue="+id+"&q="+q);
}
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
//Define SQL for drop down list
$sql = "SELECT no FROM pn ORDER BY no";
$result = mysql_query($sql);
?>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select a PN:</option>
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
<th>Status</th>
<td><input type="text" name="status"/>
</tr>
</table>
<input type="hidden" name="limit"/>
<input type="hidden" name="id"/>
<table>
<tr>
<th>Part Number</th>
<th>Rev</th>
</tr>
<tr>
<td><input type="text" size="40" name="no" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="10" name="rev" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>E.O.s</th>
</tr>
<tr>
<td><input type="text" size="70" name="eo" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Customer</th>
<th>ITAR Reg</th>
<th>Program Name</th>
</tr>
<tr>
<td><input type="text" name="cust" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="itar" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="prog" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<p><b>PN Work Order History</b><p>
<div id="lineitem" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="New Part Number" onclick="UpdateForm(this.value)"/>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
