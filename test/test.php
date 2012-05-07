<html>
<body>
<?php
require_once('/home/hamby/auth/dbinfo.php');
$search = $_GET['search'];
if($search)
{
$sql = "SELECT * FROM po WHERE no LIKE '%" . $search .  "%'";
$result = mysql_query($sql);
}
?>
<form action="test.php" method="GET">
<?php
while ($row = mysql_fetch_array($result))
{
?>
<p><?php echo $row['no'];?></p>
<?php
}
?>
<input type="text" name="search" id="search"/>
<input type="submit" value="Go"/>
</form>
<?php
mysql_close($con);
?>
</body>
</html>
