<html>
<head><h1>Hamby Tooling</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Tooling";
var mainfile = "engineering";
var mainform = "gtooling.php";
var lineform = "gltooling.php";
var noteform = "";
var printform = "ptooling.php";
var table = "tool";
var field = "no";
var field2 = "type";
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
//Define SQL for drop down list
$sql = "SELECT no FROM tool ORDER BY no";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="New Tooling" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete Tooling" onclick="UpdateForm(this.value)"/>
<p/>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Tool:</option>
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
<input type="hidden" name="id"/>
<input type="hidden" name="callno"/>
<table>
<tr>
<th>Tool No.</th>
<th>Part Number</th>
<th colspan="2">Customer</th>
</tr>
<tr>
<td><input type="text" name="no" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="partno" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="50" name="cust" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="4">Note</th>
</tr>
<tr>
<td colspan="4"><input type="text" size="95" name="note" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th>Date</th>
<th>Purge</th>
<th>Type</th>
<th>Initials</th>
</tr>
<tr>
<td><input type="text" name="date" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="purge" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="type" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="init" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<p><b>Tooling/PN Cross Reference</b><p>
<form id="lineitems">
<div id="lineitem" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
