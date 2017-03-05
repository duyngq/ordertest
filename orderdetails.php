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
    $productDesc = $_POST ["product_desc"];
    $additionalFee = "";

    //parse additional fee with the last line is total, the next of last line is fee (delimiter as :), and the rest is each fee (delimiter as comma)
    for ( $i = 0; $i <= 5; $i++) { //skip title as 0
        ${"proDesc".$i} = $_POST["proDesc".$i];
        ${"totalWeight".$i} = $_POST["weight".$i];
        validateNumber ( ${"totalWeight".$i}, "Package weight" );
        ${"pricePerWeight".$i} = $_POST["price".$i];;
        validateNumber ( ${"totalWeight".$i}, "Price per package weight" );
    }

    // Validate param for product details
    $addFee = $_POST ["add_fee"];
    validateNumber ( $addFee, "Additional fee" );

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
	$customerInfoArray = array("id" => "Sender id", "cust_name" => "Sender Name", "address" => "Address", "phone" => "Customer Phone");
	foreach ($compareNewCustAndOldCust as $key => $value) {
		$newValue = $newCustArray[$key];
		if (!is_null($newValue) || !empty($newValue) || isset($newValue)) {
			if (is_numeric($newValue) & $key!="phone") {
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

	$recvInfoArray = array("id" => "Receiver id", "cust_name" => "Receiver Name", "address" => "Address", "phone" => "Customer Phone");
	foreach ($compareNewCustAndOldRecv as $key => $value) {
		$newValue = $newRecvArray[$key];
		if (!is_null($newValue) || !empty($newValue) || isset($newValue)) {
			if (is_numeric($newValue) && $key!="phone") {
				$setClauseForUpdateRecvQuery = $setClauseForUpdateRecvQuery.$key."=".$newValue.", ";
			} else {
				$setClauseForUpdateRecvQuery = $setClauseForUpdateRecvQuery.$key.'="'.$newValue.'", ';
			}

			$systemLog = $systemLog."<em><span style='color:#FF0000'>*System comment:</span> <strong>".$recvInfoArray[$key]."</strong> changed from <strong>".$recvArray[$key]."</strong> to <strong>".$newValue."</strong>.. </em>";
		}
	}

	//convert input date to format d/m/Y to parse to timestamp for cal current week number of month
    $dates = explode ("/",$orderDate);
    $ordDate = strtotime($dates[1]."/".$dates[0]."/".$dates[2]);
    $code = date("n", $ordDate).weekOfMonth($ordDate);
    
	//Generate update query for order
	$orderId = $_SESSION['orderId'];
    $newOrderArray = array("id" => $orderId, "send_cust_id" => $custId,
                  "user_id" => $userId, "status" => $status, "date" => $orderDate, 
                  "desc_0" => $proDesc0, "total_weight" => $totalWeight0, "price_per_weight" => $pricePerWeight0, 
                  "desc_1" => $proDesc1, "total_weight_1" => $totalWeight1, "price_per_weight_1" => $pricePerWeight1, 
                  "desc_2" => $proDesc2, "total_weight_2" => $totalWeight2, "price_per_weight_2" => $pricePerWeight2, 
                  "desc_3" => $proDesc3, "total_weight_3" => $totalWeight3, "price_per_weight_3" => $pricePerWeight3, 
                  "desc_4" => $proDesc4, "total_weight_4" => $totalWeight4, "price_per_weight_4" => $pricePerWeight4, 
                  "desc_5" => $proDesc5, "total_weight_5" => $totalWeight5, "price_per_weight_5" => $pricePerWeight5, 
                  "code" => $code, "fee" => $addFee, "total" => $total, "recv_cust_id" => $recvId,
                  "product_desc" => $productDesc, "additional_fee" => $additionalFee);
    $orderArray = $_SESSION['oldOrderArray'];
    $compareNewOrderAndOldOrder= array_diff_assoc($newOrderArray, $orderArray);
    $updateOrderQuery = "UPDATE orders SET ";
    $whereClauseForUpdateOrderQuery = " WHERE id=$orderId";
    $setClauseForUpdateOrderQuery="";

    //define friendly name to show on message
    $orderInfoArray = array("id" => "Order id", "send_cust_id" => "Sender Id", "user_id" => "User Id", "status" => "Ship status", "date" => "Order date", 
                  "desc_0" => "Product description", "total_weight" => "Total weight", "price_per_weight" => "Price per weight", 
                  "desc_1" => "Product description 1", "total_weight_1" => "Total weight 1", "price_per_weight_1" => "Price per weight 1", 
                  "desc_2" => "Product description 2", "total_weight_2" => "Total weight 2", "price_per_weight_2" => "Price per weight 2", 
                  "desc_3" => "Product description 3", "total_weight_3" => "Total weight 3", "price_per_weight_3" => "Price per weight 3", 
                  "desc_4" => "Product description 4", "total_weight_4" => "Total weight 4", "price_per_weight_4" => "Price per weight 4", 
                  "desc_5" => "Product description 5", "total_weight_5" => "Total weight 5", "price_per_weight_5" => "Price per weight 5", 
                  "code" => "Code", "fee" => "Fee", "total" =>"Total", "recv_cust_id" => "Receiver Id",
                  "product_desc" => "Product description", "additional_fee" => "Additional fee");
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
		$updateRecvQuery = $updateRecvQuery.substr($setClauseForUpdateRecvQuery,0,-2).$whereClauseForUpdateRecvQuery;

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
	commit();

	begin();
    $addSysLogCommentQuery = "INSERT INTO comments(date, comment, order_id, user_name) VALUES";
    if ($systemLog != "") {
    	$addSysLogCommentQuery = $addSysLogCommentQuery.'("'.$currentDate.'", "'.$systemLog.'", '.$orderId.', "'.$username.'")';
    }
    if ($addSysLogCommentQuery != "INSERT INTO comments(date, comment, order_id, user_name) VALUES") {
    	$addSysLogCommentResult = mysql_query($addSysLogCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
    	if (!$addSysLogCommentResult) {
    		rollback();
            clearAll ( $connection, $sbmUpdateInfo );
    		echo "<script>alert('Add new infomation failed');</script>";
    		exit;
    	}
    }

	//add comment{
	$comment = $_POST['comment'];
	$addCommentQuery = "INSERT INTO comments(date, comment, order_id, user_name) VALUES";
	if (!empty($comment) &&( $comment != null || $commens != '')) {
		$addCommentQuery = $addCommentQuery.'("'.$currentDate.'", "'.$comment.'", '.$orderId.', "'.$username.'")';
	}

	if ($addCommentQuery != "INSERT INTO comments(date, comment, order_id, user_name) VALUES") {
		$addCommentResult = mysql_query($addCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
		if (!$addCommentResult) {
			rollback();
            clearAll ( $connection, $sbmUpdateInfo );
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


// get the week number of the month for input date
function weekOfMonth($date) {
    //Get the first day of the month.
    $firstOfMonth = strtotime(date("Y-m-01", $date));
    //Apply above formula.
    $weekNumber = intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
    switch ($weekNumber) {
    case 1:
        return "a";
    case 2:
        return "b";
    case 3:
        return "c";
    case 4:
        return "d";
    case 5:
        return "e";
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
.rTable { display: table; width: 100%; }
.rTableRow {display: table-row;}
.rTableHeading { display: table-header-group; background-color: #ddd;}
.rTableCell, .rTableHead { display: table-cell; padding: 3px 10px; border: 1px solid #999999; }
.rTableHeading { display: table-header-group; background-color: #ddd; font-weight: bold; }
.rTableFoot { display: table-footer-group; font-weight: bold; background-color: #ddd; }
.rTableBody { display: table-row-group; }
</style>
<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript" src="js/util.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
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
<form name="addComment" onsubmit="return validation()" method="post">
<center>
<table width="1024px" border="1">
	<tr>
		<td>
		<div align="right">
		<p>Welcome, <?php echo $_SESSION['username']; ?>! <a href="index.php">Home</a>
		<a href="logout.php">Log out</a></p>
		</div>
		<p align="left"><strong>SHIPMENT DETAILS</strong></p>
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
            exit;
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
                  "user_id" => $order['user_id'], "status" => $order['status'], "date" => $order['date'], 
                  "desc_0" => $order['desc_0'], "total_weight" => $order['total_weight'], "price_per_weight" => $order['price_per_weight'], 
                  "desc_1" => $order['desc_1'], "total_weight_1" => $order['total_weight_1'], "price_per_weight_1" => $order['price_per_weight_1'], 
                  "desc_2" => $order['desc_2'], "total_weight_2" => $order['total_weight_2'], "price_per_weight_2" => $order['price_per_weight_2'], 
                  "desc_3" => $order['desc_3'], "total_weight_3" => $order['total_weight_3'], "price_per_weight_3" => $order['price_per_weight_3'], 
                  "desc_4" => $order['desc_4'], "total_weight_4" => $order['total_weight_4'], "price_per_weight_4" => $order['price_per_weight_4'], 
                  "desc_5" => $order['desc_5'], "total_weight_5" => $order['total_weight_5'], "price_per_weight_5" => $order['price_per_weight_5'], 
                  "code" => $order['code'], "fee" => $order['fee'], "total" => $order['total'], "recv_cust_id" => $order['recv_cust_id'],
			      "product_desc" => $order['product_desc'], "additional_fee" => $order['additional_fee']);
			$_SESSION['oldOrderArray'] = $orderArray;
		}

		//Get customer info
		if (is_null($custId) || $custId == null || empty($custId)) {
            header("location:index.php");
            exit;
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
					value="<?php echo $cust['phone'];?>" size="60" required /></td>
			</tr>
			<tr>
				<td><blockquote>Address:</blockquote></td>
				<td><input type="text" name="custAddr" id="custAddr"
					value="<?php echo $cust['address'];?>" size="60" required /></td>
			</tr>

			<?php }
			//Get receiver info
			if (is_null($recvId) || $recvId == null || empty($recvId)) {
	            header("location:index.php");
                exit;
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
					value="<?php echo $recv['phone'];?>" size="60" required /></td>
			</tr>
			<tr>
				<td><blockquote>Address:</blockquote></td>
				<td><input type="text" name="recvAddr" id="recvAddr"
					value="<?php echo $recv['address'];?>" size="60" required /></td>
			</tr>
			<?php } ?>
			<tr>
				<td>- Date:</td>
				<td><input name="orderDate" type="date" id="datepicker"
					value="<?php echo $orderArray['date'];?>" size="60" required /></td>
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
				        <th>Description</th>
				        <th>Shipment Fee</th>
				    </tr>
				    <tr>
				        <td width=40%><p><textarea name="product_desc" id="product_desc" cols="65" rows="20"
                                        style="border: 1px solid black" placeholder="Click and write product description"><?php echo $orderArray['product_desc'];?></textarea></p>
                        </td>
                        <td width=60% style="vertical-align: top;" id="feeTableRow">
                            <div class="rTable" id="feeTable" stype="border:1px solid black"></div>
                        </td>
                    </tr>
                    
                    <div id="dialog-form" title="Product description">
                        <textarea name="product_desc_dlg" id="product_desc_dlg" style="border: 1px solid black; width: 100%; height: 100%" placeholder="Click and write product description"></textarea>
                    </div>
                    
                    <div id="shipmentFee-form" title="Shipment Fee" stype="border:1px">
                        <div class="rTable">
                            <div class="rTableRow">
                                <div class="rTableHead"><strong>Description</strong></div>
                                <div class="rTableHead"><strong>Weight(lbs)</strong></div>
                                <div class="rTableHead"><strong>Price</strong></div>
                                <div class="rTableHead"><strong>Total</strong></div>
                            </div>
                            <div class="rTableRow">
                                <div class="rTableCell"><input name="proDesc0" type="text" id="proDesc0" class="proDesc0" size="30" style="border:0" value="<?php echo $orderArray['desc_0'];?>"/></div>
                                <div class="rTableCell"><input name="weight0" type="text" id="weight0" class="weight0" value="<?php echo $orderArray['total_weight'];?>" size="10" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight0', 'price0', 'total0');calTotal('shipmentFee-form');calTotalWeight('shipmentFee-form');"/></div>
                                <div class="rTableCell"><input name="price0" type="text" id="price0" class="price0" value="<?php echo $orderArray['price_per_weight'];?>" size="20" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight0', 'price0', 'total0');calTotal('shipmentFee-form');" /></div>
                                <div class="rTableCell"><input name="total0" type="text" id="total0" class="total0" value="<?php echo $orderArray['total_weight'] * $orderArray['price_per_weight'];?>" size="10" style="border:0" readonly="readonly" /></div>
                            </div>
                            <div class="rTableRow">
                                <div class="rTableCell"><input name="proDesc1" type="text" id="proDesc1" class="proDesc1" size="30" style="border:0" value="<?php echo $orderArray['desc_1'];?>"/></div>
                                <div class="rTableCell"><input name="weight1" type="text" id="weight1" class="weight1" value="<?php echo $orderArray['total_weight_1'];?>" size="10" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight1', 'price1', 'total1');calTotal('shipmentFee-form');calTotalWeight('shipmentFee-form');"/></div>
                                <div class="rTableCell"><input name="price1" type="text" id="price1" class="price1" value="<?php echo $orderArray['price_per_weight_1'];?>" size="20" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight1', 'price1', 'total1');calTotal('shipmentFee-form');" /></div>
                                <div class="rTableCell"><input name="total1" type="text" id="total1" class="total1" value="<?php echo $orderArray['total_weight_1'] * $orderArray['price_per_weight_1'];?>" size="10" style="border:0" readonly="readonly" /></div>
                            </div>
                            <div class="rTableRow">
                                <div class="rTableCell"><input name="proDesc2" type="text" id="proDesc2" class="proDesc2" size="30" style="border:0" value="<?php echo $orderArray['desc_2'];?>"/></div>
                                <div class="rTableCell"><input name="weight2" type="text" id="weight2" class="weight2" value="<?php echo $orderArray['total_weight_2'];?>" size="10" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight2', 'price2', 'total2');calTotal('shipmentFee-form');calTotalWeight('shipmentFee-form');"/></div>
                                <div class="rTableCell"><input name="price2" type="text" id="price2" class="price2" value="<?php echo $orderArray['price_per_weight_2'];?>" size="20" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight2', 'price2', 'total2');calTotal('shipmentFee-form');" /></div>
                                <div class="rTableCell"><input name="total2" type="text" id="total2" class="total2" value="<?php echo $orderArray['total_weight_2'] * $orderArray['price_per_weight_2'];?>" size="10" style="border:0" readonly="readonly" /></div>
                            </div>
                            <div class="rTableRow">
                                <div class="rTableCell"><input name="proDesc3" type="text" id="proDesc3" class="proDesc3" size="30" style="border:0" value="<?php echo $orderArray['desc_3'];?>"/></div>
                                <div class="rTableCell"><input name="weight3" type="text" id="weight3" class="weight3" value="<?php echo $orderArray['price_per_weight_3'];?>" size="10" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight3', 'price3', 'total3');calTotal('shipmentFee-form');calTotalWeight('shipmentFee-form');"/></div>
                                <div class="rTableCell"><input name="price3" type="text" id="price3" class="price3" value="<?php echo $orderArray['price_per_weight_3'];?>" size="20" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight3', 'price3', 'total3');calTotal('shipmentFee-form');" /></div>
                                <div class="rTableCell"><input name="total3" type="text" id="total3" class="total3" value="<?php echo $orderArray['total_weight_3'] * $orderArray['price_per_weight_3'];?>" size="10" style="border:0" readonly="readonly" /></div>
                            </div>
                            <div class="rTableRow">
                                <div class="rTableCell"><input name="proDesc4" type="text" id="proDesc4" class="proDesc4" size="30" style="border:0" value="<?php echo $orderArray['desc_4'];?>"/></div>
                                <div class="rTableCell"><input name="weight4" type="text" id="weight4" class="weight4" value="<?php echo $orderArray['price_per_weight_4'];?>" size="10" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight4', 'price4', 'total4');calTotal('shipmentFee-form');calTotalWeight('shipmentFee-form');"/></div>
                                <div class="rTableCell"><input name="price4" type="text" id="price4" class="price4" value="<?php echo $orderArray['price_per_weight_4'];?>" size="20" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight4', 'price4', 'total4');calTotal('shipmentFee-form');" /></div>
                                <div class="rTableCell"><input name="total4" type="text" id="total4" class="total4" value="<?php echo $orderArray['total_weight_4'] * $orderArray['price_per_weight_4'];?>" size="10" style="border:0" readonly="readonly" /></div>
                            </div>
                            <div class="rTableRow">
                                 <div class="rTableCell"><input name="proDesc5" type="text" id="proDesc5" class="proDesc5" size="30" style="border:0" value="<?php echo $orderArray['desc_5'];?>"/></div>
                                 <div class="rTableCell"><input name="weight5" type="text" id="weight5" class="weight5" value="<?php echo $orderArray['price_per_weight_5'];?>" size="10" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight5', 'price5', 'total5');calTotal('shipmentFee-form');calTotalWeight('shipmentFee-form');"/></div>
                                 <div class="rTableCell"><input name="price5" type="text" id="price5" class="price5" value="<?php echo $orderArray['price_per_weight_5'];?>" size="20" style="border:0" onchange="calFeeAmount('shipmentFee-form', 'weight5', 'price5', 'total5');calTotal('shipmentFee-form');" /></div>
                                 <div class="rTableCell"><input name="total5" type="text" id="total5" class="total5" value="<?php echo $orderArray['total_weight_5'] * $orderArray['price_per_weight_5'];?>" size="10" style="border:0" readonly="readonly" /></div>
                            </div>
                            <br/><br/>
                            <!-- Additional fee and total -->
                            <div class="rTableRow">
                                 <div class="rTableCell" style="border:0"></div>
                                 <div class="rTableCell" style="border:0"></div>
                                 <div class="rTableCell" >Additional fee</div>
                                 <div class="rTableCell"><input name="add_fee" type="text" id="add_fee" class="add_fee" value="<?php echo $orderArray['fee'];?>" size="10" style="border:0" onchange="calTotal('shipmentFee-form');"/></div>
                            </div>
                            <div class="rTableRow">
                                 <div class="rTableCell" style="border:0">Total (*)</div>
                                 <div class="rTableCell" style="border:0"><input name="weight_sum" type="text" id="weight_sum" class="weight_sum" value="<?php
                                    $weightSum = $orderArray['total_weight'];
                                    for ($i = 1; $i<=5; $i++) {
                                    	$weightSum += $orderArray['total_weight_'.$i];
                                    }
                                    echo $weightSum;
                                 ?>" size="10" style="border:0" readonly="readonly"/></div>
                                 <div class="rTableCell" style="border:0"></div>
                                 <div class="rTableCell" style="border:0"><input name="prm_sum" type="text" id="prm_sum" class="prm_sum" value="<?php echo $orderArray['total'];?>" size="10" style="border:0" readonly="readonly"/></div>
                            </div>
                       </div>
                    </div>
                                <script>
                                  $( function() {
                                      $('.rTable').clone().appendTo('#feeTable');
                                      $('#feeTable').find('input').attr('readonly', 'readonly');
                                      var productDescDlg = $( "#dialog-form" ).dialog({
                                            autoOpen: false,
                                            height: 800,
                                            width: 800,
                                            modal: true,
                                            buttons: {
                                              Close: function() {
                                                productDescDlg.dialog( "close" );
                                                $( "#product_desc" ).val($( "#product_desc_dlg" ).val());
                                              }
                                            },
                                            close: function() {
                                                $( "#product_desc" ).val($( "#product_desc_dlg" ).val());
                                            }
                                          });
                                       
                                          $( "#product_desc" ).on( "click", function() {
                                            productDescDlg.dialog( "open" );
                                            $( "#product_desc_dlg" ).val($( "#product_desc" ).val());
                                          });
                                  } );
                                  $(function() {
                                       // dialog handling for Shipment Fee
                                          var shipmentFeeDlg = $( "#shipmentFee-form" ).dialog({
                                              autoOpen: false,
                                              height: 500,
                                              width: 800,
                                              modal: true,
                                              buttons: {
                                                Close: function() {
                                                   shipmentFeeDlg.dialog( "close" );
                                                   copyDataToParentPage();
                                                }
                                              },
                                              close: function() {
                                                  copyDataToParentPage();
                                              }
                                            });
                                         
                                            $( "#feeTableRow" ).on( "click", function() {
                                                shipmentFeeDlg.dialog( "open" );
                                                copyDataToShipmentFeeDialog(); //when open dialog, copy data from parent to child
                                            });

                                            function copyDataToParentPage() {
                                                for ( i = 0; i <= 5; i++) {
                                                    $('#feeTable').find('#proDesc' + i).val($('#shipmentFee-form').find('#proDesc' + i).val());
                                                    $('#feeTable').find('#weight' + i).val($('#shipmentFee-form').find('#weight' + i).val());
                                                    $('#feeTable').find('#price' + i).val($('#shipmentFee-form').find('#price' + i).val());
                                                    $('#feeTable').find('#total' + i).val($('#shipmentFee-form').find('#total' + i).val());
                                                }
                                                $('#feeTable').find('#add_fee').val($('#shipmentFee-form').find('#add_fee').val());
                                                $('#feeTable').find('#weight_sum').val($('#shipmentFee-form').find('#weight_sum').val());
                                                $('#feeTable').find('#prm_sum').val($('#shipmentFee-form').find('#prm_sum').val());
                                            }

                                            function copyDataToShipmentFeeDialog() {
                                                for ( i = 0; i <= 5; i++) {
                                                    $('#shipmentFee-form').find('#proDesc' + i).val($('#feeTable').find('#proDesc' + i).val());
                                                    $('#shipmentFee-form').find('#weight' + i).val($('#feeTable').find('#weight' + i).val());
                                                    $('#shipmentFee-form').find('#price' + i).val($('#feeTable').find('#price' + i).val());
                                                    $('#shipmentFee-form').find('#total' + i).val($('#feeTable').find('#total' + i).val());
                                                }
                                                $('#shipmentFee-form').find('#add_fee').val($('#feeTable').find('#add_fee').val());
                                                $('#shipmentFee-form').find('#weight_sum').val($('#feeTable').find('#weight_sum').val());
                                                $('#shipmentFee-form').find('#prm_sum').val($('#feeTable').find('#prm_sum').val());
                                            }
                                        } );
                                </script>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan=2>
				<p>- Comment:</p>
				<p><textarea name="comment" cols="134" rows="4"
					style="border: 1px solid #ff0000"></textarea></p>
				</td>
			</tr>
		</table>
		<div align="center">
			     <label><input type="submit" name="sbmUpdateInfo" value="Update Information" onclick="return confirm('Are you sure you want to change ?')" /></label>
	           <label><input type="button" name="print" value="Print order" onclick="PrintPreview(this)"/></label>
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
