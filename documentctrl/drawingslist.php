<html>
<head><h1>Part Number Drawing List</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Drawing";
var mainfile = "documentctrl";
var mainform = "gdrawingslist.php";
var lineform = "gldrawingslist.php";
var noteform = "";
var printform = "pspeclist.php";
var selectfield = "drawing";
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
$sql = "SELECT no FROM drawing ORDER BY no";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<form id="printform">
<input type="button" value="Print Drawing" onclick="PrintForm()"/>
</form>
<p/>
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
<td><input type="text" disabled name="status"/>
</tr>
</table>
<input type="hidden" name="limit"/>
<input type="hidden" id="id" name="id"/>
<table>
<tr>
<th>Part Number</th>
<th>Rev</th>
</tr>
<tr>
<td><input type="text" size="40" disabled name="no"/></td>
<td><input type="text" size="10" disabled name="rev"/></td>
</tr>
</table>
</form>
<div id="lineitem" style="padding : 4px; width : 100%; height : 100%; overflow:auto;"></div>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
