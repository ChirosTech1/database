<html>
<head><h1>Purchasing Contacts</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript" src="../script/contact.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Contact";
var mainfile = "purchasing";
var mainform = "gcontact.php";
var lineform;
var noteform;
var table = "contact";
var field = "company";
var field2 = "contact";
var liveform = "livesearch.php";
//]]
</script>
<body onload="UpdateForm('cload');document.getElementsByName('menu')[0].focus();">
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define SQL for drop down list
$sql = "SELECT company FROM contact ORDER BY company";
$result = mysql_query($sql);
?>
<input type="button" value="Prev" onclick="UpdateForm(this.value);EditForm();"/>
<input type="button" value="Next" onclick="UpdateForm(this.value);EditForm();"/>
<input type="button" value="New" onclick="UpdateForm(this.value);EditForm(this.value);"/>
<input type="button" value="Edit" onclick="EditForm(this.value)"/>
<input type="button" value="Delete Contact" onclick="UpdateForm(this.value)"/></p>
<form id = "main">
<select name="menu" id="menu" onchange="UpdateForm('menu')">
<option value="default">Select a Company:</option>
<?php
while ($row = mysql_fetch_array($result))
{
?>
<option value = "<?php echo $row['company'];?>"><?php echo $row['company'];?></option>
<?php
}
?>
</select>
<input type="hidden" name="limit"/>
<input type="hidden" name="no"/>
<table>
<tr>
<th>Vendor ID</th>
<td><input type="text" name="id" id="id"/></td>
</tr>
<tr>
<th colspan="3">Company Name</th>
<th colspan="3">Contact Name</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="company" id="company" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="contact" id="contact" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="3">Address</th>
<th colspan="1">City</th>
<th colspan="1">State</th>
<th colspan="1">Zip</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="address" id="address" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="25" name="city" id="city" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" name="state" id="state" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="20" name="zip" id="zip" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="1">Phone #1</th>
<th colspan="1">Ext #1</th>
<th colspan="1">Phone #2</th>
<th colspan="1">Ext #2</th>
<th colspan="1">Fax</th>
</tr>
<tr>
<td colspan="1"><input type="text" size="20" name="phone" id="phone" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" name="ext" id="ext" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="20" name="phone2" id="phone2" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" name="ext2" id="ext2" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="30" name="fax" id="fax" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="2">Email Address</th>
<th colspan="2">Website</th>
<th colspan="2">Account Number</th>
</tr>
<tr>
<td colspan="2"><input type="text" size="33" name="email" id="email" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="33" name="web" id="web" onchange="UpdateForm('update')"/></td>
<td colspan="2"><input type="text" size="33" name="acct" id="acct" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<th colspan="3">Website Login</th>
<th colspan="3">Website Password</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="login" id="login" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="loginpass" id="loginpass" onchange="UpdateForm('update')"/></td>
</tr>
<tr><th>Notes:</th></tr>
<tr>
<td colspan="6"><input type="text" size="100" name="note" id="note" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<input type="button" value="Short PO's" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="Long PO's" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="Cert PO's" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="Debit Memo's" onclick="PurchaseOrders(this.value)"/></p>
<input type="button" value="New Short PO" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="New Long PO" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="New Cert PO" onclick="PurchaseOrders(this.value)"/>
<input type="button" value="New Debit Memo" onclick="PurchaseOrders(this.value)"/>

<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
