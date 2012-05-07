//<![CDATA[
function Invoices(q)
{
	switch(q)
	{
	case "Invoices":
		var no = document.getElementsByName('no')[0].value;
		window.open('../contracts/invoice.php?linkvalue='+no);
		break;
	case "New Invoice":
		var no = document.getElementsByName('no')[0].value;
		window.open('../contracts/invoice.php?new=' + q + '&linkvalue='+no);
		break;
	}
}
//]]
