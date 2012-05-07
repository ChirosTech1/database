<html>
<head><link rel="stylesheet" type="text/css" href="../style/longpo.css" /></head>
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
$pagehigh = 33;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//SQL variables for Main form
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$no = $_GET['no'];
$callno = $_GET['callno'];
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date, DATE_FORMAT(indate, '%m/%d/%Y') AS indate FROM po WHERE no = '$no' AND callno = '$callno'";
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
<td class="formname" width="25%">Purchase</td>
<th colspan="2" class="head">HAMBY CORPORATION</th>
<td class="head" width="25%"></td>
</tr>
<tr>
<td class="formname" width="25%">Order Form</td>
<td class="headaddress"><?php echo $crow['address']. "<br />Phone:" . $crow['phone'];?></td>
<td class="headaddress"><?php echo $crow['city'] . ", " . $crow['state']. " " . $crow['zip']. "<br />Fax:;" . $crow['fax'];?></td>
<td class="head" width="25%"><?php if($rev) echo "REVISION ".$rev;?></td>
</tr>
</table>
<table width="100%"class="po">
<tr>
<th width="75%"></th>
<th class="pono">Date</th>
<th class="pono">PO Number</th>
</tr>
<th width="75%"></th>
<td class="pono"><?php echo $date;?></td>
<td class="pono"><?php if($callno)echo $no." Call # ".$callno; else echo $no;?></td>
</table>
<p/>
<table width="100%" class="address">
<tr>
<th width="15%"></th>
<th><u>VENDOR</u></th>
<th><u>SHIP TO</u></th>
</tr>
<tr>
<td width="15%"></td>
<td><b><?php echo $supplier;?></b></td>
<td><b><?php echo $sname;?></b></td>
</tr>
<tr>
<td width="15%"></td>
<td><?php echo $address;?></td>
<td><?php echo $saddress;?></td>
</tr>
<tr>
<td width="15%"></td>
<td><?php echo $city.", ".$state." ".$zip;?></td>
<td><?php echo $scity.", ".$sstate." ".$szip;?></td>
</tr>
</table>
<p/>
<table width="100%" class="mid" border="1">
<tr>
<td><?php if($phone){echo "T: ".$phone;}?></td>
<td><?php echo $cont;?></td>
<td><?php echo $indate;?></td>
<td><?php echo $shp;?></td>
<td><?php echo $wo;?></td>
<td><?php echo $terms;?></td>
<td width="20%" rowspan="2"><?php echo $_GET[$i];?></td>
</tr>
<tr>
<td><?php if($phone){echo "T: ".$fax;}?></td>
<th>Contract Reference</th>
<th>In House Date</th>
<th>Ship Via</th>
<th>WO/RMA</th>
<th>Terms</th>
</tr>
</table>
<table class="lines">
<?php

/****************************************************************
*								*
*			GET PO LINE ITEMS			*
*								*
****************************************************************/
if($lastline){
$lsql = "SELECT * FROM poline WHERE no = '$no' AND callno = $callno AND line > '$lastline'";
$lastline = 0;
$k = 0;
}
else{
if($k < $pagehigh)
$lsql = "SELECT * FROM poline WHERE no = '$no' AND callno = $callno";
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
<th class="line" width="5%">Line</th>
<th class="line" width="5%">Qty</th>
<th class="line" width="5%">Units</th>
<th class="line">Description</th>
<th class="line" width="10%">Unit Price</th>
<th class="line" width="10%">Line Price</th>
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
<td class="line" width="5%"><?php echo $unit;?></td>
<td class="des" ><?php echo $pn ." ".$des;?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": echo "$".$up; break;}?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": echo "$".$lp; break;}?></td>
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
?>
</tr>
<tr>
<td class="line"> </td>
<td class="line"></td>
<td class="line"></td>
<td height="16" class="line"></td>
<td class="line"></td>
<td class="line"></td>
</tr>
<?php
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
$nsql = "SELECT * FROM ponote WHERE no = '$no' AND callno = '$callno' AND id > '$lastnote'";
//Reset page counter to move to next requested form
$lastnote = 0;
$k = 0;}
else{
$nsql = "SELECT * FROM ponote WHERE no = '$no' AND callno = '$callno'";
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
		<td height="16" class="des"><?php echo $note;?></td>
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
	if(!$lastnote)
	$k = 0;
}
$k = $k + 3;
if ($k == $pagehigh - 1) 
$l = 1;
if($k < $pagehigh)
{
?>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line">Subtotal:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": echo "$".$subtot; break;}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line">Tax:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": echo "$".$taxtot; break;}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"><?php if($agent){echo "Purchasing Agent: ".$agent;}?></td>
<th class="line">Total:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": echo "$".$tot; break;}?></td>
</tr>
<?php
}
?>
</table>
<?php
//Add Rows to place the footer at the bottom
echo $k;
for ($foot = $pagehigh - 1 - $k;$foot;$foot--)
{
?>
<p>This</p>
<?php
}
?>
<div id="footer">
<pre class="forminfo">Purchase Order		Hamby Corporation 27704 Avenue Scott, Valencia, CA 91355 (661) 257-1924, Fax (661) 257-1213</pre>
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
