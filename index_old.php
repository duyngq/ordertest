<?php
session_start(); /// initialize session
if (!isset($_SESSION['loggedIn']) || (isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'])) {
    header("location:login.php");
}
?> 
<html>
    <head><title>Client List </title>
        <meta http-equiv="Content-Type" CONTENT="text/html; charset=utf-8" />
        <style type="text/css">
            tr {
                background-color: transparent;
                color: #000;
                text-decoration: none;
            }
        </style>
        <script type="text/javascript">
            function ChangeColor(tableRow, highLight) {
                if (highLight) {
                    tableRow.style.backgroundColor = '#dcfac9';
                    tableRow.style.cursor='pointer';
                } else {
                    tableRow.style.backgroundColor = 'white';
                    tableRow.style.cursor='default';
                }
            }

            function DoNav(theUrl) {
                document.location.href = theUrl;
            }
        </script>
    </head>
    <body>
	<center>
        <table width="1024px" border="1">
            <tr>
                <td><div align="right">
                        <p>Welcome, <?php $user_name = $_SESSION['username']; echo $user_name; ?>!  <a href="logout.php">Log out</a></p>
                        <p align="left"><strong>CLIENT LIST</strong></p>
                        <p align="left">Today's Date: <?php date_default_timezone_set('Asia/Bangkok'); echo date('d/m/Y');?> - Time: <?php echo date('H:i'); ?> </p>
                        <p align="left">
                            <label>
                                <input type="submit" name="Submit" value="Add New Client" onClick="window.location.href='addclient.php'"/>
                            </label>
                        </p>
                        <table width="100%" border="1" align="right">
                            <tr>
                                <td><strong>Date Last Enter </strong></td>
                                <td><strong>Time</strong></td>
                                <td><strong>User</strong></td>
                                <td><strong>Cust ID </strong></td>
                                <td><strong>Customer Name </strong></td>
                                <td><strong>Referrer</strong></td>
                                <td><strong>Builder</strong></td>
                                <td><strong>Lender</strong></td>
                                <td><strong>Total Commissions </strong></td>
                            </tr>
                            <?php
                            include_once 'dbconn.php';
                            ob_start();
                            //show all customers and their info
//                            $getAllCustomersQuery="SELECT cust.id, cust.name as custName,
//                                                          ref.fee, ref.name as refName,
//                                                          bld.name as bldName, bld.package_amount, bld.land_amount, bld.construction_amount,
//                                                          ld.name as ldName, ld.loan_amount, ld.lvr,
//                                                          prm.prm, prm.financial_broker, prm.legals, prm.builders, prm.financial_planner, prm.total
//                                                   FROM customers cust, referrer ref, builder bld, lender ld, prmcommissions prm
//                                                   WHERE referrer_id IN (SELECT id from referrer)
//                                                     AND builder_id IN (SELECT id from builder)
//                                                     AND lender_id IN (SELECT id from lender)
//                                                     AND commission_id IN (SELECT id from prmcommissions);";
//                            $getAllCustomersQuery = "SELECT * FROM customers c WHERE c.user_id IN (SELECT id FROM users)";
                            $getAllCustomersQuery = "SELECT * FROM customers c ORDER BY date DESC";
                            $allCustomersResult = mysql_query($getAllCustomersQuery) or die(mysql_error() . "Can not retrieve Customers data");
                            
//                            $getAllUsersQuery = "SELECT * FROM users";
//                            $allCustomersResult = mysql_query($getReferrerIdQuery) or die(mysql_error() . "Can not retrieve Customers data");
                            while ($customer = mysql_fetch_array($allCustomersResult)) {
                            ?>
                            <tr onMouseOver="ChangeColor(this, true);" onMouseOut="ChangeColor(this, false);" onClick="DoNav('customersummary.php?tr=<?php 
                            $custId=base64_encode($customer['id']); 
                            $pos1 = rand(0,25);
                            $pos2 = rand(26,51);
                            $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                            $randomLetter1 = $a_z[$pos1];
                            $randomLetter2 = $a_z[$pos2];
                            echo substr_replace($custId, $randomLetter1.$randomLetter2, 1, 0);
                            ?>')">
                                <td><?php $date_last_entered = $customer['date']; $date_time = explode(" ", $date_last_entered); echo trim($date_time[0]); ?></td>
                                <td><?php echo trim($date_time[1]); ?></td>
                                <td><?php echo $user_name;?></td>
                                <td><?php echo $customer['id']?></td>
                                <td><?php echo $customer['cust_name']?></td>
                                <td><?php echo $customer['referrer_name']?></td>
                                <td><?php echo $customer['builder_name']?></td>
                                <td><?php echo $customer['lender_name']?></td>
                                <td><?php echo '$'.$customer['prm_total']?></td>
                            </tr>
                            <?php
                                }
                            mysql_close($connection);
                            ob_end_flush();
                            ?>
                        </table>
                        <p align="left">&nbsp;</p>
                    </div></td>
            </tr>
        </table></center>
    </body>
</html>


