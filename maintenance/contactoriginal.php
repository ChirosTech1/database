<html>
<head><h1>Maintenance Contacts</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Contact";
var mainfile = "maintenance";
var mainform = "gcontact.php";
var lineform = "glmaintpo.php";
var noteform = "gnmaintpo.php";
var printform = "pmaintpo.php";
//]]
</script>
<body onload="UpdateForm('load')">
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define SQL for drop down list
$sql = "SELECT company FROM contact ORDER BY company";
$result = mysql_query($sql);
?>
<form id = "main" disabled>
<select id = "menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Company:</option>
<?php
while ($row = mysql_fetch_array($result))
{
?>
<option value = "<?php echo $row['company'];?>"><?php echo $row['company'];?></option>
<?php
}
?>
</select>
<input type="hidden" id="limit" value=""/>
<table>
<tr>
<th>Vendor ID</th>
<td><input type="text" id="id" readonly="readonly"/></td>
</tr>
<tr>
<th colspan="3">Company Name</th>
<th colspan="3">Contact Name</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" readonly="readonly" id="company" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" readonly  id="contact" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="3">Address</th>
<th colspan="1">City</th>
<th colspan="1">State</th>
<th colspan="1">Zip</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" id="address" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="25" id="city" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" id="state" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="20" id="zip" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="1">Phone #1</th>
<th colspan="1">Ext #1</th>
<th colspan="1">Phone #2</th>
<th colspan="1">Ext #2</th>
<th colspan="1">Fax</th>
</tr>
<tr>
<td colspan="1"><input type="text" size="20" id="phone" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" id="ext" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="20" id="phone2" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" id="ext2" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="30" id="fax" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="2">Email Address</th>
<th colspan="2">Website</th>
<th colspan="2">Account Number</th>
</tr>
<tr>
<td colspan="2"><input type="text" size="33" id="email" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="33" id="web" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="33" id="acct" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="3">Website Login</th>
<th colspan="3">Website Password</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" id="login" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" id="loginpass" onchange="UpdateForm('update')"/></td>
</tr>
<tr><th>Notes:</th></tr>
<tr>
<td colspan="6"><input type="text" size="100" id="note" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="New" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete" onclick="UpdateForm(this.value)"/>
<input type="text" size="10" id="edit" onchange="EditForm()"/></p>
<input type="button" value="Maintenance PO's" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="New Maintenance PO" onclick="PurchaseOrders(this.value)"/>

<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
