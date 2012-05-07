<html>
<head><h1>Contracts Customers</h1></head>
<script type="text/javascript" src="../script/ajax.js"></script>
<script type="text/javascript" src="../script/customer.js"></script>
<script type="text/javascript">
//<![CDATA{
var formtype = "Customer";
var mainfile = "contracts";
var mainform = "gcustomer.php";
var printform = "pwo.php";
//]]
</script>
<body onload="UpdateForm('load');EditForm();document.getElementsByName('menu')[0].focus();">
<?php
//sql connection
require_once('/home/hamby/auth/dbinfo.php');
//Define SQL for drop down list
$sql = "SELECT * FROM customer ORDER BY co";
$result = mysql_query($sql);
?>
<form id = "main">
<select name="menu" onchange="UpdateForm('menu')">
<option value = "default">Select a Company:</option>
<?php
while ($row = mysql_fetch_array($result))
{
?>
<option value = "<?php echo $row['id'];?>"><?php echo $row['co'] . " " . $row['scity'];?></option>
<?php
}
?>
</select>
<input type="hidden" name="limit" value=""/>
<input type="hidden" name="no" value=""/>
<input type="hidden" name="callno" value=""/>
<table>
<tr>
<th>Customer ID</th>
<td><input type="text" name="id" disabled/></td>
<th>Company Name</th>
<td><input type="text" name="co"/></td>
</tr>
</table>
<table>
<tr>
<th colspan="3">Billing Address</th>
<th colspan="3">Shipping Address</th>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="bco" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="sco" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="bcont" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="scont" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td colspan="3"><input type="text" size="50" name="baddress" onchange="UpdateForm('update')"/></td>
<td colspan="3"><input type="text" size="50" name="saddress" onchange="UpdateForm('update')"/></td>
</tr>
<tr>
<td colspan="1"><input type="text" size="25" name="bcity" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" name="bstate" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="10" name="bzip" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="25" name="scity" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="5" name="sstate" onchange="UpdateForm('update')"/></td>
<td colspan="1"><input type="text" size="10" name="szip" onchange="UpdateForm('update')"/></td>
</tr>
</table>
<table>
<tr>
<th>Phone</th>
<th>Phone 2</th>
<th>Fax</th>
<th>Shipping Acct</th>
</tr>
<tr>
<td><input type="text" size="20" name="phone" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="20" name="phone2" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="20" name="fax" onchange="UpdateForm('update')"/></td>
<td><input type="text" size="20" name="sacct" onchange="UpdateForm('update')"/></td>
</tr>
</table>
</form>
<input type="button" value="Prev" onclick="UpdateForm(this.value)"/>
<input type="button" value="Next" onclick="UpdateForm(this.value)"/>
<input type="button" value="New" onclick="UpdateForm(this.value)"/>
<input type="button" value="Delete" onclick="UpdateForm(this.value)"/>
<input type="button" value="Edit" onClick="EditForm(this.value)"/></p>
<input type="button" value="WO's" onclick="WorkOrders(this.value)"/></p>
<input type="button" value="New WO" onclick="WorkOrders(this.value)"/>

<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
