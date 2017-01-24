1. change customers
	add user_name VARCHAR(45)
<?php
include_once 'dbconn.php';
ob_start();
//show all customers and their info
$getAllOrdersQuery = "SELECT * FROM customers c ORDER BY date DESC";
$allOrdersResult = mysql_query($getAllOrdersQuery) or die(mysql_error() . "Can not retrieve Customers data");
$shippedList = array();
$shippingList = array();
print_r($allOrdersResult);
while ($customer = mysql_fetch_array($allOrdersResult)) {
    if ($customer['status'] == 1) {
        echo '1';
        array_push($shippedList, $customer['date']);
        array_push($shippedList, $customer['id']);
        array_push($shippedList, $customer['cust_name']);
        array_push($shippedList, $customer['referrer_name']);
        array_push($shippedList, $customer['builder_name']);
        array_push($shippedList, $customer['lender_name']);
        array_push($shippedList, $customer['prm_total']);
    } else {
        echo '2';
        array_push($shippingList, $customer['date']);
        array_push($shippingList, $customer['id']);
        array_push($shippingList, $customer['cust_name']);
        array_push($shippingList, $customer['referrer_name']);
        array_push($shippingList, $customer['builder_name']);
        array_push($shippingList, $customer['lender_name']);
        array_push($shippingList, $customer['prm_total']);
    }
}
//echo "</br>Active list</br>";
//print_r($activeList);
//echo "</br>".count($activeList) / 7;
//for ($index = 0; $index < (count($activeList) / 7); $index ++) {
//    echo $index."</br>";
//    echo $activeList[$index]." ";
//    echo $activeList[$index + 1]." ";
//    echo $activeList[$index + 2]." ";
//    echo $activeList[$index + 3]." ";
//    echo $activeList[$index + 4]." ";
//    echo $activeList[$index + 5]." ";
//    echo $activeList[$index+6]."</br>";
//}

//echo "</br>Inactive list</br>";
print_r($shippingList);
//echo '</br>second date '.$inactiveList[7].'</br>';
for ($index = 0; $index < (count($shippingList) / 7); $index ++) {
    //echo $index."</br>";
    $i = $index*7;
	//echo '</br>'.$i.'</br>';
    echo "date ".$shippingList[$i]." ";
    echo "id ".$shippingList[$i + 1]." ";
    echo $shippingList[$i + 2]." ";
    echo $shippingList[$i + 3]." ";
    echo $shippingList[$i + 4]." ";
    echo $shippingList[$i + 5]." ";
    echo "prm_total ".$shippingList[$i+6]."</br>";
}
?>
