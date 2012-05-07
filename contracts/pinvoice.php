<html>
<head><link rel="stylesheet" type="text/css" href="../style/invoice.css" /></head>
<?php
//Print or Preview
$printyes = $_GET['print'];
if($printyes){?>
<body onload="window.print()">
<?php
$printsel = count($_GET);
}
else{
?>
<body>
<?php
$printsel = 2;
}
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Maximum rows allowed on one page
$pagehigh = 14;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//SQL variables for Main form
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date FROM customer INNER JOIN inv ON customer.id = inv.custno WHERE inv.id = '$id'";
$result = mysql_query($sql);
$num = mysql_num_fields($result);
$field = array();
for($i = 0; $i < $num; ++$i)
{
	$fields = mysql_fetch_field($result, $i);
	$field[$i] = $fields->name;
}
$row = mysql_fetch_array($result);
//Define Variables based on SQL Field names
for($i = 0; $i < $num; ++$i)
{
	$$field[$i] = $row[$i];
}
//Define Page counter
$k = 0;
//Loop through all the pages
for ($i = 1;$i < $printsel; $i++)
{
//Only Loop through if a page was requested
if($_GET[$i])
{
?>
<table class="top">
<tr>
<td colspan="4" class="head"><?php echo $crow['company'];?></td>
</tr>
<tr>
<td class="formname" width="25%"></td>
<td class="headaddress"><?php echo $crow['address']. "<br />Phone:" . $crow['phone'];?></td>
<td class="headaddress"><?php echo $crow['city'] . ", " . $crow['state']. " " . $crow['zip']. "<br />Fax:" . $crow['fax'];?></td>
<td class="head" width="25%"></td>
</tr>
<tr>
<td colspan="4" height="30"></td>
</tr>
</table>
<table width="100%"class="po">
<tr>
<th width="75%"></th>
<th class="pono">Date</th>
<th class="pono">Invoice</th>
</tr>
<tr>
<th width="75%"></th>
<td class="pono"><?php echo $date;?></td>
<td class="pono"><?php echo $no;?></td>
</tr>
<tr>
<td colspan="3" height="20"></td>
</tr>
</table>
<p/>
<table width="100%" class="address">
<tr>
<td width="10%" rowspan="4" style="text-align:center;">Bill <br/> To:</td>
<td><b><?php echo $bco;?></b></td>
<td width="10%" rowspan="4" style="text-align:center;">Ship <br/> To:</td>
<td><b><?php echo $sco;?></b></td>
</tr>
<tr>
<td><?php echo $bcont;?></td>
<td><?php echo $scont;?></td>
</tr>
<tr>
<td><?php echo $baddress;?></td>
<td><?php echo $saddress;?></td>
</tr>
<tr>
<td><?php echo $bcity.", ".$bstate." ".$bzip;?></td>
<td><?php echo $scity.", ".$sstate." ".$szip;?></td>
</tr>
<tr>
<td colspan="4" height="60"></td>
</tr>
</table>
<table width="100%" class="mid" border="1">
<tr>
<td height="32"><?php echo $terms;?></td>
<td width="25%"><?php echo $po;?></td>
<td><?php echo $shp;?></td>
<td><?php echo $nopgs;?></td>
<td><?php echo $wo;?></td>
<td width="20%" rowspan="2"><?php echo $_GET[$i];?></td>
</tr>
<tr>
<th height="28">Terms</th>
<th>P.O. Number</th>
<th>Shipped Via</th>
<th>Pkgs</th>
<th>W.O. No.</th>
</tr>
</table>
<table class="lines">
<?php

/****************************************************************
*								*
*			GET LINE ITEMS				*
*								*
****************************************************************/
if($lastline){
$lsql = "SELECT * FROM invline WHERE no = '$no' AND line > '$lastline' ORDER BY line";
$lastline = 0;
$k = 0;
}
else{
if($k < $pagehigh)
$lsql = "SELECT * FROM invline WHERE no = '$no' ORDER BY line";
}
$lresult = mysql_query($lsql);
//Total pages
$totalrec = mysql_num_rows($lresult);
//Get Field Names for VAriables
$lnum = mysql_num_fields($lresult);
$field = array();
for($j = 0; $j < $lnum; ++$j)
{

	$lfields = mysql_fetch_field($lresult, $j);
	$lfield[$j] = $lfields->name;
}
//Title headers for line items
?>
<tr>
<th class="headline" width="8%">Item</th>
<th colspan="2" style="border-bottom:1px solid black;" class="headline" width="14%">Quantity</th>
<th class="headline">Description</th>
<th class="headline" width="12%">Unit Cost</th>
<th class="headline" width="12%">Total</th>
</tr>
<tr style="border-bottom:1px solid black;">
<th class="headline" width="5%"></th>
<th class="headline" width="7%">Shpd</th>
<th class="headline" width="7%">Due</th>
<th class="headline"></th>
<th class="headline" width="12%"></th>
<th class="headline" width="12%"></th>
</tr>
<?php
//Chack page counter
//Loop through all line items
while($lrow = mysql_fetch_array($lresult))
{
if ($k < $pagehigh)
{
//Define Variables based on SQL Field names
for($j = 0; $j < $lnum; ++$j)
{
	$$lfield[$j] = $lrow[$j];
}
//Update Page Counter
$k = $k + 1;
//If there are more than 13 line items then alert the user
if ($k == $pagehigh - 1) 
$l = 1;
if ($k < $pagehigh)
{
?>
<tr>
<td class="line" width="5%"><?php echo $line;?></td>
<td class="line" width="5%"><?php echo $qty;?></td>
<td class="line" width="5%"><?php echo $due;?></td>
<td class="des" ><?php if($rev){echo $pn ." Rev. '".$rev . "'";}else{echo $pn;}?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Invoice": case "Invoice Copy": case "Invoice Copy 2": case "Accounting Copy": case "File Copy": case "Sales Copy": case "GSI": echo "$".$up; break;}?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Invoice": case "Invoice Copy": case "Invoice Copy 2": case "Accounting Copy": case "File Copy": case "Sales Copy": case "GSI": echo "$".$lp; break;}?></td>
<?php
}
else
{
if($lastline){}
else
$lastline = $line - 1;
}
}
}
if ($k < $pagehigh)
{
if($_GET[$i] == "Accounting Copy")
{
	//Get Accounting Price info
	$price = mysql_fetch_array(mysql_query("SELECT * FROM 
	(SELECT SUM(lp) AS pa FROM invline WHERE no='$no' AND type='PA')pa,
	(SELECT SUM(lp) AS sa FROM invline WHERE no='$no' AND type='SA')sa,
	(SELECT SUM(lp) AS ea FROM invline WHERE no='$no' AND type='EA' OR no = '$no' AND type = 'P1')ea,
	(SELECT SUM(lp) AS qa FROM invline WHERE no='$no' AND type='QA')qa,
	(SELECT shpchrg FROM inv WHERE no='$no')shp"));
	$pa = $price['pa'];
	$sa = $price['sa'];
	$ea = $price['ea'];
	$qa = $price['qa'];
	$shp = $price['shpchrg'];
	if(!$pa){$pa = 0;}
	if(!$sa){$sa = 0;}
	if(!$ea){$ea = 0;}
	if(!$qa){$qa = 0;}
	if(!$shp){$shp = 0;}
	?>
	</tr>
	<tr>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"><?php echo "PROD: " . $pa . " / ENG: " . $ea . " / QUAL: " . $qa . " / OTHER: " . $sa . " / SHP: " . $shp;?></td>
	<td class="line"></td>
	<td class="line"></td>
	</tr>
	<?php
}
else
{?>
	</tr>
	<tr>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	</tr>
<?php
}
}

/************************************************************************
*									*
*				GET PO NOTES				*
*									*
************************************************************************/

//Get Notes from MySQL
if($lastline){}
else
{
if($lastnote){
$nsql = "SELECT * FROM invnote WHERE no = '$no' AND id > '$lastnote'";
//Reset page counter to move to next requested form
$lastnote = 0;
$k = 0;}
else{
$nsql = "SELECT * FROM ponote WHERE no = '$no'";
}
}
$nresults = mysql_query($nsql);
//Page Counter
$totalrec = $totalrec + mysql_num_rows($nresults) + 3;
while($nrow = mysql_fetch_array($nresults))
{
//fill in notes
$note = $nrow['note'];
//Add one to page counter
$k = $k + 1;
if ($k == $pagehigh - 1) 
$l = 1;
//Check if counter has reached page limit
if($k < $pagehigh)
{
?>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td height="18" class="des"><?php echo $note;?></td>
<td class="line"></td>
<td class="line"></td>
</tr>
<?php
}
//Variable to see last note
else
{
//Only assign variable if this is the first loop where page counter is too high
if($lastline){}
else
{
if ($lastnote){}
else{
$lastnote = $nrow['id'] - 1;
}
}
}
}
/************************************************************************
*									*
*		ORDER TOTALS						*
*									*
************************************************************************/
if(!$lastline)
{
	if(!$lastnote){
	$foot = $pagehigh - 1 - $k;
	$k = 0;}
}
if($foot < 0)
$foot = 0;
$k = $k + 3;
if ($k == $pagehigh - 1) 
$l = 1;
if($k < $pagehigh)
{
	for ($foot = $foot; $foot; $foot--)
	{
	?>
	<tr>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	<td height="18" class="des"></td>
	<td class="line"></td>
	<td class="line"></td>
	</tr>
	<?php
	}
?>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line"><?php if($shpchrg) echo "Ship/Handling:";?></th>
<td class="line"><?php if($shpchrg){switch($_GET[$i]){case "Original Invoice": case "Invoice Copy": case "Invoice Copy 2": case "Accounting Copy": case "File Copy": case "Sales Copy": case "GSI": echo "$".$shpchrg; break;}}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line">Total:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Invoice": case "Invoice Copy": case "Invoice Copy 2": case "Accounting Copy": case "File Copy": case "Sales Copy": case "GSI": echo "$".$tot; break;}?></td>
</tr>
<?php
}
?>
</table>
</form>
</div>
<?php
//Page numbers
if(!$totalpages)
$totalpages = ceil($totalrec / $pagehigh);
if($k >= $pagehigh)
{
$pagecount = $pagecount + 1;
echo "Page ".$pagecount." of ".$totalpages;
}
else
{
	if($pagecount){
	$pagecount = $pagecount + 1;
	echo "Page ".$pagecount." of ".$totalpages;
	$pagecount = 0;
	}
}
?>

<div class="page-break"></div>
<?php
}
if($k < $pagehigh)
$k = 0;
else
$i = $i - 1;
}
if($l)
{
if($printyes)
{
?>
<SCRIPT LANGUAGE="javascript">
alert ("There are to many Lines. Please use 2 sheets per copy to print PO.");
</SCRIPT>
<?php
}
}
//close mysql connection
mysql_close($con);
?>
</body>
</html>
