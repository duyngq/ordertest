function calTotalPricePackage() {
	var weight = document.getElementById("total_weight").value;// addClient.lender_loanAmount.value;
	var pricePerWeight = document.getElementById("price_per_weight").value;// addClient.builder_packageAmount.value;
	var totalPricePackage = parseFloat(weight)* parseFloat(pricePerWeight);
	document.getElementById("total_package_price").value = totalPricePackage;
}

function calTotalPricePackage_1() {
	var weight = document.getElementById("total_weight_1").value;// addClient.lender_loanAmount.value;
	var pricePerWeight = document.getElementById("price_per_weight_1").value;// addClient.builder_packageAmount.value;
	var totalPricePackage = parseFloat(weight)* parseFloat(pricePerWeight);
	document.getElementById("total_package_price_1").value = totalPricePackage;
}

function calTotalPricePackage_2() {
	var weight = document.getElementById("total_weight_2").value;// addClient.lender_loanAmount.value;
	var pricePerWeight = document.getElementById("price_per_weight_2").value;// addClient.builder_packageAmount.value;
	var totalPricePackage = parseFloat(weight)* parseFloat(pricePerWeight);
	document.getElementById("total_package_price_2").value = totalPricePackage;
}

function calProductAmount(productquantity, productprice, productamount) {
	var quantity = document.getElementById(productquantity).value;// addClient.lender_loanAmount.value;
	var productPrice = document.getElementById(productprice).value;// addClient.builder_packageAmount.value;
	var productAmount = parseFloat(quantity)* parseFloat(productPrice);
	document.getElementById(productamount).value = productAmount;
	updateTotal('productTbl');
}

function updateTotal(tableId) {
	var table = document.getElementById(tableId);
	var total = 0;
	for (var r = 1, n = table.rows.length; r < n; r++) {
		total = parseFloat(total) + parseFloat(table.rows[r].cells[3].children[0].value);
	}
	document.getElementById("prm_sum").value = parseFloat(total) + parseFloat(document.getElementById("total_package_price").value);
}

function updateTotal() {
	document.getElementById("prm_sum").value = parseFloat(document.getElementById("total_package_price").value) + parseFloat(document.getElementById("total_package_price_1").value) + parseFloat(document.getElementById("total_package_price_2").value) + parseFloat(document.getElementById("add_fee").value);
}

function addRow(tableId) {
	var table = document.getElementById(tableId);
	var e = table.rows.length;
	var x = table.insertRow(e);
	var l = table.rows[e-1].cells.length;
	// x.innerHTML = "&nbsp;";
	for ( var c = 0, m = l; c < m; c++) {
		table.rows[e].insertCell(c);
		table.rows[e].cells[c].innerHTML = "&nbsp;&nbsp;";
	}
}

function addProductRow(tableId) {
    var table = document.getElementById(tableId);
    var tableRows = table.rows.length; // number of rows of tbale
    var e = parseInt(document.getElementById('noOfProducts').value)+1; // the index to create id for each row/cell
    var x = table.insertRow(tableRows); // add new rows to table
    var l = table.rows[tableRows-1].cells.length;
    // insert single column with default data
    table.rows[tableRows].insertCell(0);
    table.rows[tableRows].cells[0].innerHTML="<td><input name=\"product"+e+"name\" type=\"text\" id=\"product"+e+"name\" size=\"30\" placeholder=\"Product name\" /></td>";

    table.rows[tableRows].insertCell(1);
    table.rows[tableRows].cells[1].innerHTML="<td><input name=\"product"+e+"quantity\" type=\"number\" id=\"product"+e+"quantity\" value=\"0\" size=\"30\" onchange=\"calProductAmount('product"+e+"quantity', 'product"+e+"price', 'product"+e+"amount')\"/></td>";
    
    table.rows[tableRows].insertCell(2);
    table.rows[tableRows].cells[2].innerHTML="<td><input name=\"product"+e+"price\" type=\"text\" id=\"product"+e+"price\" value=\"0\" size=\"30\" onchange=\"calProductAmount('product"+e+"quantity', 'product"+e+"price', 'product"+e+"amount')\"/></td>";
    
    table.rows[tableRows].insertCell(3);
    table.rows[tableRows].cells[3].innerHTML="<td><input name=\"product"+e+"amount\" type=\"text\" id=\"product"+e+"amount\" value=\"0\" size=\"30\" readonly=\"true\"/></td>";
	    
    table.rows[tableRows].insertCell(4);
    table.rows[tableRows].cells[4].innerHTML="<td><input type=\"button\" onclick=\"addProductRow('productTbl')\" border=0 style='cursor:hand' value=\"+\"/></td>";
	    
    table.rows[tableRows].insertCell(5);            
    table.rows[tableRows].cells[5].innerHTML="<td><input type=\"button\" onclick=\"removeProductRow('productTbl', this)\" border=0 style='cursor:hand' value=\"-\"/></td>";
    
    document.getElementById('noOfProducts').value = e;
}

function removeProductRow(tableId, selectedRow) {
	var row = selectedRow.parentNode.parentNode;
	document.getElementById(tableId).deleteRow(row.rowIndex);
	//document.getElementById('noOfProducts').value = document.getElementById(tableId).rows.length - 1;
}