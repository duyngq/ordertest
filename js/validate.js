function LTrim(str) {
	if (str == null) {
		return null;
	}
	for (var i = 0; str.charAt(i) == " "; i++)
		;
	return str.substring(i, str.length);
}

function RTrim(str) {
	if (str == null) {
		return null;
	}
	for (var i = str.length - 1; str.charAt(i) == " "; i--)
		;
	return str.substring(0, i + 1);
}

function Trim(str) {
	return RTrim(LTrim(str));
}

function checknumber(value) {
	var valid = "0123456789."
	var chars;
	var result = true;
	if (value.length == 0)
		return false;
	var dotcount = 0;
	for (i = 0; i < value.length && result == true; i++) {
		chars = value.charAt(i);
		if (valid.indexOf(chars) == -1 || dotcount > 1) {
			result = false;
			dotcount = 0;
		} else {
			if (chars == '.') {
				dotcount++;
			}
			result = true;
		}
	}
	return result;
}

function validation() {
	if (document.getElementById("custName").value.length == 0) {
		alert("Please enter customer name.")
		document.getElementById("custName").focus();
		return false;
	} else if (document.getElementById("custPhone").value.length == 0) {
		alert("Please enter customer phone.")
		document.getElementById("custPhone").focus();
		return false;
	} else if (document.getElementById("custAddr").value.length == 0) {
		alert("Please enter customer address.")
		document.getElementById("custAddr").focus();
		return false;
	} else if (document.getElementById("orderDate").value.length == 0) {
		alert("Please enter the date.")
		document.getElementById("orderDate").focus();
		return false;
	} else if (checknumber(document.getElementById("total_weight").value) == false) {
		alert("Please enter total weight in number.")
		document.getElementById("total_weight").focus();
		return false;
	} else if (checknumber(document.getElementById("price_per_weight").value) == false) {
		alert("Please enter Pirce (USD/kg) in number.")
		document.getElementById("price_per_weight").focus();
		return false;
	} else if (checknumber(document.getElementById("total_package_price").value) == false) {
		alert("Please enter Total price in number.")
		document.getElementById("total_package_price").focus();
		return false;
	}
	else {
		//Validate product table
		return validateProductTable();
	}
	return true;
}

function validateProductTable() {
	var table = document.getElementById(tableId);
	var total = 0;
	for (var r = 1, n = table.rows.length; r < n; r++) {
		if (table.rows[r].cells[0].children[0].value.length == 0) {
			alert("Please check and enter product name in product table.")
			return false;
		}
		if (checknumber(table.rows[r].cells[1].children[0].value) == false) {
			alert("Please check and enter product quantity in product table.")
			return false;
		}
		if (checknumber(table.rows[r].cells[2].children[0].value) == false) {
			alert("Please check and enter product price in product table.")
			return false;
		}
		if (checknumber(table.rows[r].cells[3].children[3].value) == false) {
			alert("Please check product amount for each product in product table.")
			return false;
		}
	}
	return true;
}

function validateNumber(value, string, element) {
	if (checknumber(value) == false) {
		alert("Please enter " + string + " in number.")
		document.getElementById(element).focus();
		return false;
	}
	return true;
}

function validateCustomer() {
	if (document.getElementById("referrer_name").value.length == 0) {
		alert("Please enter Referrer Name.")
		document.getElementById("referrer_name").focus();
		return false;
	} else if (checknumber(document.getElementById("referrer_fee").value) == false) {
		alert("Please enter Referrer Fee in number.")
		document.getElementById("referrer_fee").focus();
		return false;
	} else if (document.getElementById("builder_name").value.length == 0) {
		alert("Please enter Builder Name.")
		document.getElementById("builder_name").focus();
		return false;
	} else if (checknumber(document.getElementById("builder_packageAmount").value) == false) {
		alert("Please enter Builder Package Amount in number.")
		document.getElementById("builder_packageAmount").focus();
		return false;
	} else if (checknumber(document.getElementById("builder_landAmount").value) == false) {
		alert("Please enter Builder Land Amount in number.")
		document.getElementById("builder_landAmount").focus();
		return false;
	} else if (checknumber(document
			.getElementById("builder_constructionAmount").value) == false) {
		alert("Please enter Builder Construction Amount in number.")
		document.getElementById("builder_constructionAmount").focus();
		return false;
	} else if (document.getElementById("lender_name").value.length == 0) {
		alert("Please enter Lender Name.")
		document.getElementById("lender_name").focus();
		return false;
	} else if (checknumber(document.getElementById("lender_loanAmount").value) == false) {
		alert("Please enter Lender Loan Amount in number.")
		document.getElementById("lender_loanAmount").focus();
		return false;
	} else if (document.getElementById("prm_commission").value.length == 0) {
		alert("Please enter PRM Commission Receivable.")
		document.getElementById("prm_commission").focus();
		return false;
	} else if (checknumber(document.getElementById("prm_financialBroker").value) == false) {
		alert("Please enter PRM Financial Broker in number.")
		document.getElementById("prm_financialBroker").focus();
		return false;
	} else if (checknumber(document.getElementById("prm_legals").value) == false) {
		alert("Please enter PRM Legals in number.")
		document.getElementById("prm_legals").focus();
		return false;
	} else if (checknumber(document.getElementById("prm_builders").value) == false) {
		alert("Please enter PEM Builders in number.")
		document.getElementById("prm_builders").focus();
		return false;
	} else if (checknumber(document.getElementById("prm_financialPlanner").value) == false) {
		alert("Please enter PRM Financial Planner in number.")
		document.getElementById("prm_financialPlanner").focus();
		return false;
	}
	return true;
}