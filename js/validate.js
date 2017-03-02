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
	var valid = "0123456789.";
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
		alert("Please enter customer name.");
		document.getElementById("custName").focus();
		return false;
	} else if (document.getElementById("custPhone").value.length == 0) {
		alert("Please enter customer phone.");
		document.getElementById("custPhone").focus();
		return false;
	} else if (document.getElementById("custAddr").value.length == 0) {
		alert("Please enter customer address.");
		document.getElementById("custAddr").focus();
		return false;
	} else if (document.getElementById("recvName").value.length == 0) {
		alert("Please enter receiver name.");
		document.getElementById("recvName").focus();
		return false;
	} else if (document.getElementById("recvPhone").value.length == 0) {
		alert("Please enter receiver phone.");
		document.getElementById("recvPhone").focus();
		return false;
	} else if (document.getElementById("recvAddr").value.length == 0) {
		alert("Please enter receiver address.");
		document.getElementById("recvAddr").focus();
		return false;
	} else if (document.getElementById("datepicker").value.length == 0) {
		alert("Please enter the date.");
		document.getElementById("datepicker").focus();
		return false;
	} else if (document.getElementById("product_desc").value.length == 0) {
		alert("Please enter the product description.");
		document.getElementById("product_desc").focus();
		return false;
	} else if (document.getElementById("product_additional").value.length == 0) {
		alert("Please enter the additional fee.");
		document.getElementById("product_additional").focus();
		return false;
	} else if (checknumber(document.getElementById("total_weight").value) == false) {
		alert("Please enter total weight in number.");
		document.getElementById("total_weight").focus();
		return false;
	} else if (checknumber(document.getElementById("price_per_weight").value) == false) {
		alert("Please enter Pirce (USD/kg) in number.");
		document.getElementById("price_per_weight").focus();
		return false;
	} else if (checknumber(document.getElementById("total_package_price").value) == false) {
		alert("Please enter Total price in number.");
		document.getElementById("total_package_price").focus();
		return false;
	} else if (checknumber(document.getElementById("total_weight_1").value) == false) {
		alert("Please enter total weight in number.");
		document.getElementById("total_weight_1").focus();
		return false;
	} else if (checknumber(document.getElementById("price_per_weight_1").value) == false) {
		alert("Please enter Pirce (USD/kg) in number.");
		document.getElementById("price_per_weight_1").focus();
		return false;
	} else if (checknumber(document.getElementById("total_package_price_1").value) == false) {
		alert("Please enter Total price in number.");
		document.getElementById("total_package_price_1").focus();
		return false;
	} else if (checknumber(document.getElementById("total_weight_2").value) == false) {
		alert("Please enter total weight in number.");
		document.getElementById("total_weight_2").focus();
		return false;
	} else if (checknumber(document.getElementById("price_per_weight_2").value) == false) {
		alert("Please enter Pirce (USD/kg) in number.");
		document.getElementById("price_per_weight_2").focus();
		return false;
	} else if (checknumber(document.getElementById("total_package_price_2").value) == false) {
		alert("Please enter Total price in number.");
		document.getElementById("total_package_price_2").focus();
		return false;
	} else if (checknumber(document.getElementById("prm_sum").value) == false) {
		alert("Please enter Total in number.");
		document.getElementById("prm_sum").focus();
		return false;
	}
//	else {
//		//Validate product table
//		return validateProductTable();
//	}
	return true;
}

function validateProductTable() {
	var table = document.getElementById(tableId);
	var total = 0;
	for (var r = 1, n = table.rows.length; r < n; r++) {
		if (table.rows[r].cells[0].children[0].value.length == 0) {
			alert("Please check and enter product name in product table.");
			return false;
		}
		if (checknumber(table.rows[r].cells[1].children[0].value) == false) {
			alert("Please check and enter product quantity in product table.");
			return false;
		}
		if (checknumber(table.rows[r].cells[2].children[0].value) == false) {
			alert("Please check and enter product price in product table.");
			return false;
		}
		if (checknumber(table.rows[r].cells[3].children[3].value) == false) {
			alert("Please check product amount for each product in product table.");
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