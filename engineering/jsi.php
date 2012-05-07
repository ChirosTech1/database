<html>
<head><h1>Job Summary and Inspection</h1></head>
<script type="text/javascript" src="../script/jsiajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "JSI";
var mainfile = "engineering";
var mainform = "gjsi.php";
var lineform = "gljsi.php";
var noteform = "gnjsi.php";
var printform = "pjsi.php";
var field = "no";
var field2 = "des";
var liveform = "livesearch.php";
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
//Define SQL for drop down list
	$sql = "SELECT * FROM jsi ORDER BY no DESC";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete JSI" onclick="UpdateForm(this.value)"/>
<input type="button" value="New JSI" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/></p>

<form id = "main">
<select name="menu" onchange="UpdateForm('menu')">
<option value = "default">Select a JSI PN:</option>
<?php
while ($row = mysql_fetch_array($result))
{
?>
<option value = "<?php echo $row['id'];?>"><?php echo $row['no'];?></option>
<?php
}
?>
</select>
<p></p>
<input type="hidden" name="limit"/>
<input type="hidden" name="id" id="id"/>
<table>
<tr>
<th>Part Number</th>
<td><input type="text" name="no" id="no" onchange="UpdateForm('update')"/></td>
<th>Remarks</th>
<td><input type="text" name="rmks" onchange="UpdateForm('update')"/></td>
<th>Parts Per Panel</th>
<td><input type="text" name="ppp" onchange="UpdateForm('update')"/></td>
<th>Final</th>
<td><input type="text" name="final" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<div id="lineitem" style="padding : 4px; width : 1000px; height : 1000px; overflow:auto;"></div>
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
</tr>
<tr>
<td><input type="checkbox" checked="checked" id="1" value="Work Order"/></td>
</tr>
</table>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
