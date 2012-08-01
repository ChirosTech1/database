<html>
<head><link rel="stylesheet" type="text/css" href="../style/jsi.css" /></head>
<?php
//Print or Preview
$printyes = $_GET['print'];
if($printyes){?>
<body onload="window.print()">
<?php
}
else{
?>
<body>
<?php
}
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Maximum rows allowed on one page
$pagehigh = 29;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//Define Variables
$table = 'jsi';
$jtable = 'jsijob';
$mtable = 'jsimat';
$stable = 'jsispec';
//SQL variables for Main form
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT * FROM $table WHERE id = '$id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
//Define PN Variable to referenece in sql to get jobs,mats,specs,etc
$no = $row['no'];
//Define Variables based on SQL Field names
for($i = 0; $i < $num; ++$i)
{
	$$field[$i] = $row[$i];
}
//Define Page counter 
$pagecounter = 0;
?>



<?php

/********************************************************
*							*
*	2nd Page Header Section Defined as Variable	*
*	Make sure to add $pagecounter++ after use	*
*							*
********************************************************/
$header = '
</table>
</div>
<div class="add_page_header">
<table class="add_page_header">
	<tr>
		<th>JOB SUMMARY AND INSPECTION</th>
	</tr>
</table>
</div>
';
//Define How many Rows this Header takes
$header_rows = 1;
/********************************************************
*							*
*	Blank Rows to Add the bottom of Page		*
*							*
********************************************************/
$blankrows = '
	<tr>
		<td class="blank_rows"></td>
	</tr>
';

/********************************************************
*							*
*		Approval Line				*
*							*
********************************************************/
$approval = '
	<div class="approval">
		</p>
		PREP BY_______/__ APRLS_______/__ : ENG_______/__ QC_______/__ PROD_______/__
	</div>
';
?>



<!--
---------------------------------------------------------
.							.
.		HEADER SECTION				.
.							.
---------------------------------------------------------
--!>
<div class="page_header">
<table class="page_header">
	<tr class="page_header">
		<td class="page_header">PTH#______________</td>
		<th class="page_header" rowspan="2">JOB SUMMARY AND INSPECTION</th>
		<td class="page_header">JSI#___________________</td>
	</tr><?php $pagecounter++;?>
	<tr class="page_header">
		<td class="page_header">Final Assembly: <?php echo $row['final'];?></td>
		<td class="page_header">Page 1 of ---</td>
	</tr><?php $pagecounter++;?>
</table>
<table class="page_header">
	<tr class="page_header">
		<td class="page_header">Date Due:______________</td>
		<td class="page_header">Qty Due:_____</td>
		<td class="page_header">Qty:____</td>
		<td class="page_header">SN:______________</td>
		<td class="page_header">WO:______________</td>
		<td class="page_header">Cust:_______________</td>
	</tr><?php $pagecounter++;?>
</table>
<table class="page_header">
	<tr class="page_header">
		<td class="page_header">Part Number: <?php echo $row['no'];?></td>
		<td class="page_header">Remarks:  <?php echo $row['rmks'];?></td>
		<td class="page_header">Run__Panels @  <?php echo $row['ppp'];?> Parts/Panel</td>
	</tr><?php $pagecounter++;?>
</table>
</div>
<!--
---------------------------------------------------------
.							.
.		Procedure Lines				.
.							.
---------------------------------------------------------
--!>
<?php
$procedure_header = '
<div class="procedure">
<table class="procedure">
	<tr>
		<th rowspan="2" class="procedure">##</th>
		<th rowspan="2" class="procedure">Job Description</th>
		<th rowspan="2" class="procedure">Materials and Specs</th>
		<th rowspan="2" class="procedure">QC</th>
		<th rowspan="2" class="procedure">Date</th>
		<th rowspan="2" class="procedure">S</th>
		<th colspan="2" class="procedure">Times</th>
		<th rowspan="2" class="procedure">Emp</th>
		<th colspan="2" class="procedure">Qty</th>
		<th rowspan="2" class="procedure">QC</th>
		<th rowspan="2" class="procedure">Qual Spec</th>
		<th rowspan="2" class="procedure">ST</th>
		<th rowspan="2" class="procedure">Rejs</th>
		<th rowspan="2" class="procedure">ST</th>
		<th rowspan="2" class="procedure">Date</th>
	</tr>
	<tr>
		<th class="procedure">In</th>
		<th class="procedure">Out</th>
		<th class="procedure">I</th>
		<th class="procedure">O</th>
	</tr>
';
$procedure_header_rows = 2;
echo $procedure_header; $pagecounter = $pagecounter + $procedure_header_rows;
/************************************************
*						*
*	Procedure Job Lines			*
*						*
************************************************/
//Define How many extra rows to add at the end
$jextra = 5;

//Get Job Descriptions based on Part Number
$jsql = "SELECT * FROM $jtable WHERE pn = '$no' ORDER BY no";
$jresult = mysql_query($jsql);

//Set Max row widths
$jobmaxrow = 27;
$matspecmaxrow = 15;

//Loop through all Jobs
for($j = 1;$jrow = mysql_fetch_array($jresult);$j++)
{
	//Check String Length of Job Description/Matspec and increase Page counter if needed
	if(strlen($crow['job']) > $jobmaxrow)
		$pagecounter++;
	if(strlen($crow['matspec']) > $matspecmaxrow)
		$pagecounter++;
	//Check for skipped Job numbers
	if($jrow['no'] == $j)
	{
?>
	<tr>
		<td class="procedure"><?php echo $jrow['no'];?></td>
		<td class="procedure"><?php echo $jrow['job'];?></td>
		<td class="procedure"><?php echo $jrow['matspec'];?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr>
<?php 
		//Add 1 to pagecounter for row added
		$pagecounter++; 
		//Check to see if New page is required
		if($pagecounter >= $pagehigh)
		{
			//If new page is required add new page
			$pagecounter = 0;
			echo $header;$pagecounter = $pagecounter + $header_rows;
			//Add Header rows for Job Descriptions
			echo $procedure_header;$pagecounter = $pagecounter + $procedure_header_rows;
		}
?>
<?php
	}
	//Add Blank Spaces for Skipped Job numbers
	else
	{
	//Keep adding blank numbers until a valid job is found
	while($jrow['no'] > $j)
	{
?>
	<tr>
		<td class="procedure"><?php echo $j;?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr>
<?php 
		//Add 1 to pagecounter for row added
		$pagecounter++; 
		//Check to see if New page is required
		if($pagecounter >= $pagehigh)
		{
			//If new page is required add new page
			$pagecounter = 0;
			echo $header;$pagecounter = $pagecounter + $header_rows;
			//Add Header rows for Job Descriptions
			echo $procedure_header;$pagecounter = $pagecounter + $procedure_header_rows;
		}
?>

<?php	
		//Add +1 to $j to increment
		$j++;
	}
	//Add Valid Job Number after blanks are added
?>
	<tr>
		<td class="procedure"><?php echo $jrow['no'];?></td>
		<td class="procedure"><?php echo $jrow['job'];?></td>
		<td class="procedure"><?php echo $jrow['matspec'];?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr>
<?php 
		//Add 1 to pagecounter for row added
		$pagecounter++; 
		//Check to see if New page is required
		if($pagecounter >= $pagehigh)
		{
			//If new page is required add new page
			$pagecounter = 0;
			echo $header;$pagecounter = $header_rows;
			//Add Header rows for Job Descriptions
			echo $procedure_header;$pagecounter = $pagecounter + $procedure_header_rows;
		}
?>
<?php
	}
}
//Check to see if there is enough room to add extra rows
if($pagecounter + $jextra >= $pagehigh)
{
	$jextra = $jextra - (($pagecounter + $jextra) - $pagehigh);
}
//Add $jextra extra rows to allow space
for($e = 0;$e!=$jextra;$e++)
{
?>
	<tr>
		<td class="procedure"><?php echo $j;?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr><?php $pagecounter++;?>
<?php
		//increment value of $j for blank Job Number values
		$j++;
}
?>
</table>
</div>
<?php
/************************************************
*						*
*		Material List			*
*						*
************************************************/
$material_header = '
<div class="material">
<table class="material">
	<tr>
		<th class="material" colspan="7">Material List</th>
		<th class="material" width="200px"></th>
	</tr>
	<tr>
		<th class="material">##</th>
		<th class="material">Qty</th>
		<th class="material">PN</th>
		<th class="material" colspan="2">Description</th>
		<th class="material">Specification</th>
		<th class="material" width="70">Certs</th>
		<th class="material"></th>
	</tr>
';
$material_header_rows = 2;
/************************************************
*						*
*	Material List Lines			*
*						*	
************************************************/

//Define How many extra rows to add at the end
$mextra = 4;

//Get Materials based on Part Number
$msql = "SELECT $mtable.*, material.pn AS matpn, material.des AS matdes, material.type AS mattype, material.spec AS matspec FROM $mtable INNER JOIN material ON $mtable.mat = material.no WHERE $mtable.pn = '$no' ORDER BY no";


//Set Max character row widths
$qtymaxrow = 9;
$matmaxrow = 45;
$matpnmaxrow = 45;
$matdesmaxrow = 45;
$matspecmaxrow = 45;
//Calculate How many rows and check for needed page break
$cresult = mysql_query($msql);
while($crow = mysql_fetch_array($cresult))
{
	//Check String Length of columns and increase Page counter if needed
	if(strlen($crow['qty']) > $qtymaxrow)
		$pagecounter++;
	if(strlen($crow['mat']) > $matmaxrow)
		$pagecounter++;
	if(strlen($crow['matpn']) > $matpnmaxrow)
		$pagecounter++;
	if(strlen($crow['matdes']) > $matdesmaxrow)
		$pagecounter++;
	if(strlen($crow['matspec']) > $matspecmaxrow)
		$pagecounter++;
	//Get last number
	$mlast = $crow['no'];
}
//Add Header and Extra rows to get final row count of Materials
$mlast = $mlast + 2 + $mextra;


//Check to see if there is enough room on page to print Material list
if($pagecounter + $mlast > $pagehigh)
{
	//Add Filler rows at bottom of page
	$howmanyrows = $pagehigh - $pagecounter;
?>
	<div class="blank_rows">
	<table class="blank_rows">
<?php
	for ($b = 0;$howmanyrows>$b;$b++)
	{
		echo $blankrows;
	}
?>
	</table>
	</div>
<?php
	//Add New Page Header row (this includes a page break)
	echo $header;$pagecounter = $pagecounter + $header_rows;
	echo $material_header;$pagecounter = $pagecounter + $material_header_rows;
}
else
{
	echo $material_header;$pagecounter = $pagecounter + $material_header_rows;
}


//Print out Material List
$mresult = mysql_query($msql);
for($m = 1;$mrow = mysql_fetch_array($mresult);$m++)
{
	//Check for skipped Material numbers
	if($mrow['no'] == $m)
	{
?>
	<tr>
		<td class="material"><?php echo $mrow['no'];?></td>
		<td class="material"><?php echo $mrow['qty'] . " " . $mrow['unit'];?></td>
		<td class="material"><?php echo $mrow['mat'];?></td>
		<td class="material"><?php echo $mrow['matpn'] . " " . $mrow['mattype'];?></td>
		<td class="material"><?php echo $mrow['matdes'];?></td>
		<td class="material"><?php echo $mrow['matspec'];?></td>
		<td class="material"></td>
		<td class="material"></td>
	</tr>
<?php 
		//Add 1 to pagecounter for row added
		$pagecounter++; 
		//Check to see if New page is required
		if($pagecounter >= $pagehigh)
		{
			//If new page is required add new page
			$pagecounter = 0;
			echo $header;$pagecounter = $pagecounter + $header_rows;
			//Add Header rows for Material Descriptions
			echo $material_header;$pagecounter = $pagecounter + $material_header_rows;
		}
?>
<?php
	}
	//Add Blank Spaces for Skipped Material numbers
	else
	{
		//Keep adding blank numbers until a valid job is found
		for($m = $m;$mrow['no'] > $m;$m++)
		{
?>
		<tr>
			<td class="material"><?php echo $m;?></td>
			<td class="material"></td>
			<td class="material"></td>
			<td class="material"></td>
			<td class="material"></td>
			<td class="material"></td>
			<td class="material"></td>
			<td class="material"></td>
		</tr>
<?php 
			//Add 1 to pagecounter for row added
			$pagecounter++; 
			//Check to see if New page is required
			if($pagecounter >= $pagehigh)
			{
				//If new page is required add new page
				$pagecounter = 0;
				echo $header;$pagecounter = $pagecounter + $header_rows;
				//Add Header rows for Material Descriptions
				echo $material_header;$pagecounter = $pagecounter + $material_header_rows;
			}
?>
<?php	
			//Add +1 to $m to increment
		//	$m++;
		}
		//Add Valid Material Number after blanks are added
?>
		<tr>
			<td class="material"><?php echo $mrow['no'];?></td>
			<td class="material"><?php echo $mrow['qty'] . " " . $mrow['unit'];?></td>
			<td class="material"><?php echo $mrow['mat'];?></td>
			<td class="material"><?php echo $mrow['matpn'] . " " . $mrow['mattype'];?></td>
			<td class="material"><?php echo $mrow['matdes'];?></td>
			<td class="material"><?php echo $mrow['matspec'];?></td>
			<td class="material"></td>
			<td class="material"></td>
		</tr>
<?php 
			//Add 1 to pagecounter for row added
			$pagecounter++; 
			//Check to see if New page is required
			if($pagecounter >= $pagehigh)
			{
				//If new page is required add new page
				$pagecounter = 0;
				echo $header;$pagecounter = $pagecounter + $header_rows;
				//Add Header rows for Material Descriptions
				echo $material_header;$pagecounter = $pagecounter + $material_header_rows;
			}
?>

<?php

	}
}
//Check to see if there is enough room to add extra rows
if($pagecounter + $mextra >= $pagehigh)
{
	$mextra = $mextra - (($pagecounter + $mextra) - $pagehigh);
}

//Add $mextra extra rows to allow space
for($e = 0;$e!=$mextra;$e++)
{
?>
	<tr>
		<td class="material"><?php echo $m;?></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
	</tr><?php $pagecounter++;?>

<?php
	//increment value of $j for blank Job Number values
	$m++;
}
?>
</table>
</div>


<!-----------------------------------------------
.						.
.	Blue Prints and Specifications		.
.						.
----------------------------------------------!>
<div class="specs">
<table class="specs">
<!-- HEADER SECTION --!>
	<tr>
		<th class="specs" colspan="9">Blue Prints and Specifications</th>
	</tr><?php $pagecounter++;?>
	<tr>
		<th class="specs"></th>
		<th class="specs">B/P OR Spec</th>
		<th class="specs">Description</th>
		<th class="specs"></th>
		<th class="specs">B/P OR Spec</th>
		<th class="specs">Description</th>
		<th class="specs"></th>
		<th class="specs">B/P OR Spec</th>
		<th class="specs">Description</th>
	</tr><?php $pagecounter++;?>
<?php

/************************************************
*						*
*	B/P and Specs List			*
*						*
************************************************/
$ssql = "SELECT *, LENGTH(let) AS len FROM $stable WHERE pn = '$no' ORDER BY len, let";
$sresult = mysql_query($ssql);
//Specifiy Letter variable to use as filler for blank spots
$let = "A";
//Loop through Specification Letters 3 at a time
for($s = 0; $srow = mysql_fetch_array($sresult); $s++)
{
	//Check the letter against the letter counter to check for blanks
	if($srow['let'] == $let)
	{
		//Fill in data 3 rows at a time
		if(fmod($s,3) != 0)
		{
?>
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
		//End 3 column and start new column
		else
		{
			if($s != 0)
			{
?>
				</tr><?php $pagecounter++;?>
<?php
			}
?>
		<tr>	
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
	}
	//Fill In Blank Letters until Valid Letter is found
	else
	{
		//Continue to fill in blank rows until valid letter is found
		while($srow['let'] > $let || strlen($srow['let']) > strlen($let))
		{
			//Fill in data 3 rows at a time
			if(fmod($s,3) != 0)
			{
?>
				<td class="specs"><?php echo $let;?></td>
				<td class="specs"></td>
				<td class="specs"></td>
<?php
			}
			//End 3 column and start new column
			else
			{
				if($s != 0)
				{
?>
					</tr><?php $pagecounter++;?>
<?php
				}
?>
			<tr>	
				<td class="specs"><?php echo $let;?></td>
				<td class="specs"></td>
				<td class="specs"></td>
<?php
			}
			//Increment Letter variable
			$let++;
			$s++;
		}	
		//Fill in Valid letter found
		if(fmod($s,3) != 0)
		{
?>
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
		//End 3 column and start new column
		else
		{
			if($s != 0)
			{
?>
				</tr><?php $pagecounter++;?>
<?php
			}
?>
		<tr>	
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
	}
$let++;
}
?>
</table>
</div>

<!-----------------------------------------------
.						.
.		Flysheet			.
.						.
----------------------------------------------!>
<?php
//Get Flysheet Information from Document List
$stable = 'speclist';
$sptable = 'spec';
$dtable = 'drawinglist';
$dptable = 'drawing';

/************************************************
*						*
*Need to change hard value before going live	*
*Change to $no where 0800-620-004		*
*						*
************************************************/
$list = "SELECT * FROM $stable s WHERE s.no='$no' UNION SELECT * FROM $dtable d WHERE d.no='$no'";
$main = "SELECT s.no,s.rev,s.chg,s.note,s.type,s.status FROM $sptable s UNION SELECT d.no,d.rev,d.chg,d.note,d.cust,d.status FROM $dptable d";
$fsql = "SELECT * FROM ($list) list JOIN ($main) main ON list.spec = main.no ORDER BY spec";
$fresult = mysql_query($fsql);
//Check to see if Flysheet needs to be printed
$frow = mysql_fetch_array($fresult);
if($frow[0])
{
?>
	<div class="flysheet">
	<table class="flysheet">
	<!-- HEADER SECTION --!>
		<tr>
			<th class="flysheet" colspan="9">FlySheet</th>
		</tr><?php $pagecounter++;?>
		<tr>
			<th class="flysheet">Document</th>
			<th class="flysheet">Rev</th>
			<th class="flysheet">Status</th>
			<th class="flysheet">Document</th>
			<th class="flysheet">Rev</th>
			<th class="flysheet">Status</th>
			<th class="flysheet">Document</th>
			<th class="flysheet">Rev</th>
			<th class="flysheet">Status</th>
		</tr><?php $pagecounter++;?>
<?php

	//Loop through Documents 3 at a time
	for($f = 0; $frow = mysql_fetch_array($fresult); $f++)
	{
			//Fill in data 3 rows at a time
			if(fmod($f,3) != 0)
			{
?>
				<td class="flysheet"><?php echo $frow['spec'];?></td>
				<td class="flysheet"><?php echo $frow['rev'];?></td>
				<td class="flysheet"><?php echo $frow['status'];?></td>
<?php
			}
			//End 3 column and start new column
			else
			{
				if($s != 0)
				{
?>
					</tr><?php $pagecounter++;?>
<?php
				}
?>
			<tr>	
				<td class="flysheet"><?php echo $frow['spec'];?></td>
				<td class="flysheet"><?php echo $frow['rev'];?></td>
				<td class="flysheet"><?php echo $frow['status'];?></td>
<?php
			}
	}
?>
</table>
</div>
<?php
}
echo $approval;
?>


<?php

















?>
