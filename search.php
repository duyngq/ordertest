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
function loadAllCustomer() {
    $getCustQuery = "SELECT * FROM sendcustomers where id = $custId ";
    $custResult = mysql_query($getCustQuery) or die(mysql_error() . "Can not retrieve information from database");
    while ($cust = mysql_fetch_array($custResult)) {
        $senderArray = array("id" => $cust['id'], "cust_name" => $cust['cust_name'], "address" => $cust['address'], "phone" => $cust['phone']);
    }
}
//loadAllCustomer();

function isValueSet($value) {
    if (isset($value) && !is_null($value) && !empty($value)) {
        return true;
    }
    return false;
}
//    $sender = $_POST ["sender"];
//    $senderPhone = $_POST ["senderPhone"];
//
//    $receiver = $_POST ["receiver"];
//    $receiverPhone = $_POST ["receiverPhone"];

    $orderId = $_POST ["orderNo"];

//    begin();
    if (isValueSet($orderId)) {
        $orderQuery = "select * from orders where id=".$orderId;
        $orderQueryResult = mysql_query($orderQuery, $connection) or die ( mysql_error () . "Can not retrieve database" );
        $row['order'] = mysql_fetch_row($orderQueryResult);

        //Sender
        $senderQuery = "select * from sendcustomers where id=".$row['order'][1];
        $senderQueryResult = mysql_query($senderQuery, $connection) or die ( mysql_error () . "Can not retrieve database" );
        $row['sender'] = mysql_fetch_row($senderQueryResult);

        //Receiver
        $receiverQuery = "select * from recvcustomers where id=".$row['order'][8];
        $receiverQueryResult = mysql_query($receiverQuery, $connection) or die ( mysql_error () . "Can not retrieve database" );
        $row['receiver'] = mysql_fetch_row($receiverQueryResult);

        $orderId = base64_encode($orderId);
        $pos1 = rand(0, 25);
        $pos2 = rand(26, 51);
        $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $randomLetter1 = $a_z[$pos1];
        $randomLetter2 = $a_z[$pos2];
        $row['orderId'] = substr_replace($orderId, $randomLetter1 . $randomLetter2, 1, 0);

        echo json_encode($row);
    } else {
        throw new Exception('Unable to find order');
    }
?>