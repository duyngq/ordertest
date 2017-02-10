<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start(); /// initialize session
if (!isset($_SESSION['loggedIn']) || (isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'])) {
	header("location:login.php");
}
include_once 'dbconn.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ORDER INVOICE</title>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel='stylesheet' type='text/css' href='css/style.css' />
<link rel='stylesheet' type='text/css' href='css/print.css' media="print" />
<script>
  $( function() {
    $( "#datepicker" ).datepicker({
        dateFormat: "dd/mm/yy"
    });
  } );
</script>
</head>

<body>
	<div id="page-wrap">
		<textarea id="header">SAO PHI CARGO</textarea>
		<div id="identity">
			<p align="center" id="underline">
				1229 Jacklin Rd, Milpitas, Ca, 95036</br>
			</p>
			<p align="center">
				<strong>Tell</strong>: <a href="#">408-781-8812</a>
			</p>
			</br>
			</br>

			<p align="center" id="underline">
				160 Đường D2, Phường 25, Quận Bình Thạnh</br>
			</p>
			<p align="center">
				<strong>MR.Khoa: </strong><a href="#">0934-934-952</a>
			</p>
			</br>
			</br>
			<?php
				$orderId = $_GET ['tr'];
				$orderId1 = substr ( $orderId, 0, 1 );
				$orderId2 = substr ( $orderId, 3 );
				$orderId = base64_decode ( $orderId1 . $orderId2 );
				$getOrdersQuery = "SELECT * FROM orders where id = $orderId ";
				$ordersResult = mysql_query($getOrdersQuery) or die(mysql_error() . "Can not retrieve information from database");
				$custId;
				$recvId;
				while ($order = mysql_fetch_array($ordersResult)) {
					$custId = $order['send_cust_id'];
					$recvId = $order['recv_cust_id'];
					$orderArray = array("id" => $order['id'], "send_cust_id" => $order['send_cust_id'],
							"user_id" => $order['user_id'], "status" => $order['status'], "date" => $order['date'], "total_weight" => $order['total_weight'],
							"price_per_weight" => $order['price_per_weight'], "total" => $order['total'], "recv_cust_id" => $order['recv_cust_id'],);
					$_SESSION['oldOrderArray'] = $orderArray;
				}

				//Get customer info
				$getCustQuery = "SELECT * FROM sendcustomers where id = $custId ";
				$custResult = mysql_query($getCustQuery) or die(mysql_error() . "Can not retrieve information from database");
				while ($cust = mysql_fetch_array($custResult)) {
					$senderArray = array("id" => $cust['id'], "cust_name" => $cust['cust_name'], "address" => $cust['address'], "phone" => $cust['phone']);
				}

				//Get receiver info
				$getRecvQuery = "SELECT * FROM recvcustomers where id = $recvId ";
				$recvResult = mysql_query($getRecvQuery) or die(mysql_error() . "Can not retrieve information from database");
				while ($recv = mysql_fetch_array($recvResult)) {
					$recvArray = array("id" => $recv['id'], "cust_name" => $recv['cust_name'], "address" => $recv['address'], "phone" => $recv['phone']);
				}
			?>
			<p align="center">
				RECEIPT</br>SP: <?php echo $orderArray['id'];?></p>
			<div style="clear: both"></div>
			<div id="customer" align="center">
				<table id="meta">
					<tr>
                    <td class="meta-head">
							<p id="underline">
								Sender (Người gửi)</br>Name:
							</p> <?php echo $senderArray['name'];?></br>
							<p id="underline">Address: <?php echo $senderArray['address'];?></p>
							</br>
							<p id="underline">Phone: <?php echo $senderArray['phone'];?></p>
						</td>
						<td class="meta-head">
							<p id="underline">
								Receiver (Người nhận)</br>Name:
							</p> <?php echo $recvArray['name'];?></br>
							<p id="underline">Address: <?php echo $recvArray['address'];?></p>
							</br>
							<p id="underline">Phone: <?php echo $recvArray['phone'];?></p>
						</td>
					</tr>

				</table>

			</div>
		</div>

		<div align="center">
			<table id="meta">
				<tr>
					<td>
						<p id="underline">
							<strong>DESCRIPTION OF CONTENTS: (TÊN HÀNG HÓA):</strong>
							<?php
                    $getOrderDetailsQuery = "SELECT * FROM orderdetails where order_id = $orderId ";
                    $orderDetailsResult = mysql_query($getOrderDetailsQuery) or die(mysql_error() . "Can not retrieve information from database");
                    $noOfProduct = 1;
                    echo "<ul>";
                    $orderDetalsArray;
					$products = array ();
                    while ($orderDetails = mysql_fetch_array($orderDetailsResult)) {
                    	$product = array ();
				        $product [0] = $orderDetails['id'];
				        $product [1] = $orderDetails['order_id'];
				        $product [2] = $orderDetails['product_name'];
				        $product [3] = $orderDetails['product_quantity'];
				        $product [4] = $orderDetails['product_price'];
				        $products [$noOfProduct] = $product;
                    	echo "<li>".$orderDetails['product_quantity']." ".$orderDetails['product_name']."</li>";
                    }
                    echo "</ul>";
                    echo "PHU THU:</br>";
					foreach ($products as $product){
                    	echo $product[3]." ".$product[2]." x ".$product[4]." = ".($product[3]*$product[4]);
                    }
																				// For loop here over product list to show:
																				// + <quantity> <unit> <productName>

																				// hien phu thu
																				// PHU THU: <quantity> <unit> <productName> X <price> = <total>

																				// TOTAL: <weight> X <pricePerWeight> = total1 + <total> = <final total>
																				?>
						</p>
                    </td>
				</tr>

			</table>
			<table id="tablenoborder" class="nothing">
				<tr>
					<td><strong>Weight:(lbs)</strong></td>
					<td></td>
					<td><strong>Date:</strong></td>
					<td></td>
					<td><strong>No.oBox:</strong></td>
					<td contenteditable='true'>1</td>
				</tr>
			</table>
			<table id="tablenoborder" class="nothing">
				<tr>
					<td><strong>TOTAL:</strong></td>
					<td><p class="solid">$89</p></td>
				</tr>
			</table>
		</div>
		<div align="center">
			<table id="meta" class="nothing">
				<tr>
					<td>
						<ul>
							<li>Chúng tôi không chịu trách nhiệm của hàng hóa bị vỡ trong quá
								trình vận chuyển trên chuyến bay.</li>
							<li>Chúng tôi chỉ hoàn trả lại tổng giá trị hàng hóa 100% nếu như
								quý khách hàng mua bảo hiểm của công ty chúng tôi.</li>
						</ul>
					</td>
				</tr>
			</table>
		</div>

		<?php
		$orderId = $_GET ['tr'];
		$_SESSION ['orderId'] = $orderId;
		$orderId1 = substr ( $orderId, 0, 1 );
		$orderId2 = substr ( $orderId, 3 );
		$orderId = base64_decode ( $orderId1 . $orderId2 );
		// get all order info
		$getOrdersQuery = "SELECT * FROM orders where id = $orderId ";
		$ordersResult = mysql_query ( $getOrdersQuery ) or die ( mysql_error () . "Can not retrieve information from database" );
		$custId;
		while ( $order = mysql_fetch_array ( $ordersResult ) ) {
			$userId = $_SESSION ['user_id'];
			$custId = $order ['cust_id'];
			$_SESSION ['custId'] = $custId;
			$_SESSION ['orderStatus'] = $order ['status'];
			$_SESSION ['orderId'] = $order ['id'];
			$orderArray = array (
					"orderId" => $order ['id'],
					"custId" => $order ['cust_id'],
					"userId" => $order ['user_id'],
					"status" => $order ['status'],
					"date" => $order ['date'],
					"totalWeight" => $order ['total_weight'],
					"pricePerWeight" => $order ['price_per_weight'],
					"total" => $order ['total']
			);
			$_SESSION ['oldOrderArray'] = $orderArray;
		}

		// Get customer info
		$getCustQuery = "SELECT * FROM customers where id = $custId ";
		$custResult = mysql_query ( $getCustQuery ) or die ( mysql_error () . "Can not retrieve information from database" );
		while ( $cust = mysql_fetch_array ( $custResult ) ) {
			$senderArray = array (
					"custId" => $cust ['id'],
					"custName" => $cust ['cust_name'],
					"address" => $cust ['address'],
					"phone" => $cust ['phone']
			);
			$_SESSION ['oldCustArray'] = $senderArray;
			// Get product details
			$getOrderDetailsQuery = "SELECT * FROM orderdetails where order_id = $orderId ";
			$orderDetailsResult = mysql_query ( $getOrderDetailsQuery ) or die ( mysql_error () . "Can not retrieve information from database" );
			$noOfProduct = 1;
			$oldProducts = array ();
			while ( $orderDetails = mysql_fetch_array ( $orderDetailsResult ) ) {
				// $oldOrderDetalsArray = array("orderDetailsId" => $orderDetails['id'], "orderId" => $orderDetails['order_id'],
				// "productName" => $orderDetails['product_name'], "productQuantity" => $orderDetails['product_quantity'], "productPrice" => $orderDetails['product_price']);
				$product = array ();
				$product [0] = $orderDetails ['id'];
				$product [1] = $orderDetails ['order_id'];
				$product [2] = $orderDetails ['product_name'];
				$product [3] = $orderDetails ['product_quantity'];
				$product [4] = $orderDetails ['product_price'];
				$oldProducts [$noOfProduct] = $product;
			}
		}
		mysql_close ( $connection );
		ob_end_flush ();
		?>
</body>
</html>
