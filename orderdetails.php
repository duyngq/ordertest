<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//if (!isset($_SESSION['expire'])) {
//    header ( "location:login.php" );
//}
//
//$now = time(); // Checking the time now when home page starts.
//if ($now > $_SESSION['expire']) {
//    session_destroy();
//    header ( "location:login.php" );
//}
//$_SESSION['expire'] = $now + (30 * 60);
session_start(); /// initialize session
if (!isset($_SESSION['loggedIn']) || (isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'])) {
	header("location:login.php");
}
include_once 'dbconn.php';
ob_start();
//add customer to DB and redirect to index.php


if (isset($_POST["sbmUpdateInfo"])) {
	$sbmUpdateInfo = $_POST["sbmUpdateInfo"];
}

if (isset($sbmUpdateInfo) ) {
	//update customer info
	unset($_POST["sbmUpdateInfo"]);

	$custName = $_POST ["custName"];
    $custAddr = $_POST ["custAddr"];
    // TODO: need to validate phone format here.
    $custPhone = $_POST ["custPhone"];

    $recvName = $_POST ["recvName"];
    $recvAddr = $_POST ["recvAddr"];
    // TODO: need to validate phone format here.
    $recvPhone = $_POST ["recvPhone"];

    $orderDate = $_POST ["orderDate"];
    //get current date
    $currentDate = date('d/m/Y H:i');
    // Validate param for product details
    $totalWeight = $_POST ["total_weight"];
    validateNumber ( $totalWeight, "Package weight" );

    $pricePerWeight = $_POST ["price_per_weight"];
    validateNumber ( $pricePerWeight, "Price per package weight" );

    $totalPackagePrice = $_POST ["total_package_price"];
    validateNumber ( $totalPackagePrice, "Package price amount" );


	$noOfProducts = $_POST ["noOfProducts"];
    if (! is_numeric ( $noOfProducts )) {
        echo "<script>alert('Number of products should be a number!!!!')</script>";
        exit ();
    } else if ($noOfProducts < 0) {
        echo "<script>alert('Number of products should be greater or equal 0!!!!')</script>";
        exit ();
    }

    $oldProducts = $_SESSION['oldOrderDetailsArray'];
    $products = array ();
    for($i = 1; $i <= $noOfProducts; $i ++) {
        $product = array ();
        $product [0] = $oldProducts[$i][0]; //order details id
        $product [1] = $oldProducts[$i][1]; //oder id
        $product [2] = $_POST ["product" . $i . "name"];
        $product [3] = $_POST ["product" . $i . "quantity"];
        validateNumber ( $_POST ["product" . $i . "quantity"], "Product quantity of " . $_POST ["product" . $i . "name"] );
        $product [4] = $_POST ["product" . $i . "price"];
        validateNumber ( $_POST ["product" . $i . "price"], "Product price of " . $_POST ["product" . $i . "name"] );
        $products [$i] = $product;
    }

    $total = $_POST ["prm_sum"];
    validateNumber ( $total, "Total amount of all products" );

    // add customer
    $userId = $_SESSION ['user_id'];
    $username = $_SESSION ['username'];
    $custId = $_SESSION['custId'];
    $recvId = $_SESSION['recvId'];
	$status = $_POST['status'];
	if ( is_null($status) || $status == null || $status == '') {
		$status = $_SESSION['status'];
	}

	//generate update query for customer
	$newCustArray = array("id" => $custId, "cust_name" => $custName, "address" => $custAddr, "phone" => $custPhone);
	$senderArray = $_SESSION['oldCustArray'];
	$compareNewCustAndOldCust = array_diff_assoc($newCustArray, $senderArray);
	$updateCustomerQuery = "UPDATE sendcustomers SET ";
	$whereClauseForUpdateCustomerQuery = " WHERE id=$custId";
	$setClauseForUpdateCustomerQuery="";

	$systemLog="";
	$customerInfoArray = array("custId" => "Customer id", "custName" => "Customer Name", "address" => "Address", "phone" => "Customer Phone");
	foreach ($compareNewCustAndOldCust as $key => $value) {
		$newValue = $newCustArray[$key];
		if (!is_null($newValue) || !empty($newValue) || isset($newValue)) {
			if (is_numeric($newValue)) {
				$setClauseForUpdateCustomerQuery = $setClauseForUpdateCustomerQuery.$key."=".$newValue.", ";
			} else {
				$setClauseForUpdateCustomerQuery = $setClauseForUpdateCustomerQuery.$key.'="'.$newValue.'", ';
			}

			$systemLog = $systemLog."<em><span style='color:#FF0000'>*System comment:</span> <strong>".$customerInfoArray[$key]."</strong> changed from <strong>".$senderArray[$key]."</strong> to <strong>".$newValue."</strong>.. </em>";
		}
	}

	//generate update query for receiver
	$newRecvArray = array("id" => $recvId, "cust_name" => $recvName, "address" => $recvAddr, "phone" => $recvPhone);
	$recvArray = $_SESSION['oldRecvArray'];
	$compareNewCustAndOldRecv = array_diff_assoc($newRecvArray, $recvArray);
	$updateRecvQuery = "UPDATE recvcustomers SET ";
	$whereClauseForUpdateRecvQuery = " WHERE id=$recvId";
	$setClauseForUpdateRecvQuery="";

	$systemLog="";
	$recvInfoArray = array("custId" => "Customer id", "custName" => "Customer Name", "address" => "Address", "phone" => "Customer Phone");
	foreach ($compareNewCustAndOldRecv as $key => $value) {
		$newValue = $newRecvArray[$key];
		if (!is_null($newValue) || !empty($newValue) || isset($newValue)) {
			if (is_numeric($newValue)) {
				$setClauseForUpdateRecvQuery = $setClauseForUpdateRecvQuery.$key."=".$newValue.", ";
			} else {
				$setClauseForUpdateRecvQuery = $setClauseForUpdateRecvQuery.$key.'="'.$newValue.'", ';
			}

			$systemLog = $systemLog."<em><span style='color:#FF0000'>*System comment:</span> <strong>".$recvInfoArray[$key]."</strong> changed from <strong>".$recvArray[$key]."</strong> to <strong>".$newValue."</strong>.. </em>";
		}
	}

	//Generate update query for order
	$orderId = $_SESSION['orderId'];
    $newOrderArray = array("id" => $orderId, "send_cust_id" => $custId,
                  "user_id" => $userId, "status" => $status, "date" => $orderDate, "total_weight" => $totalWeight,
                  "price_per_weight" => $pricePerWeight, "total" =>$total, "recv_cust_id" => $recvId,);
    $orderArray = $_SESSION['oldOrderArray'];
    $compareNewOrderAndOldOrder= array_diff_assoc($newOrderArray, $orderArray);
    $updateOrderQuery = "UPDATE orders SET ";
    $whereClauseForUpdateOrderQuery = " WHERE id=$orderId";
    $setClauseForUpdateOrderQuery="";

    //define friendly name to show on message
    $orderInfoArray = array("orderId" => "Order id", "sendCustId" => "Sender Id", "userId" => "User Id", "status" => "Ship status", "date" => "Order date", "totalWeight" => "Total weight",
                  "pricePerWeight" => "Price per weight", "total" =>"Total", "recvId" => "Receiver Id");
    foreach ($compareNewOrderAndOldOrder as $key => $value) {
        $newValue = $newOrderArray[$key];
        if (!is_null($newValue) || !empty($newValue) || isset($newValue)) {
            if (is_numeric($newValue)) {
                $setClauseForUpdateOrderQuery = $setClauseForUpdateOrderQuery.$key."=".$newValue.", ";
            } else {
                $setClauseForUpdateOrderQuery = $setClauseForUpdateOrderQuery.$key.'="'.$newValue.'", ';
            }

            $systemLog = $systemLog."<em><span style='color:#FF0000'>*System comment:</span> <strong>".$orderInfoArray[$key]."</strong> changed from <strong>".$orderArray[$key]."</strong> to <strong>".$newValue."</strong>.. </em>";
        }
    }

    // Generate update query for order details
    $updateOrderDetailsQuery = " ";
    foreach ($products as $product) {
        $updateOrderDetailsQuery.="UPDATE orderdetails SET product_name='$product[2]',product_quantity=$product[3], product_price=$product[4];";
        $systemLog = $systemLog."<em><span style='color:#FF0000'>*System comment:</span> <strong>Order details:</strong> changed to <strong>product_name='".$product[2]."',product_quantity=".$product[3].", product_price=".$product[4]."</strong>.. </em>";
    }

	begin();
	//update customers
	if ($setClauseForUpdateCustomerQuery != null || $setClauseForUpdateCustomerQuery != '') {
		$updateCustomerQuery = $updateCustomerQuery.substr($setClauseForUpdateCustomerQuery,0,-2).$whereClauseForUpdateCustomerQuery;

		$updateCustomerResult = mysql_query($updateCustomerQuery, $connection) or die(mysql_error() . "Can not store data to database");
		if (!$updateCustomerResult) {
			rollback();
            clearAll ( $connection, $sbmUpdateInfo );
			echo "<script>alert('Update customer failed');</script>";
			exit;
		}
	}

	//update receiver
	if ($setClauseForUpdateRecvQuery != null || $setClauseForUpdateRecvQuery != '') {
		$updateCustomerQuery = $updateCustomerQuery.substr($setClauseForUpdateCustomerQuery,0,-2).$whereClauseForUpdateCustomerQuery;

		$updateRecvResult = mysql_query($updateRecvQuery, $connection) or die(mysql_error() . "Can not store data to database");
		if (!$updateRecvResult) {
			rollback();
			clearAll ( $connection, $sbmUpdateInfo );
			echo "<script>alert('Update receiver failed');</script>";
			exit;
		}
	}

	//update order
    if ($setClauseForUpdateOrderQuery != null || $setClauseForUpdateOrderQuery != '') {
        $updateOrderQuery = $updateOrderQuery.substr($setClauseForUpdateOrderQuery,0,-2).$whereClauseForUpdateOrderQuery;

        $updateOrderResult = mysql_query($updateOrderQuery, $connection) or die(mysql_error() . "Can not store data to database");
        if (!$updateOrderResult) {
            rollback();
            clearAll ( $connection, $sbmUpdateInfo );
            echo "<script>alert('Update order failed');</script>";
            exit;
        }
    }

    //update order details
    if ($updateOrderDetailsQuery != null || $updateOrderDetailsQuery != '') {
        $updateOrderDetailsResult = mysql_query($updateOrderDetailsQuery, $connection) or die(mysql_error() . "Can not store data to database");
        if (!$updateOrderDetailsResult) {
            rollback();
            echo "<script>alert('Update order details failed');</script>";
            clearAll ( $connection, $sbmUpdateInfo );
            exit;
        }
    }
    $addSysLogCommentQuery = "INSERT INTO comments(date, comment, order_id, user_name) VALUES";
    if ($systemLog != "") {
    	$addSysLogCommentQuery = $addSysLogCommentQuery.'("'.$currentDate.'", "'.$systemLog.'", '.$orderId.', "'.$username.'")';
    }
    if ($addSysLogCommentQuery != "INSERT INTO comments(date, comment, order_id, user_name) VALUES") {
    	$addSysLogCommentResult = mysql_query($addSysLogCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
    	if ($addSysLogCommentResult) {
    		echo "<script>alert('Add new infomation succeed');</script>";
    	} else {
    		rollback();
            clearAll ( $connection, $sbmUpdateInfo );
    		echo "<script>alert('Add new infomation failed');</script>";
    		exit;
    	}
    }

	//add comment{
	$comment = $_POST['comment'];
	$addCommentQuery = "INSERT INTO comments(date, comment, order_id, user_name) VALUES";
	if ($comment != null || $commens != '') {
		$addCommentQuery = $addCommentQuery.'("'.$currentDate.'", "'.$comment.'", '.$orderId.', "'.$username.'")';
	}

	if ($addCommentQuery != "INSERT INTO comments(date, comment, order_id, user_name) VALUES") {
		$addCommentResult = mysql_query($addCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
		if ($addCommentResult) {
			echo "<script>alert('Add comment succeed');</script>";
		} else {
			rollback();
            clearAll ( $connection, $sbmUpdateInfo );
			echo "<script>alert('Add comment failed');</script>";
			exit;
		}
	}
	commit();
	unset($sbmUpdateInfo);
	ob_end_flush();
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ORDER DETAILS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order details</title>
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
<link rel="stylesheet"
	href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
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
<form name="addComment" onsubmit="return validateCustomer()"
	method="post">
<center>
<table width="1024px" border="1">
	<tr>
		<td>
		<div align="right">
		<p>Welcome, <?php echo $_SESSION['username']; ?>! <a href="index.php">Home</a>
		<a href="logout.php">Log out</a></p>
		</div>
		<p align="left"><strong>ORDER DETAILS</strong></p>
		<p align="left">Today's Date: <?php date_default_timezone_set('Australia/Melbourne'); echo date('d/m/Y');?>
		- Time: <?php echo date('H:i'); ?></p>
		<table width="100%" border="0" bordercolor="#F0F0F0">
		<?php
		$orderId = $_GET['tr'];
		$_SESSION['orderId'] = $orderId;
		$orderId1 = substr($orderId, 0, 1);
		$orderId2 = substr($orderId, 3);
		$orderId = base64_decode($orderId1.$orderId2);
		//get all order info
		$getOrdersQuery = "SELECT * FROM orders where id = $orderId ";
		$ordersResult = mysql_query($getOrdersQuery) or die(mysql_error() . "Can not retrieve information from database");
		if (mysql_num_rows($ordersResult) < 1) {
			header("location:index.php");
		}
		$custId;
		$recvId;
		while ($order = mysql_fetch_array($ordersResult)) {
			$userId = $_SESSION['user_id'];
			$custId = $order['send_cust_id'];
			$recvId = $order['recv_cust_id'];
			$_SESSION['custId'] = $custId;
			$_SESSION['recvId'] = $recvId;
			$_SESSION['orderStatus'] = $order['status'];
			$_SESSION['orderId'] = $order['id'];
			$orderArray = array("id" => $order['id'], "send_cust_id" => $order['send_cust_id'],
                  "user_id" => $order['user_id'], "status" => $order['status'], "date" => $order['date'], "total_weight" => $order['total_weight'],
                  "price_per_weight" => $order['price_per_weight'], "total" => $order['total'], "recv_cust_id" => $order['recv_cust_id'],);
			$_SESSION['oldOrderArray'] = $orderArray;
		}

		//Get customer info
		if (is_null($custId) || $custId == null || empty($custId)) {
            header("location:index.php");
		}
		$getCustQuery = "SELECT * FROM sendcustomers where id = $custId ";
		$custResult = mysql_query($getCustQuery) or die(mysql_error() . "Can not retrieve information from database");
		while ($cust = mysql_fetch_array($custResult)) {
			$senderArray = array("id" => $cust['id'], "cust_name" => $cust['cust_name'], "address" => $cust['address'], "phone" => $cust['phone']);
            $_SESSION['oldCustArray'] = $senderArray;
			?>
			<tr>
				<td>- Sender:</td>
			</tr>
			<tr>
				<td><blockquote>Name:</blockquote></td>
				<td><input type="text" name="custName" id="custName"
					value="<?php echo $cust['cust_name'];?>" size="60" /></td>
			</tr>
			<tr>
				<td><blockquote>Phone:</blockquote></td>
				<td><input type="text" name="custPhone" id="custPhone"
					value="<?php echo $cust['phone'];?>" size="60" /></td>
			</tr>
			<tr>
				<td><blockquote>Address:</blockquote></td>
				<td><input type="text" name="custAddr" id="custAddr"
					value="<?php echo $cust['address'];?>" size="60" /></td>
			</tr>

			<?php }
			//Get receiver info
			if (is_null($recvId) || $recvId == null || empty($recvId)) {
	            header("location:index.php");
	        }
			$getRecvQuery = "SELECT * FROM recvcustomers where id = $recvId ";
			$recvResult = mysql_query($getRecvQuery) or die(mysql_error() . "Can not retrieve information from database");
			while ($recv = mysql_fetch_array($recvResult)) {
				$recvArray = array("id" => $recv['id'], "cust_name" => $recv['cust_name'], "address" => $recv['address'], "phone" => $recv['phone']);
				$_SESSION['oldRecvArray'] = $recvArray;
			?>
			<tr>
				<td>- Receiver:</td>
			</tr>
			<tr>
				<td><blockquote>Name:</blockquote></td>
				<td><input type="text" name="recvName" id="recvName"
					value="<?php echo $recv['cust_name'];?>" size="60" /></td>
			</tr>
			<tr>
				<td><blockquote>Phone:</blockquote></td>
				<td><input type="text" name="recvPhone" id="recvPhone"
					value="<?php echo $recv['phone'];?>" size="60" /></td>
			</tr>
			<tr>
				<td><blockquote>Address:</blockquote></td>
				<td><input type="text" name="recvAddr" id="recvAddr"
					value="<?php echo $recv['address'];?>" size="60" /></td>
			</tr>
			<?php } ?>
			<tr>
				<td>- Date:</td>
				<td><input name="orderDate" type="date" id="datepicker"
					value="<?php echo $orderArray['date'];?>" size="60" /></td>
			</tr>
			<tr style="border-bottom: 1px solid">
				<td colspan="6" style="border-bottom: 1px solid">- <strong>Status</strong>:
				<select name="status"
				<?php //if ($_SESSION['role_id'] != '1') { echo 'disabled';} ?>>
					<option value="1"
					<?php if ($_SESSION['orderStatus'] == 1) { echo "selected='selected'";}?>>Shipped</option>
					<option value="0"
					<?php if ($_SESSION['orderStatus'] == 0) { echo "selected='selected'";}?>>Shipping</option>
				</select></td>
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
					<?php
					//Get product details
					$getOrderDetailsQuery = "SELECT * FROM orderdetails where order_id = $orderId ";
					$orderDetailsResult = mysql_query($getOrderDetailsQuery) or die(mysql_error() . "Can not retrieve information from database");
					$noOfProduct = 1;
					$oldProducts = array ();
					while ($orderDetails = mysql_fetch_array($orderDetailsResult)) {
//						$oldOrderDetalsArray = array("orderDetailsId" => $orderDetails['id'], "orderId" => $orderDetails['order_id'],
//			                  "productName" => $orderDetails['product_name'], "productQuantity" => $orderDetails['product_quantity'], "productPrice" => $orderDetails['product_price']);
				        $product = array ();
				        $product [0] = $orderDetails['id'];
				        $product [1] = $orderDetails['order_id'];
				        $product [2] = $orderDetails['product_name'];
				        $product [3] = $orderDetails['product_quantity'];
				        $product [4] = $orderDetails['product_price'];
				        $oldProducts [$noOfProduct] = $product;
						?>
					<tr>
						<td><input name="product<?php echo $noOfProduct; ?>name"
							type="text" id="product<?php echo $noOfProduct; ?>name" size="30"
							value="<?php echo $orderDetails['product_name'];?>" /></td>
						<td><input name="product<?php echo $noOfProduct; ?>quantity"
							type="number" id="product<?php echo $noOfProduct; ?>quantity"
							value="<?php echo $orderDetails['product_quantity'];?>" size="30"
							onchange="calProductAmount('product<?php echo $noOfProduct; ?>quantity', 'product<?php echo $noOfProduct; ?>price', 'product<?php echo $noOfProduct; ?>amount')" /></td>
						<td><input name="product<?php echo $noOfProduct; ?>price"
							type="text" id="product<?php echo $noOfProduct; ?>price"
							value="<?php echo $orderDetails['product_price'];?>" size="30"
							onchange="calProductAmount('product<?php echo $noOfProduct; ?>quantity', 'product<?php echo $noOfProduct; ?>price', 'product<?php echo $noOfProduct; ?>amount')" /></td>
						<td><input name="product<?php echo $noOfProduct; ?>amount"
							type="text" id="product<?php echo $noOfProduct; ?>amount"
							value="<?php echo $orderDetails['product_price']*$orderDetails['product_quantity'];?>"
							size="30" readonly="true" /></td>
					</tr>
					<?php
					$noOfProduct++;
					}
					$_SESSION['oldOrderDetailsArray'] = $oldProducts;
					?>
					<input name="noOfProducts" id="noOfProducts" type="hidden" border=0
						value="<?php echo $noOfProduct - 1; ?>" />
				</table>
				</td>
			</tr>
			<tr>
				<td>- Product details:</td>
			</tr>
			<tr>
				<td>
				<blockquote>
				<p>Total weight:</p>
				</blockquote>
				</td>
				<td><input name="total_weight" type="text" id="total_weight"
					value="<?php echo $orderArray['total_weight'];?>" size="60"
					onchange="calTotalPricePackage(); updateTotal('productTbl')" /></td>
			</tr>
			<tr>
				<td>
				<blockquote>
				<p>Price (USD/kg):</p>
				</blockquote>
				</td>
				<td><input name="price_per_weight" type="text" id="price_per_weight"
					value="<?php echo $orderArray['price_per_weight'];?>" size="60"
					onchange="calTotalPricePackage(); updateTotal('productTbl')" /></td>
			</tr>
			<tr>
				<td>
				<blockquote>
				<p>Total price:</p>
				</blockquote>
				</td>
				<td><input name="total_package_price" type="text"
					id="total_package_price"
					value="<?php echo $orderArray['price_per_weight']*$orderArray['total_weight'];?>"
					size="60" readonly="true" /></td>
			</tr>
			<tr>
				<td>
				<p>Total (*)</p>
				</td>
				<td><input name="prm_sum" type="text" id="prm_sum"
					value="<?php echo $orderArray['total'];?>" size="60"
					readonly="true" /></td>
			</tr>
			<tr>
				<td colspan=2>
				<p>- Comment:</p>
				<p><textarea name="comment" cols="150" rows="4"
					style="border: 1px solid #ff0000"></textarea></p>
				</td>
			</tr>
		</table>
		<div align="center">
			<form id="frmUpdateInfo" name="frmUpdateInfo" method="post" style="text-align: center" >
			     <p><input type="submit" name="sbmUpdateInfo" value="Update Information" onclick="return confirm('Are you sure you want to change ?')" /></p>
			</form>
	           <p><input type="button" name="print" value="Print order" onclick="PrintPreview(this)"/></p>
		</div>
		<script>
		    function PrintPreview() {
                window.open('printorder.php?tr=<?php echo $_GET['tr'];?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=768,directories=no,location=no');
		    }
		</script>
		
		<!-- p><strong>Previous Comments:</strong></p>
		<table width="100%" border="1" bordercolor="#000000">
			<tr>
				<td width="5%"><strong>Date</strong></td>
				<td width="4%"><strong>Time</strong></td>
				<td width="4%"><strong>User</strong></td>
				<td width="auto"><strong>Comment</strong></td>
			</tr>
			<?php
			$getCommentsQuery = "SELECT * FROM comments WHERE order_id=$orderId ORDER BY id DESC";
			$commentsQuery=mysql_query($getCommentsQuery) or die(mysql_error() . "Can not retrieve Comments data");
			while ($comment = mysql_fetch_array($commentsQuery)) {
				?>
			<tr>
				<td><?php $date = $comment['date']; $date_time = explode(" ", $date); echo trim($date_time[0]); ?></td>
				<td><?php echo trim($date_time[1]); ?></td>
				<td><?php echo $comment['user_name'] ?></td>
				<td><?php $comments = explode(". ", $comment['comment']);
				$lastCmt = $comments[sizeof ($comments) - 2];
				foreach ($comments as $value) {
					if ($value == $lastCmt) {
						echo $value;
					} else {
						echo $value."</br>";
					}
				}
				?></td>
			</tr>
			<?php }
			mysql_close($connection);
			ob_end_flush();?>
			<tr>
				<td colspan="5" style="border: hidden"></td>
			</tr>
		</table-->
		</td>
	</tr>
</table>
</center>
</form>
</body>
</html>
