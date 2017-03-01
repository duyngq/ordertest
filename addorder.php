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
//if (isset($_SESSION['sender'])) {
//	unset ($_SESSION['sender']);
//}
if (isset ( $submit )) {
	unset ( $_POST ["submit"] );

	$custName = $_POST ["custName"];
	$custAddr = $_POST ["custAddr"];
	// TODO: need to validate phone format here.
	$custPhone = $_POST ["custPhone"];

	$recvName = $_POST ["recvName"];
	$recvAddr = $_POST ["recvAddr"];
	// TODO: need to validate phone format here.
	$recvPhone = $_POST ["recvPhone"];

	$orderDate = $_POST ["orderDate"];

	$productDesc = $_POST ["product_desc"];
	$additionalFee = $_POST ["product_additional"];

	//parse additional fee with the last line is total, the next of last line is fee (delimiter as :), and the rest is each fee (delimiter as comma)
	$feeDetails = 0; 
	$fees = split("\r\n", $additionalFee); // get each line
	for ( $i = 1; $i < count($fees); $i++) { //skip title as 0
		if (!empty($fees[$i])) {
			$fee = split(",", $fees[$i]); //get each fee details
			if ( count($fee) > 1) {
				$feeDetails ++;
				${"totalWeight".($i-1)} = $fee[0];
				validateNumber ( ${"totalWeight".($i-1)}, "Package weight" );
				${"pricePerWeight".($i-1)} = $fee[1];
				validateNumber ( ${"totalWeight".($i-1)}, "Price per package weight" );
			} else {
				$fee = split(":", $fees[$i]); //get the total (the last one) and additional fee
				if ( count($fee) > 0) {
					if ($i == count($fees) - 1) {
						$total = $fee[1];
						validateNumber ( $total, "Total amount of all products" );
					} else {
						$addFee = trim($fee[1]);
						validateNumber ( $addFee, "Additional fee" );
					}
				}
			}
		}
	}
	
	for ($j = $feeDetails; $j < 6; $j++) { //hard code with 6 fee details
		${"totalWeight".($j)} = 0;
		${"pricePerWeight".($j)} = 0;
	}
	// Validate param for product details
//	$totalWeight = $_POST ["total_weight"];
//	validateNumber ( $totalWeight, "Package weight" );
//
//	$pricePerWeight = $_POST ["price_per_weight"];
//	validateNumber ( $pricePerWeight, "Price per package weight" );
//
//	$totalWeight1 = $_POST ["total_weight_1"];
//    validateNumber ( $totalWeight, "Package weight 1" );
//
//    $pricePerWeight1 = $_POST ["price_per_weight_1"];
//    validateNumber ( $pricePerWeight, "Price per package weight 1" );
//
//    $totalWeight2 = $_POST ["total_weight_2"];
//    validateNumber ( $totalWeight, "Package weight 2" );
//
//    $pricePerWeight2 = $_POST ["price_per_weight_2"];
//    validateNumber ( $pricePerWeight, "Price per package weight 2" );
//
//    $addFee = $_POST ["add_fee"];
//    validateNumber ( $addFee, "Additional fee" );

// 	$totalPackagePrice = $_POST ["total_package_price"];
// 	validateNumber ( $totalPackagePrice, "Package price amount" );

	/**
	 * get all products details from product table
	 */
//	$noOfProducts = $_POST ["noOfProducts"];
//	if (! is_numeric ( $noOfProducts )) {
//		echo "<script>alert('Number of products should be a number!!!!')</script>";
//		exit ();
//	} else if ($noOfProducts < 0) {
//		echo "<script>alert('Number of products should be greater or equal 0!!!!')</script>";
//		exit ();
//	}
//
//	$products = array (); //store product details with order as: name, quantity, price
//	for($i = 1; $i <= $noOfProducts; $i ++) {
//		$product = array ();
//		$product [0] = $_POST ["product" . $i . "name"];
//		if (is_null($product[0]) || $product[0] == null || $product[0] == '') {
//			continue;
//		}
//		$product [1] = $_POST ["product" . $i . "quantity"];
//		validateNumber ( $_POST ["product" . $i . "quantity"], "Product quantity of " . $_POST ["product" . $i . "name"] );
//		$product [2] = $_POST ["product" . $i . "price"];
//		validateNumber ( $_POST ["product" . $i . "price"], "Product price of " . $_POST ["product" . $i . "name"] );
//		$products [$i] = $product;
//	}

//	$total = $_POST ["prm_sum"];
//	validateNumber ( $total, "Total amount of all products" );

	// add customer
	$userId = $_SESSION ['user_id'];
	$username = $_SESSION ['username'];

	begin();
	// Check existing send customer first
	$checkCustomerQuery = "select id, cust_name from sendcustomers where phone='" . $custPhone . "'";
	$checkCustomerResult = mysql_query ( $checkCustomerQuery, $connection ) or die ( mysql_error () . "Can not retrieve database" );

	$checkReceiverQuery = "select id, cust_name from recvcustomers where phone='" . $recvPhone . "'";
    $checkReceiverResult = mysql_query ( $checkReceiverQuery, $connection ) or die ( mysql_error () . "Can not retrieve database" );

	$custId = null;
	$recvCustId = null;
	if ($checkCustomerResult && $checkReceiverResult) {
		if (mysql_num_rows ( $checkCustomerResult ) == 0) {
			// Need to add this as new customer
			$addCustomerQuery = "insert into sendcustomers (cust_name, phone, address) values ('$custName', '$custPhone', '$custAddr')";
			$addCustomerResult = mysql_query ( $addCustomerQuery, $connection ) or die ( mysql_error () . "Can not retrieve to database" );
			$custId = mysql_insert_id ();
			if (!$addCustomerResult) {
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
                echo "<script>alert('Add send customer failed');</script>";
                clearAll ( $connection, $submit );
                exit ();
            }
        }

        // Then we have all customers (existing and non-existing), add new order
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
        $orderId = addNewOrder ( $custId, $recvCustId, $userId, $orderDate, $totalWeight0, $pricePerWeight0, $totalWeight1, $pricePerWeight1, $totalWeight2, $pricePerWeight2, $totalWeight3, $pricePerWeight3, $totalWeight4, $pricePerWeight5, $totalWeight5, $pricePerWeight5, $addFee, $productDesc, $additionalFee, $total, $connection, $submit );
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

function addNewOrder($custId, $recvCustId, $userId, $orderDate, $totalWeight, $pricePerWeight, $totalWeight1, $pricePerWeight1, $totalWeight2, $pricePerWeight2, $totalWeight3, $pricePerWeight3, $totalWeight4, $pricePerWeight5, $totalWeight5, $pricePerWeight5, $addFee, $productDesc, $additionalFee, $total, $connection, $submit) {
	$addNewOrder = "insert into orders(send_cust_id, recv_cust_id, user_id, status, date, total_weight, price_per_weight, total_weight_1, price_per_weight_1, total_weight_2, price_per_weight_2, total_weight_3, price_per_weight_3, total_weight_4, price_per_weight_4, total_weight_5, price_per_weight_5, fee, product_desc, additional_fee, total) 
	   values ($custId, $recvCustId, $userId, 0, '$orderDate', $totalWeight, $pricePerWeight, $totalWeight1, $pricePerWeight1, $totalWeight2, $pricePerWeight2, $totalWeight3, $pricePerWeight3, $totalWeight4, $pricePerWeight5, $totalWeight5, $pricePerWeight5, $addFee, '$productDesc', '$additionalFee', $total)";
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

// Load and save all customers to be able to selectable when input
function loadAllCustomers() {
	getSenders();
	getReceivers();
}

function getSenders() {
    // get all customers' name
    $getCustomersNameQuery = "SELECT * FROM sendcustomers";
    $getCustomersNameResult = mysql_query($getCustomersNameQuery);
    // Mysql_num_row is counting table row
    if($getCustomersNameResult) {
        while ($customer=mysql_fetch_array($getCustomersNameResult)) {
            $senderArray[$customer['phone']] = array("cust_name" => $customer['cust_name'], "address" => $customer['address'], "phone" => $customer['phone']);
        }
        if (isset($senderArray)) {
            $_SESSION['sender'] = $senderArray;
        }
    }
}

function getReceivers() {
    // get all customers' name
    $getRecvNameQuery = "SELECT * FROM recvcustomers";
    $getRecvNameResult = mysql_query($getRecvNameQuery);
    // Mysql_num_row is counting table row
    if($getRecvNameResult) {
        while ($recv = mysql_fetch_array($getRecvNameResult)) {
            $receiverArray[$recv['phone']] = array("cust_name" => $recv['cust_name'], "address" => $recv['address'], "phone" => $recv['phone']);
        }
        if (isset($receiverArray)) {
            $_SESSION['recv'] = $receiverArray;
        }
    }
}
loadAllCustomers();
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
								<strong>ADD NEW SHIPMENT </strong>
							</p>
							<p align="left">Today's Date: <?php date_default_timezone_set('Asia/Bangkok'); echo date('d/m/Y');?> - Time: <?php echo date('H:i'); ?> </p>
							<table width="100%" border="0" bordercolor="#F0F0F0">
							    <tr>
                                    <td>- Sender</td>
                                </tr>
								<tr>
								<td><blockquote>Sender Phone Number:</blockquote></td>
								<td><input name="custPhone" type="text" id="custPhone" size="60" list="custPhoneList" required />
									<!-- drop down list that can be search/add new text -->
									<script type="text/javascript">
									   $("input[name=custPhone]").focusout(function(){
										   var ptamzzNamespace = <?php if (isset($_SESSION['sender'])) {echo json_encode($_SESSION['sender']);}?>;
										   var custPhone = $(this).val();
										   $('input[name="custName"]').val(ptamzzNamespace[custPhone]['cust_name']);
										   $('input[name="custAddr"]').val(ptamzzNamespace[custPhone]['address']);
									   });
									</script>
									<datalist id="custPhoneList">
									<?php
									   if (isset($_SESSION['sender'])) {
										   foreach ($_SESSION['sender'] as $sender) {
										   	   echo "<option data-id=\"".$sender['phone']."\" value=\"".$sender['phone']."\"></option>";
										   }
									   }
									?>
									</datalist>
								</td>
								</tr>
								<tr>
									<td><blockquote>Sender Name:</blockquote></td>
									<td><input list="custName" name="custName" id="custName" size="60" /></td>
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
                                <td><input name="recvPhone" type="text" id="recvPhone" size="60" list="recvPhoneList" required />
                                    <!-- drop down list that can be search/add new text -->
                                    <script type="text/javascript">
                                       $("input[name=recvPhone]").focusout(function(){
                                           var recvs = <?php if (isset($_SESSION['recv'])) {echo json_encode($_SESSION['recv']);}?>;
                                           var recvPhone = $(this).val();
                                           $('input[name="recvName"]').val(recvs[recvPhone]['cust_name']);
                                           $('input[name="recvAddr"]').val(recvs[recvPhone]['address']);
                                       });
                                    </script>
                                    <datalist id="recvPhoneList">
                                    <?php
                                    if (isset($_SESSION['recv'])) {
                                       foreach ($_SESSION['recv'] as $receiver) {
                                           echo "<option data-id=\"".$receiver['phone']."\" value=\"".$receiver['phone']."\"></option>";
                                       }
                                    }
                                    ?>
                                    </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <td><blockquote>Receiver Name:</blockquote></td>
                                    <td><input list="recvName" name="recvName" id="recvName" size="60" /></td>
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
												<th>Description</th>
												<th>Additional Fee</th>
											</tr>
											<tr>
												<td><p><textarea name="product_desc" id="product_desc" cols="65" rows="20"
										style="border: 1px solid black" placeholder="Click and write product description" onclick="openProductDescWindow()"></textarea></p></td>
												<td><p><textarea name="product_additional" id="product_additional" cols="65" rows="20"
										style="border: 1px solid black" placeholder="Click and write additional fee description" onclick="openFeeWindow()"></textarea></p></td>
											</tr>
											<script>
										    function openProductDescWindow() {
										    	childWindow = window.open("productdesc.html", 'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,directories=no,location=no');
										    	if (childWindow.opener == null) {
											    	childWindow.opener = self;
										    	}
										    }

                                            function openFeeWindow() {
                                            	// Fixes dual-screen position                         Most browsers      Firefox
                                                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                                                var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

                                                var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                                                var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                                                var left = ((width / 2) - (700 / 2)) + dualScreenLeft;
                                                var top = ((height / 2) - (300 / 2)) + dualScreenTop;
                                                
                                                childWindow = window.open("fee.html", 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=700,height=300,directories=no,location=no,top=' + top + ', left=' + left);
                                                if (childWindow.opener == null) {
                                                    childWindow.opener = self;
                                                }
                                            }
											</script>
										</table>
									</td>
								</tr>
								<!-- tr>
									<td>- Product details:</td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Total weight:</p>
										</blockquote></td>
									<td><input name="total_weight" type="text" id="total_weight"
										value="0" size="60"
										onchange="calTotalPricePackage(); updateTotal()" /></td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Price (USD/lb):</p>
										</blockquote></td>
									<td><input name="price_per_weight" type="text"
										id="price_per_weight" value="0" size="60"
										onchange="calTotalPricePackage(); updateTotal()" /></td>
								</tr>
								<tr>
									<td  style="border-bottom: 1px solid"><blockquote>
											<p>Total price:</p>
										</blockquote></td>
									<td><input name="total_package_price" type="text"
										id="total_package_price" value="0" size="60" readonly="true" /></td>
								</tr>
								<tr>
									<td>- Product details:</td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Total weight:</p>
										</blockquote></td>
									<td><input name="total_weight_1" type="text" id="total_weight_1"
										value="0" size="60"
										onchange="calTotalPricePackage(); updateTotal()" /></td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Price (USD/lb):</p>
										</blockquote></td>
									<td><input name="price_per_weight_1" type="text"
										id="price_per_weight_1" value="0" size="60"
										onchange="calTotalPricePackage_1(); updateTotal()" /></td>
								</tr>
								<tr>
									<td  style="border-bottom: 1px solid"><blockquote>
											<p>Total price:</p>
										</blockquote></td>
									<td><input name="total_package_price_1" type="text"
										id="total_package_price_1" value="0" size="60" readonly="true" /></td>
								</tr>
								<tr>
									<td>- Product details:</td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Total weight:</p>
										</blockquote></td>
									<td><input name="total_weight_2" type="text" id="total_weight_2"
										value="0" size="60"
										onchange="calTotalPricePackage_2(); updateTotal()" /></td>
								</tr>
								<tr>
									<td><blockquote>
											<p>Price (USD/lb):</p>
										</blockquote></td>
									<td><input name="price_per_weight_2" type="text"
										id="price_per_weight_2" value="0" size="60"
										onchange="calTotalPricePackage_2(); updateTotal()" /></td>
								</tr>
								<tr>
									<td style="border-bottom: 1px solid"><blockquote>
											<p>Total price:</p>
										</blockquote></td>
									<td><input name="total_package_price_2" type="text"
										id="total_package_price_2" value="0" size="60" readonly="true" /></td>
								</tr>
								<tr>
									<td><p>Additional Fee :</p></td>
									<td><input name="add_fee" type="text" id="add_fee" value="0"
										size="60" onchange="updateTotal()"/></td>
								</tr>
								<tr>
									<td><p>Total (*)</p></td>
									<td><input name="prm_sum" type="text" id="prm_sum" value="0"
										size="60" /></td>
								</tr-->
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