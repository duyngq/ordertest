<?php
error_reporting ( E_ALL ^ E_DEPRECATED );
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
session_start (); // / initialize session
if (! isset ( $_SESSION ['loggedIn'] ) || (isset ( $_SESSION ['loggedIn'] ) && ! $_SESSION ['loggedIn'])) {
	header ( "location:login.php" );
}
//header('Content-Type: text/plain');
include_once 'dbconn.php';

//TODO: need to move this page to structure as header, content, footer
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
			<p align="center" id="underline" style="font-size:20px">
				1229 Jacklin Rd, Milpitas, Ca, 95036</br>
			</p>
			<p align="center" style="font-size:20px">
				<strong>Tell</strong>: <a href="#">408-781-8812</a>
			</p>
			</br>
			</br>

			<p align="center" id="underline" style="font-size:20px">
				160 Đường D2, Phường 25, Quận Bình Thạnh</br>
			</p>
			<p align="center" style="font-size:20px">
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
			                  "price_per_weight" => $order['price_per_weight'], "total_weight_1" => $order['total_weight_1'],
			                  "price_per_weight_1" => $order['price_per_weight_1'], "total" => $order['total'], "recv_cust_id" => $order['recv_cust_id'],
			                  "product_desc" => $order['product_desc'], "additional_fee" => $order['additional_fee']);
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
			<p align="center" style="font-size:20px">
				RECEIPT</br>SP: <?php echo $orderArray['id'];?></p>
			<div style="clear: both"></div>
			<div id="customer" align="center">
				<table id="meta">
					<tr>
                        <td class="meta-head">
							<p id="underline"><strong>
								Sender (Người gửi)</strong></br>
							</p> Name: <?php echo $senderArray['cust_name'];?></br>
							<p id="underline">Address: <?php echo $senderArray['address'];?></p>
							<p id="underline">Phone: <?php echo $senderArray['phone'];?></p>
						</td>
						<td class="meta-head">
							<p id="underline"> <strong>
								Receiver (Người nhận)</strong></br>
							</p> Name: <?php echo $recvArray['cust_name'];?></br>
							<p id="underline">Address: <?php echo $recvArray['address'];?></p>
							<p id="underline">Phone: <?php echo $recvArray['phone'];?></p>
						</td>
					</tr>

				</table>

			</div>
		</div>
        </br></br>
		<div align="center">
			<table id="meta">
				<tr>
					<td contenteditable='true'>
						<p id="underline"><strong>DESCRIPTION OF CONTENTS: (TÊN HÀNG HÓA):</strong></p>
						<?php 
    						// For loop here over product list to show:
    						// + <quantity> <unit> <productName>
    						
    						// hien phu thu
    						// PHU THU: <quantity> <unit> <productName> X <price> = <total>
    						// TOTAL: <weight> X <pricePerWeight> = total1 + <total> = <final total>
    						$order   = array("\r\n", "\n", "\r");
                            $replace = '<br />';
                            
							echo str_replace($order, $replace, $orderArray['product_desc']);
							echo "<br>PHU THU:<br>";
							echo str_replace($order, $replace, $orderArray['additional_fee']);
							echo "<br><br>";
							$details2="";
							$total2="";
							if ((!is_null($orderArray["total_weight_1"]) && !empty($orderArray["total_weight_1"])) && (!is_null($orderArray["price_per_weight_1"]) && !empty($orderArray["price_per_weight_1"]))) {
								$details2 = $details2." + ".$orderArray["total_weight_1"]." X ".$orderArray["price_per_weight_1"];
								$total2 = $total2." + ".$orderArray["total_weight_1"]*$orderArray["price_per_weight_1"];
							}
	                        echo "<strong>TOTAL : ".$orderArray["total_weight"]." X ".$orderArray["price_per_weight"].$details2." = ".($orderArray["total_weight"]*$orderArray["price_per_weight"]).$total2." = ".$orderArray["total"]."</strong>";
						?>
                    </td>
				</tr>

			</table>
			<table id="tablenoborder" class="nothing">
				<tr>
					<td contenteditable='true'><strong>Weight:(lbs)</strong></td>
					<td><?php echo $orderArray["total_weight"];?></td>
					<td><strong>Date:</strong></td>
					<td><?php echo $orderArray["date"];?></td>
					<td><strong>No.oBox:</strong></td>
					<td contenteditable='true'>1</td>
				</tr>
			</table>
			<table id="tablenoborder" class="nothing">
				<tr>
					<td><strong>TOTAL:</strong></td>
					<td contenteditable='true'><p class="solid"><?php echo "$".$orderArray["total"];?></p></td>
				</tr>
			</table>
		</div>
		<div align="center">
			<table id="meta" class="nothing">
				<tr>
					<td>
						<ul>
							<li id="underline">Chúng tôi không chịu trách nhiệm của hàng hóa bị vỡ trong quá
								trình vận chuyển trên chuyến bay.</li>
							<li id="underline">Chúng tôi chỉ hoàn trả lại tổng giá trị hàng hóa 100% nếu như
								quý khách hàng mua bảo hiểm của công ty chúng tôi.</li>
						</ul>
					</td>
				</tr>
				<tr>
				    <td>
                       <strong id="underline">Customer: <?php echo str_repeat("&nbsp;", 100);?> </strong></br>
                       <strong id="underline">Employee: <?php echo str_repeat("&nbsp;", 100); ?></strong>
                    </td>
				</tr>
			</table>
		</div>

</body>
</html>
