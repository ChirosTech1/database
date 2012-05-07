<html>
<head><h1>Hamby Specifications</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Specification";
var mainfile = "documentctrl";
var mainform = "gspecs.php";
var lineform = "glspecs.php";
var noteform = "";
var printform = "pspecs.php";
var table = "spec";
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
$sql = "SELECT no FROM spec ORDER BY no";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="New Specification" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete Specification" onclick="UpdateForm(this.value)"/>
<p/>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Spec:</option>
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
<th>Spec No.</th>
<th>Rev</th>
<th>Type</th>
</tr>
<tr>
<td><input type="text" name="no" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="rev" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="type" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th>Change</th>
<th colspan="2">Note</th>
</tr>
<tr>
<td><input type="text" name="chg" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" name="note" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<p><b>Specification/PN Cross Reference</b><p>
<form id="lineitems">
<div id="lineitem" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
