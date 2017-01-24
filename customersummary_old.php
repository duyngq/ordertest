<?php
session_start(); /// initialize session
if (!isset($_SESSION['loggedIn']) || (isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'])) {
    header("location:login.php");
}
include_once 'dbconn.php';
//ob_start();
//add customer to DB and redirect to index.php
if (isset($_POST["Submit"])) {
    $submit = $_POST["Submit"];
}
if (isset($submit)) {
    unset($_POST["Submit"]);

    $custId = $_POST["custId"];
    $custName = $_POST["custName"];
    
    $referrer = $_POST["referrer_name"];
    $referrerFee = $_POST["referrer_fee"];
    if (!is_null($referrerFee)) {
        if (!is_numeric($referrerFee)) {
            echo "<script>alert('Referrer Fee should be a number!!!!')</script>";
            exit;
        } else if ($referrerFee < 0) {
            echo "<script>alert('Referrer Fee should be greater or equal 0!!!!')</script>";
            exit;
        }
    }
    
    $builder = $_POST["builder_name"];
    $builderPackageAmount = $_POST["builder_packageAmount"];
    $builderLandAmount = $_POST["builder_landAmount"];
    $builderConstructionAmount = $_POST["builder_constructionAmount"];
    if (!is_null($builderPackageAmount)) {
        if (!is_numeric($builderPackageAmount)) {
            echo "<script>alert('Builder Package Value should be a number!!!!')</script>";
            exit;
        } else if ($builderPackageAmount < 0) {
            echo "<script>alert('Builder Package Value should be greater or equal 0!!!!')</script>";
            exit;
        }
    }
    
    if (!is_null($builderLandAmount)) {
        if (!is_numeric($builderLandAmount)) {
            echo "<script>alert('Builder Land Amount should be a number!!!!')</script>";
            exit;
        } else if ($builderLandAmount < 0) {
            echo "<script>alert('Builder Land Amount should be greater or equal 0!!!!')</script>";
            exit;
        }
    }
    
    if (!is_null($builderConstructionAmount)) {
        if (!is_numeric($builderConstructionAmount)) {
            echo "<script>alert('Builder Construction Amount should be a number!!!!')</script>";
            exit;
        } else if ($builderConstructionAmount < 0) {
            echo "<script>alert('Builder Construction Amount should be greater or equal 0!!!!')</script>";
            exit;
        }
    }
    
    $lender = $_POST["lender_name"];
    $lenderLoanAmount = $_POST["lender_loanAmount"];
    $lenderLVR = $_POST["lender_lvr"];
    if (!is_null($lenderLoanAmount)) {
        if (!is_numeric($lenderLoanAmount)) {
            echo "<script>alert('Lender Loan Amount should be a number!!!!')</script>";
            exit;
        } else if ($lenderLoanAmount < 0) {
            echo "<script>alert('Lender Loan Amount should be greater or equal 0!!!!')</script>";
            exit;
        }
    }
    
    if (!is_null($lenderLVR)) {
        if (!is_numeric($lenderLVR)) {
            echo "<script>alert('alert('Lender LVR should be a number!!!!')</script>";
            exit;
        } else if ($lenderLVR < 0) {
            echo "<script>alert('Lender LVR should be greater or equal 0!!!!')</script>";
            exit;
        } else if ($lenderLVR != round(($lenderLoanAmount/$builderPackageAmount)*100, 2)) {
            echo "<script>alert('Lender LVR should be equal [Lender Loan Amountor / Builder Package Amount]!!!!')</script>";
            exit;
        }
    }
    
    $prmFinancialBroker = $_POST["prm_financialBroker"];
    $prmLegals = $_POST["prm_legals"];
    $prmBuilders = $_POST["prm_builders"];
    $prmFinancialPlanner = $_POST["prm_financialPlanner"];
    $prmTotal = $_POST["prm_sum"];
    if (!is_null($prmFinancialBroker)) {
        if (!is_numeric($prmFinancialBroker)) {
            echo "<script>alert('PRM Financial/Broker should be a number!!!!')</script>";
            exit;
        } else if ($prmFinancialBroker < 0) {
            echo "<script>alert('PRM Financial/Broker should be greater or equal 0!!!!')</script>";
            exit;
        }
    }

    if (!is_null($prmLegals)) {
        if (!is_numeric($prmLegals)) {
            echo "<script>alert('PRM Legals should be a number!!!!')</script>";
            exit;
        } else if ($prmLegals < 0) {
            echo "<script>alert('PRM Legals should be greater or equal 0!!!!')</script>";
            exit;
        }
    }

    if (!is_null($prmBuilders)) {
        if (!is_numeric($prmBuilders)) {
            echo "<script>alert('PRM Builders should be a number!!!!')</script>";
            exit;
        } else if ($prmBuilders < 0) {
            echo "<script>alert('PRM Builders should be greater or equal 0!!!!')</script>";
            exit;
        }
    }

    if (!is_null($prmFinancialPlanner)) {
        if (!is_numeric($prmFinancialPlanner)) {
            echo "<script>alert('PRM Financial Planner should be a number!!!!')</script>";
            exit;
        } else if ($prmFinancialPlanner < 0) {
            echo "<script>alert('PRM Financial Planner should be greater or equal 0!!!!')</script>";
            exit;
        }
    }

    if (!is_null($prmTotal)) {
        if (!is_numeric($prmTotal)) {
            echo "<script>alert('PRM Total should be a number!!!!')</script>";
            exit;
        } else if ($prmTotal < 0) {
            echo "<script>alert('PRM Total should be greater or equal 0!!!!')</script>";
            exit;
        } else if ($prmTotal != ($prmFinancialBroker + $prmLegals + $prmBuilders + $prmFinancialPlanner)) {
            echo "<script>alert('PRM Total should be equal the sum of PRM Financial/Broker, PRM Legals, PRM Builders, PRM Financial Planner!!!!')</script>";
            exit;
        }
    }
    
    $userId = $_SESSION['user_id'];
    $status = $_POST['status'];
    if ( is_null($status) || $status == null || $status == '') {
        $status = $_SESSION['status'];
    }
    $newCustomerArray = array("referrer_name" => $referrer, "referrer_fee" => $referrerFee,
                  "builder_name" => $builder, "builder_package_amount" => $builderPackageAmount, "builder_land_amount" => $builderLandAmount, "builder_construction_amount" => $builderConstructionAmount,
                  "lender_name" => $lender, "lender_loan_amount" => $lenderLoanAmount, "lender_lvr" => $lenderLVR,
                  "prm_financial_broker" => $prmFinancialBroker, "prm_legals" => $prmLegals, "prm_builders" => $prmBuilders, "prm_financial_planner" => $prmFinancialPlanner, "prm_total" => $prmTotal,
                  "status" => $status);
    $oldCustArray = $_SESSION['oldCustomerArray'];
    $compareNewCustAndOldCust = array_diff_assoc($newCustomerArray, $oldCustArray);
    $updateCustomerQuery = "UPDATE customers SET ";
    $whereClauseForUpdateCustomerQuery = " WHERE id=$custId";
    $setClauseForUpdateCustomerQuery="";
    
    $systemLog="";
    $customerInfoArray = array("referrer_name" => "[Referrer/Name", "referrer_fee" => "[Referrer/Fee]",
                  "builder_name" => "[Builder/Name]", "builder_package_amount" => "[Builder/Package Value]", "builder_land_amount" => "[Builder/Land Amount]", "builder_construction_amount" => "[Builder/Construction Amount]",
                  "lender_name" => "[Lender/Name]", "lender_loan_amount" => "[Lender/Loan Amount]", "lender_lvr" => "[Lender/LVR]",
                  "prm_financial_broker" => "[PRM Commissions/Financial Broker]", "prm_legals" => "[PRM Commissions/Legals]", "prm_builders" => "[PRM Commissions/Builders]", "prm_financial_planner" => "[PRM Commissions/Financial Planner]", "prm_total" => "[PRM Commissions/Total]",
                  "status" => "[Status]");
    foreach ($compareNewCustAndOldCust as $key => $value) {
        $newValue = $newCustomerArray[$key];
        if ($newValue != null || $newValue != '') {
            if (is_numeric($newValue)) {
                $setClauseForUpdateCustomerQuery = $setClauseForUpdateCustomerQuery.$key."=".$newValue.", ";
            } else {
                $setClauseForUpdateCustomerQuery = $setClauseForUpdateCustomerQuery.$key.'="'.$newValue.'", ';
            }
            $systemLog = $systemLog."<em><span style='color:#FF0000'>*System comment:</span> <strong>".$customerInfoArray[$key]."</strong> changed from <strong>".$oldCustArray[$key]."</strong> to <strong>".$newValue."</strong>.. </em>";
        }
    }
    
    //get current date
    date_default_timezone_set('Asia/Bangkok');
    $currentDate = date('d/m/Y H:i');
    $username = $_SESSION['username'];
    $setClauseForUpdateCustomerQuery = $setClauseForUpdateCustomerQuery." date = '".$currentDate."', user_name='".$username."'";
    if ($setClauseForUpdateCustomerQuery != null || $setClauseForUpdateCustomerQuery != '') {
        $updateCustomerQuery = $updateCustomerQuery.$setClauseForUpdateCustomerQuery.$whereClauseForUpdateCustomerQuery;
//        echo $updateCustomerQuery."</br>";
//        $updateCustomerQuery = "UPDATE customers SET referrer_name='$referrer', referrer_fee=$referrerFee,
//            builder_name='$builder', builder_package_amount=$builderPackageAmount, builder_land_amount=$builderLandAmount, builder_construction_amount=$builderConstructionAmount,
//            lender_name='$lender', lender_loan_amount=$lenderLoanAmount, lender_lvr=$lenderLVR,
//            prm_financial_broker=$prmFinancialBroker, prm_legals=$prmLegals, prm_builders=$prmBuilders, prm_financial_planner=$prmFinancialPlanner, prm_total=$prmTotal,
//            status=$status WHERE id=$custId";
//        echo $updateCustomerQuery."</br>";

        $updateCustomerResult = mysql_query($updateCustomerQuery, $connection) or die(mysql_error() . "Can not store Customer to database");
        if (!$updateCustomerResult) {
            echo "<script>alert('Update customer failed');</script>";
            exit;
        }
    }
    $comment = $_POST['comment'];
    $addCommentQuery = "INSERT INTO comments(date, comment, cust_id, user_name) VALUES";
    if ($comment != null || $comment != '') {
        $addCommentQuery = $addCommentQuery.'("'.$currentDate.'", "'.$comment.'", '.$custId.', "'.$username.'"),';
    }
    if ($systemLog != "") {
        $addCommentQuery = $addCommentQuery.'("'.$currentDate.'", "'.$systemLog.'", '.$custId.', "'.$username.'")';
    }

    if ($addCommentQuery[strlen($addCommentQuery)-1] == ',') {
        $addCommentQuery = substr($addCommentQuery, 0 , -1);
    }
    if ($addCommentQuery != "INSERT INTO comments(date, comment, cust_id, user_name) VALUES") {
        $addCommentResult = mysql_query($addCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
        if ($addCommentResult) {
            echo "<script>alert('Add comment succeed');</script>";
        } else {
            echo "<script>alert('Add comment failed');</script>";
        }
    }
    
//    $customerId=$_SESSION['customerId'];
//    echo "<script>location.href = 'customersummary.php?tr=$customerId';</script>";
    //add customer
//    $updateCustomerQuery = "UPDATE customers SET referrer_name='$referrer', referrer_fee=$referrerFee,
//        builder_name='$builder', builder_package_amount=$builderPackageAmount, builder_land_amount=$builderLandAmount, builder_construction_amount=$builderConstructionAmount,
//        lender_name='$lender', lender_loan_amount=$lenderLoanAmount, lender_lvr=$lenderLVR,
//        prm_financial_broker=$prmFinancialBroker, prm_legals=$prmLegals, prm_builders=$prmBuilders, prm_financial_planner=$prmFinancialPlanner, prm_total=$prmTotal,
//        status=$status WHERE id=$custId";
//    $updateCustomerResult = mysql_query($updateCustomerQuery, $connection) or die(mysql_error() . "Can not store Customer to database");
//    if ($updateCustomerResult) {
//        //get current date
//        date_default_timezone_set('Asia/Bangkok');
//        $currentDate = date('d/m/Y H:i');
//        $username = $_SESSION['username'];
//        $comment = $_POST['comment'];
//        $addCommentQuery = "INSERT INTO comments(date, comment, cust_id, user_name) VALUES('$currentDate', '$comment', $custId, '$username')";
//        echo $addCommentQuery;
//        $addCommentResult = mysql_query($addCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
//        if ($addCommentResult) {
//            echo "<script>alert('Add customer succeed');</script>";
//            $customerId=$_SESSION['customerId'];
//            echo "<script>location.href = 'customersummary.php?tr=$customerId';</script>";
//        } else {
//            echo "<script>alert('Add customer failed');</script>";
//        }
//    }else {
//        echo "<script>alert('Add customer failed');</script>";
//    }
    unset($submit);
//    mysql_close($connection);
    ob_end_flush();
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD><TITLE>CLIENT PROFILE</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
<TITLE>Customer Summary</TITLE>
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
</HEAD>

<BODY>
<form name="addComment" onSubmit="return validateCustomer()" method="post">
<center><table width="1024px" border="1">
  <tr>
    <td><div align="right">
      <p>Welcome, <?php echo $_SESSION['username']; ?>!  <a href="index.php">Home</a>  <a href="logout.php">Log out</a></p></div>
      <p align="left"><strong>CUSTOMER SUMMARY</strong></p>
      <p align="left">Today's Date: <?php date_default_timezone_set('Asia/Bangkok'); echo date('d/m/Y');?> - Time: <?php echo date('H:i'); ?> </p>
      <table width="100%" border="0" bordercolor="#F0F0F0">
          <?php
          include_once 'dbconn.php';
          ob_start();
          $custId = $_GET['tr'];
          $_SESSION['customerId'] = $custId;
          $custId1 = substr($custId, 0, 1);
          $custId2 = substr($custId, 3);
          $custId = base64_decode($custId1.$custId2);
          $getCustomerQuery = "SELECT * FROM customers WHERE id = $custId";
          $customerResult = mysql_query($getCustomerQuery) or die(mysql_error() . "Can not retrieve Customers data");
          while ($customer = mysql_fetch_array($customerResult)) {
              $userId = $_SESSION['user_id'];
              $getUserOfCustQuery = "SELECT * FROM customers WHERE id = $custId AND user_id = $userId";
              $getUserOfCustResult = mysql_query($getUserOfCustQuery) or die(mysql_error() . "Can not retrieve Customers data");
              if (mysql_num_rows($getUserOfCustResult) > 0) {
                  $status = $customer['status'];
              } else {
                  $status = 0;
              }
              $_SESSION['status'] = $status;
              $oldCustArray = array("referrer_name" => $customer['referrer_name'], "referrer_fee" => $customer['referrer_fee'],
                  "builder_name" => $customer['builder_name'], "builder_package_amount" => $customer['builder_package_amount'], "builder_land_amount" => $customer['builder_land_amount'], "builder_construction_amount" => $customer['builder_construction_amount'],
                  "lender_name" => $customer['lender_name'], "lender_loan_amount" => $customer['lender_loan_amount'], "lender_lvr" => $customer['lender_lvr'],
                  "prm_financial_broker" => $customer['prm_financial_broker'], "prm_legals" => $customer['prm_legals'], "prm_builders" => $customer['prm_builders'], "prm_financial_planner" => $customer['prm_financial_planner'], "prm_total" => $customer['prm_total'],
                  "status" => $status);
              $_SESSION['oldCustomerArray'] = $oldCustArray;
              $status = 1;
          ?>
        <tr>
          <td colspan="2">- Customer ID: <span style="border-bottom:1px solid">
                  <input type="text" name="custId" id ="custId" value="<?php echo $customer['id'];?>" readonly />
          </span></td>
          <td>&nbsp;</td>
          <td colspan="3">Customer Name: <span style="border-bottom:1px solid">
                  <input type="text" name="custName" id="custName" value="<?php echo $customer['cust_name'];?>" readonly/>
          </span></td>
        </tr>
        <tr style="border-bottom:1px solid">
          <td colspan="6" style="border-bottom:1px solid">- <strong>Status</strong>: 
            <select name="status" <?php if ($_SESSION['username'] != 'admin') { echo 'disabled';} ?>>
              <option value="1" <?php if ($status == 1) { echo "selected='selected'";}?>>Activate</option>
              <option value="0" <?php if ($status == 0) { echo "selected='selected'";}?>>Deactivate</option>
          </select></td>
        </tr>
        <tr>
          <td width="10%" style="border-bottom:1px solid">- <strong>Referrer</strong>: </td>
          <td width="17%" style="border-bottom:1px solid"><input type="text" name="referrer_name" value="<?php echo $customer['referrer_name'];?>" <?php if ($status == 0) { echo 'disabled'; } ?> /></td>
          <td width="9%" style="border-bottom:1px solid">&nbsp;</td>
          <td colspan="3" style="border-bottom:1px solid">Referral Fee: $
          <input name="referrer_fee" type="text" id="referrer_fee" size="5" value="<?php echo $customer['referrer_fee']; ?>" <?php if ($status == 0) { echo 'disabled'; } ?> /></td>
        </tr>
        <tr>
          <td colspan="6" style="border-bottom:1px solid"> - <strong>Builder</strong>: 
          <input type="text" name="builder_name" id="builder_name" value="<?php echo $customer['builder_name']; ?>" <?php if ($status == 0) { echo 'disabled'; } ?>  /></td>
        </tr>
        <tr>
		  <td></td>	
          <td width="0%">&nbsp;</td>
		  <td width="0%">&nbsp;</td>
		  <td width="0%">&nbsp;</td>
          <!--<td>Package Amount:</td>-->
          <td colspan="3">Package Value:   $<input name="builder_packageAmount" type="text" id="builder_packageAmount" size="5" value="<?php echo $customer['builder_package_amount']; ?>" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="calPackageAmount()" readonly="true"  /></td>
      <!--    <td width="44%">&nbsp;</td>-->
        </tr>
        <tr>
          <td></td>
          <td>Land amount:</td>
          <td colspan="3">$<input name="builder_landAmount" type="text" id="builder_landAmount" size="5" value="<?php echo $customer['builder_land_amount']; ?>" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="calPackageAmount(); calLVR();"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td></td>
          <td>Construction Amount:</td>
          <td colspan="3">$<input name="builder_constructionAmount" type="text" id="builder_constructionAmount" value="<?php echo $customer['builder_construction_amount']; ?>" size="5" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="calPackageAmount();calLVR();"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" style="border-bottom:1px solid">- <strong>Lender</strong>:
          <input type="text" name="lender_name" id="lender_name" value="<?php echo $customer['lender_name'];?>" <?php if ($status == 0) { echo 'disabled'; } ?> /></td>
        </tr>
        <tr>
          <td></td>
            <td>Loan amount: </td>
            <td>$
              <input name="lender_loanAmount" type="text" id="lender_loanAmount" value="<?php echo $customer['lender_loan_amount']; ?>" size="5"  <?php if ($status == 0) { echo 'disabled'; } ?>  onchange="return calLVR()" /></td>
            <td width="7%">&nbsp;</td>
            <td colspan="2">LVR: 
              <input name="lender_lvr" type="text" id="lender_lvr" value="<?php echo $customer['lender_lvr']; ?>" size="5"  <?php if ($status == 0) { echo 'disabled'; } ?> readonly="true"/>%</td>
        </tr>
        <tr>
          <td colspan="6" style="border-bottom:1px solid"> - <strong>PRM Commission Receivable</strong>:</td>
        </tr>
        <tr>
          <td align="right"></td>
          <td>Financial/Broker: </td>
		  <td>$
          <input name="prm_financialBroker" type="text" id="prm_financialBroker" value="<?php echo $customer['prm_financial_broker']; ?>" size="5" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
          <td align="right"></td>
          <td>Legals: </td>
		  <td>$
          <input name="prm_legals" type="text" id="prm_legals" value="<?php echo $customer['prm_legals']; ?>" size="5" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
          <td></td>
          <td>Security/Builder: </td>
		  <td>$
          <input name="prm_builders" type="text" id="prm_builders" value="<?php echo $customer['prm_builders']; ?>" size="5" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
            <td></td>
            <td>Financial Planner: </td>
            <td>$
            <input name="prm_financialPlanner" type="text" id="prm_financialPlanner" value="<?php echo $customer['prm_financial_planner']; ?>" size="5" <?php if ($status == 0) { echo 'disabled'; } ?> onchange="return calTotalPRM()" /></td>
            <td>&nbsp;</td>
            <td colspan="2">Total: $<input name="prm_sum" type="text" id="prm_sum" value="<?php echo $customer['prm_total']; ?>" size="5"  <?php if ($status == 0) { echo 'disabled'; } ?> readonly="true"/></td>
        </tr>
          <?php } ?>
      </table>
      <p>Previous  Comments:</p>
      <table width="100%" border="1" bordercolor="#000000">
        <tr>
          <td width="2%"><strong>Cmt No.</strong></td>
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
          <td><?php echo $comment['id']; ?></td>
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
		<td colspan="5" style="border:hidden">
		</td>
      	</tr>
		<tr>
		<td colspan="5" style="border:hidden">
				</br></br></br>
		New Comment:
		</td>
      	</tr>
		<tr>
		<td colspan="5">
        <textarea name="comment" id="commnet" cols="150" style="max-width:inherit"></textarea>
		</td>
      	</tr>
      </table>
      <div align="center">
    <form id="form1" name="form1" method="post" action="" style="text-align:center">
        <input type="submit" name="Submit" value="Submit" onclick="return confirm('Are you sure you want to change ?')" />
      </form>
	  </div>
      <p align="left">&nbsp;</p>
    </td>
  </tr>
</table></center>
</form>
</BODY>
</HTML>