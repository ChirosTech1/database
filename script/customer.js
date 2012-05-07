//<![CDATA[
//Update contact information
function WorkOrders(q)
{
	switch(q)
	{
	case "WO's":
		var id = document.getElementsByName('id')[0].value;
		window.open('../contracts/wo.php?supplier='+id);
		break;
	case "New WO":
		var id = document.getElementsByName('id')[0].value;
		window.open('../contracts/wo.php?new=New WO&supplier='+id);
		break;
	}
}
//]]
