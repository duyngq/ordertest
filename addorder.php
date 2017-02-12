<?php
error_reporting ( E_ALL ^ E_DEPRECATED );
//if (!isset($_SESSION['expire'])) {
//	header ( "location:login.php" );
//}
//
//$now = time(); // Checking the time now when home page starts.
//if ($now > $_SESSION['expire']) {
//	session_destroy();
//	header ( "location:login.php" );
//}
//$_SESSION['expire'] = $now + (30 * 60);
session_start ();
if (! isset ( $_SESSION ['loggedIn'] ) || (isset ( $_SESSION ['loggedIn'] ) && ! $_SESSION ['loggedIn'])) {
	header ( "location:login.php" );
}

include_once 'dbconn.php';
// add customer to DB and redirect to index.php
if (isset ( $_POST ["submit"] )) {
	$submit = $_POST ["submit"];
}
if (isset ( $submit )) {
	unset ( $_POST ["submit"] );

	$custName = $_POST ["custsName"];
	$custAddr = $_POST ["custAddr"];
	// TODO: need to validate phone format here.
	$custPhone = $_POST ["custPhone"];

	$recvName = $_POST ["recvName"];
	$recvAddr = $_POST ["recvAddr"];
	// TODO: need to validate phone format here.
	$recvPhone = $_POST ["recvPhone"];

	$orderDate = $_POST ["orderDate"];

	// Validate param for product details
	$totalWeight = $_POST ["total_weight"];
	validateNumber ( $totalWeight, "Package weight" );

	$pricePerWeight = $_POST ["price_per_weight"];
	validateNumber ( $pricePerWeight, "Price per package weight" );

	$totalPackagePrice = $_POST ["total_package_price"];
	validateNumber ( $totalPackagePrice, "Package price amount" );

	/**
	 * get all products details from product table
	 */

	$noOfProducts = $_POST ["noOfProducts"];
	if (! is_numeric ( $noOfProducts )) {
		echo "<script>alert('Number of products should be a number!!!!')</script>";
		exit ();
	} else if ($noOfProducts < 0) {
		echo "<script>alert('Number of products should be greater or equal 0!!!!')</script>";
		exit ();
	}

	$products = array (); //store product details with order as: name, quantity, price
	for($i = 1; $i <= $noOfProducts; $i ++) {
		$product = array ();
		$product [0] = $_POST ["product" . $i . "name"];
		if (is_null($product[0]) || $product[0] == null || $product[0] == '') {
			continue;
		}
		$product [1] = $_POST ["product" . $i . "quantity"];
		validateNumber ( $_POST ["product" . $i . "quantity"], "Product quantity of " . $_POST ["product" . $i . "name"] );
		$product [2] = $_POST ["product" . $i . "price"];
		validateNumber ( $_POST ["product" . $i . "price"], "Product price of " . $_POST ["product" . $i . "name"] );
		$products [$i] = $product;
	}

	$total = $_POST ["prm_sum"];
	validateNumber ( $total, "Total amount of all products" );

	// add customer
	$userId = $_SESSION ['user_id'];
	$username = $_SESSION ['username'];

	begin();
	// Check existing send customer first
	$checkCustomerQuery = "select id, cust_name from sendcustomers where phone='" . $custPhone . "'";
	$checkCustomerResult = mysql_query ( $checkCustomerQuery, $connection ) or die ( mysql_error () . "Can not retrieve database" );

	$checkReceiverQuery = "select id, cust_name from recvcustomers where phone='" . $recvPhone . "'";
    $checkReceiverResult = mysql_query ( $checkReceiverQuery, $connection ) or die ( mysql_error () . "Can not retrieve database" );

	$custId;
	$recvCustId;
	if ($checkCustomerResult && $checkReceiverResult) {
		if (mysql_num_rows ( $checkCustomerResult ) == 0) {
			// Need to add this as new customer
			$addCustomerQuery = "insert into sendcustomers (cust_name, phone, address) values ('$custName', '$custPhone', '$custAddr')";
			$addCustomerResult = mysql_query ( $addCustomerQuery, $connection ) or die ( mysql_error () . "Can not retrieve to database" );
			$custId = mysql_insert_id ();
			if (!$addCustomerResult) {
//				addNewOrder ();
//			} else {
				echo "<script>alert('Add send customer failed');</script>";
				clearAll ( $connection, $submit );
				exit ();
			}
		}

		if (mysql_num_rows ( $checkReceiverResult ) == 0) {
            // Need to add this as new receiver customer
            $addReceiverQuery = "insert into recvcustomers (cust_name, phone, address) values ('$recvName', '$recvPhone', '$recvAddr')";
            $addReceiverResult = mysql_query ( $addReceiverQuery, $connection ) or die ( mysql_error () . "Can not retrieve to database" );
            $recvCustId = mysql_insert_id ();
            if (!$addReceiverResult) {
//                addNewOrder ();
//            } else {
                echo "<script>alert('Add send customer failed');</script>";
                clearAll ( $connection, $submit );
                exit ();
            }
        }

//       else { // Then we have all customers (existing and non-existing), add new order
            if (is_null($custId)) {
				while ( $customer = mysql_fetch_array ( $checkCustomerResult ) ) {
					$custId = $customer ["id"];
				}
            }

            if (is_null($recvCustId)) {
                while ( $customer = mysql_fetch_array ( $checkReceiverResult ) ) {
                    $recvCustId = $customer ["id"];
                }
            }
			$orderId = addNewOrder ( $custId, $recvCustId, $userId, $orderDate, $totalWeight, $pricePerWeight, $total, $connection, $submit );
			addOrderDetails($orderId, $products, $connection, $submit);
//		}
	} else {
		rollback();
		echo "<script>alert('Unable to add new order');</script>";
		echo "<script>location.href = 'addorder.php';</script>";
	}
	commit();
	mysql_close ( $connection );
	ob_end_flush ();
	unset ( $submit );
}

function addNewOrder($custId, $recvCustId, $userId, $orderDate, $totalWeight, $pricePerWeight, $total, $connection, $submit) {
	$addNewOrder = "insert into orders(send_cust_id, recv_cust_id, user_id, status, date, total_weight, price_per_weight, total) values ($custId, $recvCustId, $userId, 0, '$orderDate', $totalWeight, $pricePerWeight, $total)";
	$addNewOrderResult = mysql_query ( $addNewOrder, $connection ) or die ( mysql_error () . "Can not retrieve to database" );
	$orderId = mysql_insert_id ();
	if (!$addNewOrderResult) {
		echo "<script>alert('Unable to add new order');</script>";
		clearAll ( $connection, $submit );
		exit ();
	}
	return $orderId;
}

function addOrderDetails($orderId, $products, $connection, $submit) {
	$addOrderDetails="insert into orderdetails (order_id, product_name, product_price, product_quantity) values";
	foreach ($products as $product) {
		$addOrderDetails.="($orderId, '$product[0]', $product[2], $product[1]),";
	}
	$addOrderDetailsResult = mysql_query ( substr($addOrderDetails,0,-1), $connection ) or die ( mysql_error () . "Can not retrieve to database" );
	if ($addOrderDetailsResult) {
		echo "<script>alert('Add new order succeed');</script>";
		echo "<script>location.href = 'index.php';</script>";
	} else {
		echo "<script>alert('Unable to add new order');</script>";
		clearAll ( $connection, $submit );
		exit ();
	}
}

function clearAll($connection, $submit) {
	mysql_close ( $connection );
	ob_end_flush ();
	unset ( $submit );
}

function validateNumber($validatedValue, $stringName) {
	if (! is_numeric ( $validatedValue )) {
		echo "<script>alert('$stringName should be a number!!!!')</script>";
		exit ();
	} else if ($validatedValue < 0) {
		echo "<script>alert('$stringName should be greater or equal 0!!!!')</script>";
		exit ();
	}
}

// TODO: load customers data at load to fill
?>
<DOCTYPE html PUBLIC"-//W3C//DTDXHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add New Order</title>
<style type="text/css">
tr {
	background-color: transparent;
	color: #000;
	text-decoration: none;
}

p.hidden {
	border-style: hidden;
}
</style>
<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript" src="js/util.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker({
    	dateFormat: "dd/mm/yy"
    });
  } );
</script>
</head>

<body>
	<form name="addOrder" onsubmit="return validation()" method="post">
		<center>
			<table width="1024px" border="1">
				<tr>
					<td><div align="right">
							<p>Welcome, <?php echo $_SESSION['username']; ?>!  <a
									href="index.php">Home</a> <a href="logout.php">Log out</a>
							</p>
							<p align="left">
								<strong>ADD NEW ORDER </strong>
							</p>
							<p align="left">Today's Date: <?php date_default_timezone_set('Asia/Bangkok'); echo date('d/m/Y');?> - Time: <?php echo date('H:i'); ?> </p>
							<table width="100%" border="0" bordercolor="#F0F0F0">
							    <tr>
                                    <td>- Sender</td>
                                </tr>
								<tr>
								<td><blockquote>Sender Phone Number:</blockquote></td>
								<td><input name="custPhone" type="text" id="custPhone" size="60" required /></td>
								</tr>
								<tr>
									<td><blockquote>Sender Name:</blockquote></td>
									<td>
										<!-- drop down list that can be search/add new text -->



									<script type="text/javascript">
		//			$(function(){
		//			    $('#custsName').change(function(){
		//			        var abc = $("#browsers option[value='" + $('#custsName').val() + "']").attr('data-id');
		//			        alert(abc);
		//			    });
		//			});
				</script>

									<input list="custsName" name="custsName" id="custsName" size="60" />
									<datalist id="custsName">
			  <?php
					// get all customers' name
					/*
					 * $getCustomersNameQuery = "SELECT cust_name, phone FROM customers";
					 * $getCustomersNameResult = mysql_query($getCustomersNameQuery);
					 * // Mysql_num_row is counting table row
					 * if($getCustomersNameResult) {
					 * $queriedRows = mysql_num_rows($getCustomersNameResult);
					 * while ($customer=mysql_fetch_array($getCustomersNameResult)) {
					 * $custDetails=$customer['cust_name']." - ".$customer['phone'];
					 * echo "<option data-id=\"".$custDetails."\" value=\"".$custDetails."\"></option>";
					 * }
					 * }
					 */
					?></datalist>




									</td>
								</tr>
								<td><blockquote>Sender Address:</blockquote></td>
								<td><input name="custAddr" type="text" id="custAddr" size="60" required /></td>
								</tr>
								<!-- TODO: create sender and receiver customer instead of customer as generic -->
								<tr>
                                    <td>- Receiver</td>
                                </tr>
								<tr>
                                <td><blockquote>Receiver Phone Number:</blockquote></td>
                                <td><input name="recvPhone" type="text" id="recvPhone" size="60" required /></td>
                                </tr>
                                <tr>
                                    <td><blockquote>Receiver Name:</blockquote></td>
                                    <td>
	                                    <input list="recvName" name="recvName" id="recvName" size="60" />
	                                    <datalist id="recvName">
                                    </td>
                                </tr>
                                <td><blockquote>Receiver Address:</blockquote></td>
                                <td><input name="recvAddr" type="text" id="recvAddr" size="60" required /></td>
                                </tr>
								<tr>
									<td>- Date:</td>
									<td><input name="orderDate" type="date" id="datepicker"
										size="60" required /></td>
								</tr>
								<tr>
									<td>- Products:</td>
								</tr>
								<tr>
									<td colspan="2">
										<table width="1024px" border="0" id='productTbl'>
											<tr>
												<th>Product Name</th>
												<th>Quantity</th>
												<th>Price</th>
												<th>Amount</th>
											</tr>
											<tr>
												<td><input name="product1name" type="text" id="product1name"
													size="30" placeholder="Product name" /></td>
												<td><input name="product1quantity" type="number"
													id="product1quantity" value="0" size="30"
													onchange="calProductAmount('product1quantity', 'product1price', 'product1amount')" /></td>
												<td><input name="product1price" type="text"
													id="product1price" value="0" size="30"
													onchange="calProductAmount('product1quantity', 'product1price', 'product1amount')" /></td>
												<td><input name="product1amount" type="text"
													id="product1amount" value="0" size="30" readonly="true" /></td>
												<td><input type="button"
													onclick="addProductRow('productTbl')" border=0
													style='cursor: hand' value="+" /></td>
												<td></td>
											</tr>
											<input name="noOfProducts" id="noOfProducts" type="hidden" border=0 value="1" readonly/>
										</table>
									</td>
								</tr>
								<tr>
									<td>- Product details:</td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Total weight:</p>
										</blockquote></td>
									<td><input name="total_weight" type="text" id="total_weight"
										value="0" size="60"
										onchange="calTotalPricePackage(); updateTotal('productTbl')" /></td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Price (USD/kg):</p>
										</blockquote></td>
									<td><input name="price_per_weight" type="text"
										id="price_per_weight" value="0" size="60"
										onchange="calTotalPricePackage(); updateTotal('productTbl')" /></td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Total price:</p>
										</blockquote></td>
									<td><input name="total_package_price" type="text"
										id="total_package_price" value="0" size="60" readonly="true" /></td>
								</tr>
								<tr>
									<td><p>Total (*)</p></td>
									<td><input name="prm_sum" type="text" id="prm_sum" value="0"
										size="60" readonly="true" /></td>
								</tr>
							</table>
							<p>&nbsp;</p>
							<div align="center">
								<form id="form1" name="form1" method="post"
									style="text-align: center">
									<input type="submit" name="submit" id="submit" value="Add" />
								</form>
							</div>
							<p align="left">&nbsp;</p>
						</div></td>
				</tr>
			</table>

		</center>
	</form>
</BODY>
</HTML>