<html>
<head><h1>Hamby Materials</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Material";
var mainfile = "materialctrl";
var mainform = "gmaterial.php";
var lineform = "glmaterial.php";
var noteform = "gnmaterial.php";
var printform = "pmaterial.php";
var table = "material";
var field = "pn";
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
$sql = "SELECT no FROM material ORDER BY no";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="New Material" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete Material" onclick="UpdateForm(this.value)"/>
<p/>
<form id = "main">
<table>
<tr>
<td>
<select name="menu" id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Material:</option>
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
<th>Material No.</th>
<th>Part Number</th>
<th>Description</th>
</tr>
<tr>
<td><input type="text" name="no" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="pn" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="50" name="des" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Manufacturer</th>
<th>Specification</th>
<th>Type</th>
</tr>
<tr>
<td><input type="text" name="man" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="spec" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="type" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Stock</th>
<th>Obsolete</th>
<th>QAL</th>
<th>Receiving Insp.</th>
<th>Inspection Procedure</th>
</tr>
<tr>
<td><input type="text" name="stk" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="obs" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="qal" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="rec" onchange="UpdateForm('update')"/></td>
<td><input type="text" name="insp" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<p><b>Material Order History</b><p>
<form id="lineitems">
<div id="lineitem" style="padding : 4px; width : 1200px; height : 150px; overflow:auto;"></div>
</form>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
