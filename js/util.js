function calLVR() {
	var loanAmount = document.getElementById("lender_loanAmount").value;// addClient.lender_loanAmount.value;
	var packageAmount = document.getElementById("builder_packageAmount").value;// addClient.builder_packageAmount.value;
	var lvr;
	if (packageAmount == 0) {
		lvr = '';
	} else {
		lvr = (loanAmount / packageAmount) * 100;
	}
	document.getElementById("lender_lvr").value = (Math.round(lvr * 100) / 100);
}

function calTotalPRM() {
	var financialBroker = document.getElementById("prm_financialBroker").value;// addClient.lender_loanAmount.value;
	var legals = document.getElementById("prm_legals").value;// addClient.builder_packageAmount.value;
	var builders = document.getElementById("prm_builders").value;
	var financialPlanner = document.getElementById("prm_financialPlanner").value;
	var prmSum = parseFloat(financialBroker) + parseFloat(legals)
			+ parseFloat(builders) + parseFloat(financialPlanner);
	document.getElementById("prm_sum").value = prmSum;
}

function calPackageAmount() {
	var landAmount = document.getElementById("builder_landAmount").value;// addClient.lender_loanAmount.value;
	var constructionAmount = document
			.getElementById("builder_constructionAmount").value;// addClient.builder_packageAmount.value;
	var packageAmountSum = parseFloat(landAmount)
			+ parseFloat(constructionAmount);
	document.getElementById("builder_packageAmount").value = packageAmountSum;
}

function calTotalPricePackage() {
	var weight = document.getElementById("total_weight").value;// addClient.lender_loanAmount.value;
	var pricePerWeight = document.getElementById("price_per_weight").value;// addClient.builder_packageAmount.value;
	var totalPricePackage = parseFloat(weight)* parseFloat(pricePerWeight);
	document.getElementById("total_package_price").value = totalPricePackage;
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
		total = parseFloat(total) + parseFloat(table.rows[r].cells[3].children[0].value)
	}
	document.getElementById("prm_sum").value = parseFloat(total) + parseFloat(document.getElementById("total_package_price").value);
}

function addLvrListener() {
	listen = {}
	loanAmount = document.getElementById("lender_loanAmount");
	listen.onChanged = function(loanAmount) {
		calLVR();
	}
	packageAmount = document.getElementById("builder_packageAmount");
	listen.onChanged = function(packageAmount) {
		calLVR();
	}
	document.getElementById("lender_lvr").addListener(listen);
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
    var e = table.rows.length;
    var x = table.insertRow(e);
    var l = table.rows[e-1].cells.length;
    // insert single column with default data
    table.rows[e].insertCell(0);
    table.rows[e].cells[0].innerHTML="<td><input name=\"product"+e+"name\" type=\"text\" id=\"product"+e+"name\" size=\"30\" placeholder=\"Product name\" /></td>";

    table.rows[e].insertCell(1);
    table.rows[e].cells[1].innerHTML="<td><input name=\"product"+e+"quantity\" type=\"number\" id=\"product"+e+"quantity\" value=\"0\" size=\"30\" onchange=\"calProductAmount('product"+e+"quantity', 'product"+e+"price', 'product"+e+"amount')\"/></td>";
    
    table.rows[e].insertCell(2);
    table.rows[e].cells[2].innerHTML="<td><input name=\"product"+e+"price\" type=\"text\" id=\"product"+e+"price\" value=\"0\" size=\"30\" onchange=\"calProductAmount('product"+e+"quantity', 'product"+e+"price', 'product"+e+"amount')\"/></td>";
    
    table.rows[e].insertCell(3);
    table.rows[e].cells[3].innerHTML="<td><input name=\"product"+e+"amount\" type=\"text\" id=\"product"+e+"amount\" value=\"0\" size=\"30\" readonly=\"true\"/></td>";
	    
    table.rows[e].insertCell(4);
    table.rows[e].cells[4].innerHTML="<td><input type=\"button\" onclick=\"addProductRow('productTbl')\" border=0 style='cursor:hand' value=\"+\"/></td>";
	    
    table.rows[e].insertCell(5);            
    table.rows[e].cells[5].innerHTML="<td><input type=\"button\" onclick=\"removeProductRow('productTbl', this)\" border=0 style='cursor:hand' value=\"-\"/></td>";
    
    document.getElementById('noOfProducts').value = e;
//    for ( var c = 0, m = l; c < m; c++) {
//        table.rows[e].insertCell(c);
//        table.rows[e].cells[c].innerHTML = "&nbsp;&nbsp;";
//    }
}

function removeProductRow(tableId, selectedRow) {
//    alert("Row index is: " + selectedRow.rowIndex);
//    var table = document.getElementById(tableId);
//    table.deleteRow(selectedRow.rowIndex)
	var row = selectedRow.parentNode.parentNode;
	document.getElementById(tableId).deleteRow(row.rowIndex);
	document.getElementById('noOfProducts').value = document.getElementById(tableId).rows.length - 1;
}