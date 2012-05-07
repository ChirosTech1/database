//<![CDATA[
function PurchaseOrders(q)
{
	switch(q)
	{
	case "Short PO's":
		var id = document.getElementById('id').value;
		window.open('../purchasing/shortpo.php?supplier='+id);
		break;
	case "Long PO's":
		var id = document.getElementById('id').value;
		window.open('../purchasing/longpo.php?supplier='+id);
		break;
	case "Cert PO's":
		var id = document.getElementById('id').value;
		window.open('../purchasing/certpo.php?supplier='+id);
		break;
	case "Debit Memo's":
		var id = document.getElementById('id').value;
		window.open('../purchasing/debit.php?supplier='+id);
		break;
	case "Maintenance PO's":
		var id = document.getElementById('id').value;
		window.open('../maintenance/maintpo.php?supplier='+id);
		break;
	case "New Short PO":
		var id = document.getElementById('id').value;
		window.open('../purchasing/shortpo.php?new=New PO&supplier='+id);
		break;
	case "New Long PO":
		var id = document.getElementById('id').value;
		window.open('../purchasing/longpo.php?new=New PO&supplier='+id);
		break;
	case "New Cert PO":
		var id = document.getElementById('id').value;
		window.open('../purchasing/certpo.php?new=New PO&supplier='+id);
		break;
	case "New Debit Memo":
		var id = document.getElementById('id').value;
		window.open('../purchasing/debit.php?new=New Debit Memo&supplier='+id);
		break;
	case "New Maintenance PO":
		var id = document.getElementById('id').value;
		window.open('../maintenance/maintpo.php?new=New PO&supplier='+id);
		break;
	}
}
//]]
