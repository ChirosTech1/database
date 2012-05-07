<html>
<head><h1>Hamby Material History</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "History";
var mainfile = "purchasing";
var mainform = "ghistory.php";
var lineform = "glhistory.php";
var noteform = "";
var printform = "phistory.php";
var table = "poline";
var field = "mn";
var field2 = "pn";
var field3 = "des";
var liveform = "historylivesearch.php";
function Requisition(q)
{
	var id = document.getElementsByName('no')[0].value;
	window.open("req.php?linkvalue="+id+"&q="+q);
}
//]]
</script>
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define Variables
?>
<body>
<form id = "lineitems">
<input type="hidden" name="limit"/>
<input type="hidden" name="id"/>
<input type="hidden" name="no"/>
<table>
<tr>
<th>Search</th>
<th>Manufacturer PN</th>
<th>PN Description</th>
</tr>
<tr>
<td><input type="text" name="mn" size="20" onkeyup="LiveSearch(this)"/></td>
<td><input type="text" name="pn"/></td>
<td><input type="text" size="50" name="des"/></td>
</tr>
</table>
<div style="background: white;position:absolute;top:140;left:10;overflow:auto;" id="search" name="search"></div>
</form>
<p><b>Material Order History</b><p>
<div id="lineitem" style="padding : 4px; width : 100%; height : 100%; overflow:auto;"></div>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
