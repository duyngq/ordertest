<?php
error_reporting ( E_ALL ^ E_DEPRECATED );
//if (!isset($_SESSION['expire'])) {
//  header ( "location:login.php" );
//}
//
//$now = time(); // Checking the time now when home page starts.
//if ($now > $_SESSION['expire']) {
//  session_destroy();
//  header ( "location:login.php" );
//}
//$_SESSION['expire'] = $now + (30 * 60);
session_start ();
if (! isset ( $_SESSION ['loggedIn'] ) || (isset ( $_SESSION ['loggedIn'] ) && ! $_SESSION ['loggedIn'])) {
    header ( "location:login.php" );
}

include_once 'dbconn.php';
// TODO: load customers data at load to fill
// Load and save all customers to be able to selectable when input
function loadAllCustomer() {
    $getCustQuery = "SELECT * FROM sendcustomers";
    $custResult = mysql_query($getCustQuery) or die(mysql_error() . "Can not retrieve information from database");
    while ($cust = mysql_fetch_array($custResult)) {
        $senderArray = array("id" => $cust['id'], "cust_name" => $cust['cust_name'], "address" => $cust['address'], "phone" => $cust['phone']);
    }

    $getRecvQuery = "SELECT * FROM recvcustomers";
    $recvResult = mysql_query($getRecvQuery) or die(mysql_error() . "Can not retrieve information from database");
    while ($cust = mysql_fetch_array($recvResult)) {
    	$recvArray = array("id" => $cust['id'], "cust_name" => $cust['cust_name'], "address" => $cust['address'], "phone" => $cust['phone']);
    }
}
//loadAllCustomer();
?>
<DOCTYPE html PUBLIC"-//W3C//DTDXHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SEARCH ORDERS</title>
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
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" >
$(document).ready(function() {
	$("#submit").click(function(){
		$("#searchResult tbody").remove();
		var dataString = 'orderNo='+ $("#orderNo").val();// + '&email1='+ email + '&password1='+ password + '&contact1='+ contact;
		$.ajax({
	        url: 'search.php',                  //the script to call to get data
	        type: "POST",
	        data: dataString,                        //you can insert url argumnets here to pass to api.php
	           //for example "id=5&parent=6"
	        dataType: 'json',                //data format
	        success: function(data) {        //on recieve of reply
	                var order = data['order'];              //get id
	                var sender = data['sender'];           //get name
	                var receiver = data['receiver'];           //get name
	                //--------------------------------------------------------------------
	                // 3) Update html content
	                //--------------------------------------------------------------------
                    $("#searchResult").append('<tbody><tr>');
                    var newRow =
                    	"<tbody><tr onMouseOver=\"ChangeColor(this, true);\" onMouseOut=\"ChangeColor(this, false);\" onClick=\"DoNav('orderdetails.php?tr=" + data['orderId'] + "')\">"
                    	+"<td>"+order[0]+"</td>"
                    	+"<td>"+order[4]+"</td>"
                    	+"<td>"+sender[1]+"</td>"
                    	+"<td>"+sender[2]+"</td>"
                    	+"<td>"+sender[3]+"</td>"
                    	+"<td>"+receiver[1]+"</td>"
                        +"<td>"+receiver[2]+"</td>"
                        +"<td>"+receiver[3]+"</td>"
                        +"<td>"+order[7]+"</td>"
                    	+"</tr></tbody>" ;
                    $("#searchResult").append($(newRow));
	                //recommend reading up on jquery selectors they are awesome
	                // http://api.jquery.com/category/selectors/
	            },
	       error: function() {        //on recieve of reply
	                //--------------------------------------------------------------------
	                // 3) Update html content
	                //--------------------------------------------------------------------
	                $(".searchResult-error").html("");
	                $("#searchResult").append('<tbody class="employee-grid-error"><tr><th colspan="12">No data found in the server</th></tr></tbody>');
	                $("#searchResult_processing").css("display","none");
	                //recommend reading up on jquery selectors they are awesome
	                // http://api.jquery.com/category/selectors/
	            }
	    });
	});
});
</script>
</head>

<body>
    <form name="searchOrder" onsubmit="return validation()" method="post">
        <center>
            <table width="1024px" border="1" id="searchOrder">
                <tr>
                    <td><div align="right">
                            <p>Welcome, <?php echo $_SESSION['username']; ?>!  <a
                                    href="index.php">Home</a> <a href="logout.php">Log out</a>
                            </p>
                            <p align="left">
                                <strong>SEARCH ORDER </strong>
                            </p>
                            <p align="left">Today's Date: <?php date_default_timezone_set('Asia/Bangkok'); echo date('d/m/Y');?> - Time: <?php echo date('H:i'); ?> </p>
                            <table width="100%" border="0" bordercolor="#F0F0F0">
                                <tr>
	                                <td>Order Number:</td>
	                                <td><input name="orderNo" type="text" id="orderNo" size="60"/></td>
                                </tr>
                                <!-- tr>
                                    <td>Sender Name:</td>
                                    <td><input name="sender" type="text" id="sender" size="60"/></td>
                                </tr>
                                <tr>
                                    <td>Sender Phone Number:</td>
                                    <td><input name="senderPhone" type="text" id="senderPhone" size="60"/></td>
                                </tr>
                                <tr>
	                                <td>Receiver Name:</td>
	                                <td><input name="receiver" type="text" id="receiver" size="60" /></td>
                                </tr>
                                <tr>
                                    <td>Receiver Phone Number:</td>
                                    <td><input name="receiverPhone" type="text" id="receiverPhone" size="60" /></td>
                                </tr-->
                            </table>
                            <p align="center"><input type="button" name="submit" id="submit" value="Search" /></p>
                        </div></td>
                </tr>
            </table>
            <table width="1024px" border="1" id="searchResult">
                    <thead>
                        <tr>
                            <th>Order ID </th>
                            <th>Order date </th>
                            <th>Sender Name </th>
                            <th>Sender Phone</th>
                            <th>Sender Address</th>
                            <th>Receiver Name </th>
                            <th>Receiver Phone</th>
                            <th>Receiver Address</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
            </table>
        </center>
    </form>
</BODY>
</HTML>