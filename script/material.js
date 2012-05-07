//<![CDATA[
function Requisition(q)
{
	switch(q)
	{
	case "Requisitions":
		var no = document.getElementsByName('no')[0].value;
		window.open('../materialctrl/req.php?linkvalue='+no);
		break;
	case "New Requisition":
		var no = document.getElementsByName('no')[0].value;
		window.open('../materialctrl/req.php?new=New PO&linkvalue='+no);
		break;
	}
}
//]]
