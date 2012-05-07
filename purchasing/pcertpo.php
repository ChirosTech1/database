<html>
<head><link rel="stylesheet" type="text/css" href="../style/certpo.css" /></head>
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
$pagehigh = 30;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//SQL variables for Main form
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date, DATE_FORMAT(inhousedate, '%m/%d/%Y') AS inhousedate FROM po WHERE id = '$id'";
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
<table class="top" width="100%">
<tr>
<td class="formname" width="30%"></td>
<td class="head"><?php echo $crow['company'];?></td>
<th width = "10%"></th>
<th class="pono" width="20%"><?php if($rev) echo $no." Rev ".$rev;else echo $no;?></th>
</tr>
<tr>
<td class="formname" width="30%"></td>
<td class="headaddress"><?php echo $crow['address']. "<br />" . $crow['city'].", ". $crow['state'] . " " . $crow['zip'];?></td>
<td width = "10%"></td>
<td class="poinfo" width="20%">THE ABOVE NUMBER MUST APPEAR ON ALL DOCUMENTS RELATIVE TO THIS ORDER</td>
</tr>
<tr>
<th width="30%"></th>
<td class="phone"><?php echo $crow['phone']." Fax ".$crow['fax'];?></td>
<td width="10%">Date: </td>
<td class="date" width="20%"><?php echo $date;?></td>
</tr>
</table>
</br>
</br>
<table width="100%">
<tr>
<td height="10" colspan="5"></td>
</tr>
<tr>
<td class="shipto" width="5%">To:</td>
<td class="addresses" width="30%"><b><?php echo $supplier;?></b></td>
<td class="shipto" width="20%">Ship To:</td>
<td class="addresses" width="30%"><b><?php echo $sname;?></b></td>
<td width="5%"></td>
</tr>
<tr>
<td width="5%"></td>
<td class="addresses" width="30%"><?php echo $address;?></td>
<td width="20%"></td>
<td class="addresses" width="30%"><?php echo $saddress;?></td>
<td width="5%"></td>
</tr>
<tr>
<td width="5%"></td>
<td class="addresses" width="30%"><?php echo $city.", ".$state." ".$zip;?></td>
<td width="20%"></td>
<td class="addresses" width="30%"><?php echo $scity.", ".$sstate." ".$szip;?></td>
<td width="5%"></td>
</tr>
<tr>
<td height="10" colspan="5"></td>
</tr>
</table>
<p/>
<table width="100%" class="mid" border="1">
<tr>
<th width="25%">Phone</th>
<th width="25%">Contact</th>
<th width="25%">Ship Via</th>
<th width="25%">Contract Number</th>
</tr>
<tr>
<td width="25%"><?php echo $phone;?></td>
<td width="25%"><?php echo $conf;?></td>
<td width="25%"><?php echo $shp;?></td>
<td width="25%"><?php echo $cont;?></td>
</tr>
<tr>
<th width="25%">Fax</th>
<th width="25%">Terms</th>
<th width="25%">FOB Point</th>
<th width="25%">DORating</th>
</tr>
<tr>
<td width="25%"><?php echo $fax;?></td>
<td width="25%"><?php echo $terms;?></td>
<td width="25%"><?php echo $fob;?></td>
<td width="25%"><?php echo $dorate;?></td>
</tr>
</table>
<?php

/****************************************************************
*								*
*			GET PO LINE ITEMS			*
*								*
****************************************************************/
if($lastline){
$lsql = "SELECT * FROM poline WHERE no = '$no' AND callno = $callno AND line > '$lastline' ORDER BY line";
$lastline = 0;
$k = 0;
}
else{
if($k < $pagehigh)
$lsql = "SELECT *,DATE_FORMAT(indate, '%m/%d/%Y') AS indate  FROM poline WHERE no = '$no' AND callno = '$callno' ORDER BY line";
}
$lresult = mysql_query($lsql);
//Total pages
$totalrec = mysql_num_rows($lresult) * 3;
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
<table class="lines">
<tr>
<td height="10" colspan="6"></td>
</tr>
<tr>
<th class="line" width="5%">Line</th>
<th class="line" width="6%">Qty</th>
<th class="line" width="6%">Units</th>
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
$k = $k + 3;
//If there are more than 13 line items then alert the user
if ($k == $pagehigh - 1) 
$l = 1;
if ($k < $pagehigh)
{
?>
<tr>
<td class="line" width="5%"><?php echo $line;?></td>
<td class="line" width="6%"><?php echo $qty;?></td>
<td class="line" width="6%"><?php echo $unit;?></td>
<td class="des" ><?php echo $pn ." ".$des;?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": case "Acknowledgement Copy": echo "$".$up; break;}?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": case "Acknowledgement Copy": echo "$".$lp; break;}?></td>
</tr>
<tr>
<td class="line" width="5%"></td>
<td class="line" width="6%"></td>
<td class="line" width="6%"></td>
<td class="des"><?php if($indate){echo "DELIVERY SCHEDULED FOR " . $indate;} if($early) echo " OR SOONER";?></td>
<td class="line" width="10%"></td>
<td class="line" width="10%"></td>
</tr>
<tr>
<td class="line" width="5%"></td>
<td class="line" width="6%"><?php if($_GET[$i] == 'Planning Copy'){if($wo) echo "WO: ";}?></td>
<td class="line" width="6%"><?php if($_GET[$i] == 'Planning Copy'){if($wo) echo $wo; else echo "Stock";}?></td>
<td class="des"><?php if($cert) echo "CERT TO: ".$cert; if($qal) echo " QAL: ".$qal; if($rec && $_GET[$i] == 'Receiving Copy' || $_GET[$i] == 'QC Copy') echo " Rec Insp: ".$rec;?></td>
<td class="line" width="10%"></td>
<td class="line" width="10%"></td>
</tr>
<tr>
<td></td>
</tr>
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
<tr>
<td class="line"> </td>
<td class="line"></td>
<td class="line"></td>
<td height="16" class="line"></td>
<td class="line"></td>
<td class="line"></td>
</tr>
</table>
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
$totalrec = $totalrec + mysql_num_rows($nresults) + 4;
//Spacer Row between Lines and Notes
?>
<?php
$k = $k + 3;
if($k < $pagehigh)
{
?>
<table class="note">
<tr>
<td colspan="6" height="4" class="notehead"></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="total">Total PO Value:</th>
<td class="line"></td>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Purchasing Copy": case "Accounting Copy": case "File Copy": case "Acknowledgement Copy": echo "$".$tot; break;}?></td>
</tr>
<tr>
<td colspan="6" height="18" class="notehead"><u>P.O. Notes</u></td>
</tr>
<tr>
<td colspan="6" height="4" class="notehead"></td>
</tr>
<?php
}
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
<td colspan="6" height="18" class="notes"><?php echo $note;?></td>
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
$k = $k + 1;
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
<td colspan="5" height="18" class="confirm">CONFIRMING PURCHASE ORDER DO NOT DUPLICATE</td>
</tr>
<?php
}
?>
<tr>
<td colspan="5" height="4"></td>
</tr>
</table>
<table class="bot">
<tr>
<td class="poterms" width="40%">2 COPIES OF CERTIFICATIONS TO APPLICABLE SPECS. ARE REQUIRED WITH EACH SHIPMENT SEND 2 COPIES OF INVOICE WITH ORIGINAL BILL OF LADING</td>
<td class="agenthead" width="20%">Purchasing Agent:</td>
<td class="agent" width="25%"><u><?php echo $agent;?></u></td>
<td class="qchead" width="10%">QC<br/>Approval:</td>
<td class="qc" width="5%"><?php if($ordered) echo "HC";?><br/><?php if($ordered) echo $ordered;?></td>
</tr>
</table>
</form>
<table width="100%">
<tr>
<td class="forminfo" width="30%">ADM035 2/11</td>
<th class="formtype"><?php echo $_GET[$i];?></th>
<?php
//Page numbers
if(!$totalpages)
$totalpages = ceil($totalrec / $pagehigh);
if($k >= $pagehigh)
{
$pagecount = $pagecount + 1;
$pageno = "Page ".$pagecount." of ".$totalpages;
}
else
{
	if($pagecount){
	$pagecount = $pagecount + 1;
	$pageno =  "Page ".$pagecount." of ".$totalpages;
	$pagecount = 0;
	}
}
?>
<td class="pageno" width="30%"><?php echo $pageno;?></td>
</tr>
</table>
<div class="page-break"></div>
<?php
}
if($k < $pagehigh)
$k = 0;
else
$i = $i - 1;
}
if($l && $printyes)
{
?>
<SCRIPT LANGUAGE="javascript">
alert ("There are to many Lines. Please use 2 sheets per copy to print PO.");
</SCRIPT>
<?php
}
//close mysql connection
mysql_close($con);
?>
</body>
</html>
