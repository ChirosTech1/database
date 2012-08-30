<html>
<head><h1>Hold Screen</h1></head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'hold';
$primaryfield = 'item';
$secondaryfield = 'id';
//Define other variables
$q = $_POST['hold'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
//Check which action to update and execute SQL
switch ($q)
{	
	case "Hold Document":
		//Get Variables
		$no = mysql_real_escape_string($_POST['document']);
		$reason = mysql_real_escape_string($_POST['docreason']);
		//Add Values to hold table
		mysql_query("INSERT INTO hold (item,reason) VALUES ('$no','$reason')");
		//Update Spec/Drawings Table
		mysql_query("UPDATE spec status = 'Hold' WHERE no = '$no'");
		mysql_query("UPDATE drawing status = 'Hold' WHERE no = '$no'");
		//Place all PN's on hold
		mysql_query("UPDATE pn SET status = 'Hold' WHERE no IN (SELECT final FROM jsi UNION SELECT no FROM jsi WHERE no IN (SELECT pn FROM jsispec WHERE spec = '$no'))");
		break;
	case "Hold Tool":
		//Get Variables
		$no = mysql_real_escape_string($_POST['tool']);
		$reason = mysql_real_escape_string($_POST['toolreason']);
		//Add Values to hold table
		mysql_query("INSERT INTO hold (item,reason) VALUES ('$no','$reason')");
		//Update Spec/Drawings Table
		mysql_query("UPDATE tool status = 'Hold' WHERE no = '$no'");
		//Place all PN's on hold
		mysql_query("UPDATE pn SET status = 'Hold' WHERE no IN (SELECT final FROM jsi UNION SELECT no FROM jsi WHERE no IN (SELECT pn FROM jsispec WHERE spec = '$no'))");
		break;
	case "Hold Material":
		echo "This feature is not yet available. Please contact your system administrator."
		break;
	case "Hold PN":
		//Get Variables
		$no = mysql_real_escape_string($_POST['pn']);
		$reason = mysql_real_escape_string($_POST['pnreason']);
		//Add Values to hold table
		mysql_query("INSERT INTO hold (item,reason) VALUES ('$no','$reason')");
		//Update Spec/Drawings Table
		mysql_query("UPDATE pn status = 'Hold' WHERE no = '$no'");
		break;

}
?>
	<form id="holditems" action="holdscreen.php" method="POST">
	<table>
	<tr>
	<th>Type</th>
	<th>Item</th>
	<th>Reason</th>
	<th></th>
	</tr>
	<tr>
	<td>Docuement</td>
        <!--Add Select option for List of Material--!>
        <td><select name="document">
                <option value="default">Select a Document</option>
                <?php
                $tsql = "SELECT no FROM spec UNION SELECT no FROM drawing";
                $tresult = mysql_query($tsql);
                while ($trow = mysql_fetch_array($tresult)){
                ?>
                <option value="<?php echo $trow['no'];?>"><?php echo $trow['no'];?></option> 
                <?php
                }
                ?>
        </select></td>
	<td><input type="text" name="docreason" size="80"/></td>
	<td><input type="submit" name="hold" value="Hold Document"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	<td>Tooling</td>
        <!--Add Select option for List of Material--!>
        <td><select name="tool">
                <option value="default">Select a Tool</option>
                <?php
                $tsql = "SELECT no FROM tool";
                $tresult = mysql_query($tsql);
                while ($trow = mysql_fetch_array($tresult)){
                ?>
                <option value="<?php echo $trow['no'];?>"><?php echo $trow['no'];?></option> 
                <?php
                }
                ?>
        </select></td>
	<td><input type="text" name="toolreason" size="80"/></td>
	<td><input type="submit" name="hold" value="Hold Tool"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	<td>Material</td>
        <!--Add Select option for List of Material--!>
        <td><select name="material">
                <option value="default">Select a Material</option>
                <?php
                $tsql = "SELECT no FROM material";
                $tresult = mysql_query($tsql);
                while ($trow = mysql_fetch_array($tresult)){
                ?>
                <option value="<?php echo $trow['no'];?>"><?php echo $trow['no'];?></option> 
                <?php
                }
                ?>
        </select></td>
	<td><input type="text" name="matreason" size="80"/></td>
	<td><input type="submit" name="hold" value="Hold Material"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	<td>Part Number</td>
        <!--Add Select option for List of Material--!>
        <td><select name="pn">
                <option value="default">Select a Part Number</option>
                <?php
                $tsql = "SELECT no FROM pn";
                $tresult = mysql_query($tsql);
                while ($trow = mysql_fetch_array($tresult)){
                ?>
                <option value="<?php echo $trow['no'];?>"><?php echo $trow['no'];?></option> 
                <?php
                }
                ?>
        </select></td>
	<td><input type="text" name="pnreason" size="80"/></td>
	<td><input type="submit" name="hold" value="Hold PN"/><td>
	</tr>
	<tr><th height="16"></th></tr>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
