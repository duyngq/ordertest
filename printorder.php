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
<link rel="stylesheet"
	href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
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

            <p align="center">1229 Jacklin Rd, Milpitas, Ca, 95036</br>
            <strong>Tell</strong>: <a href="#">408-781-8812</a></p>
			</br></br>

            <p align="center">160 Đường D2, Phường 25, Quận Bình Thạnh</br>MR.Khoa: <a href="#">0934-934-952</a>
            <strong>Tell</strong>: <a href="#">408-781-8812</a></p>

            <div id="logo">

              <div id="logoctr">
                <a href="javascript:;" id="change-logo" title="Change logo">Change Logo</a>
                <a href="javascript:;" id="save-logo" title="Save changes">Save</a>
                |
                <a href="javascript:;" id="delete-logo" title="Delete logo">Delete Logo</a>
                <a href="javascript:;" id="cancel-logo" title="Cancel changes">Cancel</a>
              </div>

              <div id="logohelp">
                <input id="imageloc" type="text" size="50" value="" /><br />
                (max width: 540px, max height: 100px)
              </div>
              <img id="image" src="images/logo.png" alt="logo" />
            </div>

		</div>

		<div style="clear:both"></div>

		<div id="customer">

            <textarea id="customer-title">Widget Corp.
c/o Steve Widget</textarea>

            <table id="meta">
                <tr>
                    <td class="meta-head">Invoice #</td>
                    <td><textarea>000123</textarea></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td><textarea id="date">December 15, 2009</textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td><div class="due">$875.00</div></td>
                </tr>

            </table>

		</div>

		<table id="items">

		  <tr>
		      <th>Item</th>
		      <th>Description</th>
		      <th>Unit Cost</th>
		      <th>Quantity</th>
		      <th>Price</th>
		  </tr>

		  <tr class="item-row">
		      <td class="item-name"><div class="delete-wpr"><textarea>Web Updates</textarea><a class="delete" href="javascript:;" title="Remove row">X</a></div></td>
		      <td class="description"><textarea>Monthly web updates for http://widgetcorp.com (Nov. 1 - Nov. 30, 2009)</textarea></td>
		      <td><textarea class="cost">$650.00</textarea></td>
		      <td><textarea class="qty">1</textarea></td>
		      <td><span class="price">$650.00</span></td>
		  </tr>

		  <tr class="item-row">
		      <td class="item-name"><div class="delete-wpr"><textarea>SSL Renewals</textarea><a class="delete" href="javascript:;" title="Remove row">X</a></div></td>

		      <td class="description"><textarea>Yearly renewals of SSL certificates on main domain and several subdomains</textarea></td>
		      <td><textarea class="cost">$75.00</textarea></td>
		      <td><textarea class="qty">3</textarea></td>
		      <td><span class="price">$225.00</span></td>
		  </tr>

		  <tr id="hiderow">
		    <td colspan="5"><a id="addrow" href="javascript:;" title="Add a row">Add a row</a></td>
		  </tr>

		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Subtotal</td>
		      <td class="total-value"><div id="subtotal">$875.00</div></td>
		  </tr>
		  <tr>

		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Total</td>
		      <td class="total-value"><div id="total">$875.00</div></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Amount Paid</td>

		      <td class="total-value"><textarea id="paid">$0.00</textarea></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line balance">Balance Due</td>
		      <td class="total-value balance"><div class="due">$875.00</div></td>
		  </tr>

		</table>

		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</textarea>
		</div>

	</div>

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
		$custId;
		while ($order = mysql_fetch_array($ordersResult)) {
			$userId = $_SESSION['user_id'];
			$custId = $order['cust_id'];
			$_SESSION['custId'] = $custId;
			$_SESSION['orderStatus'] = $order['status'];
			$_SESSION['orderId'] = $order['id'];
			$oldOrderArray = array("orderId" => $order['id'], "custId" => $order['cust_id'],
                  "userId" => $order['user_id'], "status" => $order['status'], "date" => $order['date'], "totalWeight" => $order['total_weight'],
                  "pricePerWeight" => $order['price_per_weight'], "total" => $order['total']);
			$_SESSION['oldOrderArray'] = $oldOrderArray;
		}

		//Get customer info
		$getCustQuery = "SELECT * FROM customers where id = $custId ";
		$custResult = mysql_query($getCustQuery) or die(mysql_error() . "Can not retrieve information from database");
		while ($cust = mysql_fetch_array($custResult)) {
			$oldCustArray = array("custId" => $cust['id'], "custName" => $cust['cust_name'], "address" => $cust['address'], "phone" => $cust['phone']);
            $_SESSION['oldCustArray'] = $oldCustArray;
			?>
			<tr>
				<td>- Customer Name:</td>
				<td><input type="text" name="custName" id="custName"
					value="<?php echo $cust['cust_name'];?>" size="60" /></td>
			</tr>
			<tr>
				<td>- Customer Phone:</td>
				<td><input type="text" name="custPhone" id="custPhone"
					value="<?php echo $cust['phone'];?>" size="60" /></td>
			</tr>
			<tr>
				<td>- Customer Address:</td>
				<td><input type="text" name="custAddr" id="custAddr"
					value="<?php echo $cust['address'];?>" size="60" /></td>
			</tr>
			<tr>
				<td>- Date:</td>
				<td><input name="orderDate" type="date" id="datepicker"
					value="<?php echo $oldOrderArray['date'];?>" size="60" /></td>
			</tr>
			<?php } ?>
			<tr style="border-bottom: 1px solid">
				<td colspan="6" style="border-bottom: 1px solid">- <strong>Status</strong>:
				<select name="status"
				<?php if ($_SESSION['role_id'] != '1') { echo 'disabled';} ?>>
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
					value="<?php echo $oldOrderArray['totalWeight'];?>" size="60"
					onchange="calTotalPricePackage(); updateTotal('productTbl')" /></td>
			</tr>
			<tr>
				<td>
				<blockquote>
				<p>Price (USD/kg):</p>
				</blockquote>
				</td>
				<td><input name="price_per_weight" type="text" id="price_per_weight"
					value="<?php echo $oldOrderArray['pricePerWeight'];?>" size="60"
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
					value="<?php echo $oldOrderArray['pricePerWeight']*$oldOrderArray['totalWeight'];?>"
					size="60" readonly="true" /></td>
			</tr>
			<tr>
				<td>
				<p>Total (*)</p>
				</td>
				<td><input name="prm_sum" type="text" id="prm_sum"
					value="<?php echo $oldOrderArray['total'];?>" size="60"
					readonly="true" /></td>
			</tr>
			<tr>
				<td colspan=2>
				<p>- Comment:</p>
				<p><textarea name="comment" cols="150" rows="10"
					style="border: 2px solid #ff0000"></textarea></p>
				</td>
			</tr>
		</table>
		<div align="center">
			<form id="frmUpdateInfo" name="frmUpdateInfo" method="post" style="text-align: center">
			     <p><input type="submit" name="sbmUpdateInfo" value="Update Information" onclick="return confirm('Are you sure you want to change ?')" /></p>
			</form>

	           <p><input type="submit" name="print" value="Print order" onclick="PrintPreview()"/></p>
		</div>
		<script>
		 function PrintPreview() {
		        var popupWin = window.open('', '_blank', 'width=350,height=150,location=no,left=200px');
		        popupWin.document.open();
		        popupWin.document.write('<html><title>::Print Preview::</title></head><body">')//<link rel="stylesheet" type="text/css" href="Print.css" media="screen"/>
		        popupWin.document.write('Testing printing all things');
		        popupWin.document.write('</html>');
		        popupWin.document.close();

		    }
		</script>
		<table width="100%" border="0">

		</table>
		<p><strong>Previous Comments:</strong></p>
		<table width="100%" border="1" bordercolor="#000000">
			<tr>
				<!--          <td width="2%"><strong>Cmt No.</strong></td>-->
				<td width="5%"><strong>Date</strong></td>
				<td width="4%"><strong>Time</strong></td>
				<td width="4%"><strong>User</strong></td>
				<td width="auto"><strong>Comment</strong></td>
			</tr>
			<?php
			$getCommentsQuery = "SELECT * FROM comments WHERE cust_id=$custId ORDER BY id DESC";
			$commentsQuery=mysql_query($getCommentsQuery) or die(mysql_error() . "Can not retrieve Comments data");
			while ($comment = mysql_fetch_array($commentsQuery)) {
				?>
			<tr>
				<!--<td><?php echo $comment['id']; ?></td>-->
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
			<!--<tr>
		<td colspan="5" style="border:hidden">
				</br></br></br>
		New Comment:
		</td>
      	</tr>
		<tr>
		<td colspan="5">
        <textarea name="comment" id="commnet" cols="150" style="max-width:inherit"></textarea>
		</td>
      	</tr>-->
		</table>
		<p align="left">&nbsp;</p>
		</td>
	</tr>
</table>
</center>
</form>
</body>
</html>
