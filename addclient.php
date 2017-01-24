<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
if (!isset($_SESSION['loggedIn']) || (isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'])) {
    header("location:login.php");
}
include_once 'dbconn.php';
//add customer to DB and redirect to index.php
if (isset($_POST["Submit"])) {
    $submit = $_POST["Submit"];
}
if (isset($submit)) {
    unset($_POST["Submit"]);

    $custId = $_POST["custId"];
    $custName = $_POST["custName"];
    
    $referrer = $_POST["referrer"];
    $referrerFee = $_POST["referrerFee"];
    if (!is_numeric($referrerFee)) {
        echo "<script>alert('Referrer Fee should be a number!!!!')</script>";
        exit;
    } else if ($referrerFee < 0) {
        echo "<script>alert('Referrer Fee should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    $builder = $_POST["builder"];
    $builderPackageAmount = $_POST["builder_packageAmount"];
    $builderLandAmount = $_POST["builder_landAmount"];
    $builderConstructionAmount = $_POST["builder_constructionAmount"];
    if (!is_numeric($builderPackageAmount)) {
        echo "<script>alert('Builder Package Amount should be a number!!!!')</script>";
        exit;
    } else if ($builderPackageAmount < 0) {
        echo "<script>alert('Builder Package Amount should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    if (!is_numeric($builderLandAmount)) {
        echo "<script>alert('Builder Land Amount should be a number!!!!')</script>";
        exit;
    } else if ($builderLandAmount < 0) {
        echo "<script>alert('Builder Land Amount should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    if (!is_numeric($builderConstructionAmount)) {
        echo "<script>alert('Builder Construction Amount should be a number!!!!')</script>";
        exit;
    } else if ($builderConstructionAmount < 0) {
        echo "<script>alert('Builder Construction Amount should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    $lender = $_POST["lender"];
    $lenderLoanAmount = $_POST["lender_loanAmount"];
    $lenderLVR = $_POST["lender_lvr"];
    if (!is_numeric($lenderLoanAmount)) {
        echo "<script>alert('Lender Loan Amount should be a number!!!!')</script>";
        exit;
    } else if ($lenderLoanAmount < 0) {
        echo "<script>alert('Lender Loan Amount should be greater or equal 0!!!!')</script>";
        exit;
    }
    
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
    
    $prmCommission = $_POST["prm_commission"];
    $prmFinancialBroker = $_POST["prm_financialBroker"];
    $prmLegals = $_POST["prm_legals"];
    $prmBuilders = $_POST["prm_builders"];
    $prmFinancialPlanner = $_POST["prm_financialPlanner"];
    $prmTotal = $_POST["prm_sum"];
    
    if (!is_numeric($prmFinancialBroker)) {
        echo "<script>alert('PRM Financial/Broker should be a number!!!!')</script>";
        exit;
    } else if ($prmFinancialBroker < 0) {
        echo "<script>alert('PRM Financial/Broker should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    if (!is_numeric($prmLegals)) {
        echo "<script>alert('PRM Legals should be a number!!!!')</script>";
        exit;
    } else if ($prmLegals < 0) {
        echo "<script>alert('PRM Legals should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    if (!is_numeric($prmBuilders)) {
        echo "<script>alert('PRM Builders should be a number!!!!')</script>";
        exit;
    } else if ($prmBuilders < 0) {
        echo "<script>alert('PRM Builders should be greater or equal 0!!!!')</script>";
        exit;
    }
    
    if (!is_numeric($prmFinancialPlanner)) {
        echo "<script>alert('PRM Financial Planner should be a number!!!!')</script>";
        exit;
    } else if ($prmFinancialPlanner < 0) {
        echo "<script>alert('PRM Financial Planner should be greater or equal 0!!!!')</script>";
        exit;
    }
    
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

    //add customer
    $userId = $_SESSION['user_id'];
    //get current time
    date_default_timezone_set('Australia/Melbourne');
    $currentDate = date('d/m/Y H:i');
    $username = $_SESSION['username'];
    $addCustomerQuery = "insert into customers values ($custId, '$custName', '$referrer', $referrerFee,
        '$builder', $builderPackageAmount, $builderLandAmount, $builderConstructionAmount,
        '$lender', $lenderLoanAmount, $lenderLVR,
        $prmFinancialBroker, $prmLegals, $prmBuilders, $prmFinancialPlanner, $prmTotal, 
        $userId, 1, '$currentDate', '$username', '$prmCommission', 0, 0, 0, 0)";
    $addCustomerResult = mysql_query($addCustomerQuery, $connection) or die(mysql_error() . "Can not store Customer to database");
    if ($addCustomerResult) {
        $comment="<em><span style='color:#FF0000'>*System comment:</span> New file created.. </em>";
        $addCommentQuery = 'INSERT INTO comments(date, comment, cust_id, user_name) VALUES("'.$currentDate.'", "'.$comment.'", '.$custId.', "'.$username.'")';
        $addCommentResult = mysql_query($addCommentQuery, $connection) or die(mysql_error() . "Can not store comment to database");
        if ($addCommentResult) {
            echo "<script>alert('Add customer succeed');</script>";
            echo "<script>location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Add customer failed');</script>";
        }
        
    }else {
        echo "<script>alert('Add customer failed');</script>";
    }
    mysql_close($connection);
    ob_end_flush();
    unset($submit);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
<TITLE>Add New Client</TITLE>
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
<form name="addClient" onSubmit="return validation()" method="post">
<center><table width="1024px" border="1">
  <tr>
    <td><div align="right">
      <p>Welcome, <?php echo $_SESSION['username']; ?>!  <a href="index.php">Home</a>  <a href="logout.php">Log out</a></p>
      <p align="left"><strong>ADD NEW CLIENT </strong></p>
      <p align="left">Today's Date: <?php date_default_timezone_set('Australia/Melbourne'); echo date('d/m/Y');?> - Time: <?php echo date('H:i'); ?> </p>
      <table width="100%" border="0" bordercolor="#F0F0F0">
        <tr>
          <td>- Customer ID:</td>
          <td><input name="custId" type="text" id="custId" value="<?php 
          ob_start();
          $sql = "SELECT id FROM customers ORDER BY id desc limit 0,1";
          $result = mysql_query($sql);
          $id;
          while ($cust = mysql_fetch_array($result)) {
              $id = $cust['id'] + 1;
          }
          if ($id == null) {
              $id = 1;
          }
          echo $id;
          mysql_close($connection);
          ob_end_flush(); ?>" size="60" readonly="true"/></td>
        </tr>
        <tr>
          <td>- Customer Name:</td>
          <td><input name="custName" type="text" id="custName" size="60" /></td>
        </tr>
        <tr>
          <td>- Referrer:</td>
          <td><input name="referrer" type="text" id="referrer" size="60"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Referral Fee:</p>
          </blockquote></td>
            <td><input name="referrerFee" type="text" id="referrerFee" value="0" size="60"/></td>
        </tr>
        <tr>
          <td> - Security:</td>
          <td><input name="builder" type="text" id="builder" size="60"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>	Package Value:</p>
          </blockquote></td>
          <td><input name="builder_packageAmount" type="text" id="builder_packageAmount" value="0" size="60" onchange="calPackageAmount()" readonly="true" />
          <input name="hidden_packageAmount" type="hidden" id="builder_packageAmount" value="[auto = sum (Land + Construction)" size="1" readonly="true"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Land amount:</p>
          </blockquote></td>
          <td><input name="builder_landAmount" type="text" id="builder_landAmount" value="0" size="60" onchange="calPackageAmount(); calLVR();"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Construction Amount:</p>
          </blockquote></td>
          <td><input name="builder_constructionAmount" type="text" id="builder_constructionAmount" value="0" size="60" onchange="calPackageAmount();calLVR();"/></td>
        </tr>
        <tr>
          <td>- Lender:</td>
          <td><input name="lender" type="text" id="lender" size="60"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Loan amount: </p>
          </blockquote></td>
            <td><input name="lender_loanAmount" type="text" id="lender_loanAmount" value="0" size="60" onchange="return calLVR()"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>LVR: (*)</p>
          </blockquote></td>
            <td><input name="lender_lvr" type="text" id="lender_lvr" value="" size="60" readonly="true"/>
              <input name="hidden1" type="hidden" id="lender_lvr_1" value="[auto = (Loan amount) / (Package Amount) ]" size="1" readonly="true"/></td>
        </tr>
        <tr>
          <td> - PRM Commission Receivable:</td>
          <td><input name="prm_commission" type="text" id="prm_commission" size="60"/></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Financial/Broker:</p>
          </blockquote></td>
            <td><input name="prm_financialBroker" type="text" id="prm_financialBroker" value="0" size="60" onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Legals:</p>
          </blockquote></td>
          <td><input name="prm_legals" type="text" id="prm_legals" value="0" size="60" onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Security/Builder:</p>
          </blockquote></td>
          <td><input name="prm_builders" type="text" id="prm_builders" value="0" size="60" onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
          <td><blockquote>
            <p>Financial Planner:</p>
          </blockquote></td>
          <td><input name="prm_financialPlanner" type="text" id="prm_financialPlanner" value="0" size="60" onchange="return calTotalPRM()" /></td>
        </tr>
        <tr>
          <td><blockquote>
            <blockquote>
              <p>Total (*)</p>
            </blockquote>
          </blockquote></td>
          <td><input name="prm_sum" type="text" id="prm_sum" value="" size="60" readonly="true"/>
          <input name="hidden2" type="hidden" value="[auto = sum(Above)] " size="1" readonly="true"/></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <div align="center">
	      <form id="form1" name="form1" method="post" action="" style="text-align:center">
	        <input type="submit" name="Submit" value="Save" />
	      </form>
      </div>
      <p align="left">&nbsp;</p>
    </div></td>
  </tr>
</table></center>
</form>
</BODY>
</HTML>
