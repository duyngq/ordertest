<!-- Here is the entire code required to recreate the working 
     example you found in the article.

     If you're learning how to do this stuff, make one change 
     at a time, testing between each. Then, if something goes 
     awry, you know what change to reverse. -->

<!-- Below is the JavaScript that changes the display of the 
     tabs and panel content. Modify the div's for the tabs 
     and panel content before modifying this JavaScript. Once 
     those are done, you'll have the necessary id names to 
     modify the JavaScript.

<script type="text/javascript" language="JavaScript"><!--
function ManageTabPanelDisplay() {
//
// Between the parenthesis, list the id's of the div's that 
//     will be affected when tabs are clicked. List in any 
//     order. Put the id's in single quotes (apostrophes) 
//     and separate them with a comma -- all one line.
//
var idlist = new Array('tab1focus','tab2focus','tab3focus','tab1ready','tab2ready','tab3ready','content1','content2','content3');

// No other customizations are necessary.
if(arguments.length < 1) { return; }
for(var i = 0; i < idlist.length; i++) {
   var block = false;
   for(var ii = 0; ii < arguments.length; ii++) {
      if(idlist[i] == arguments[ii]) {
         block = true;
         break;
         }
      }
   if(block) { document.getElementById(idlist[i]).style.display = "block"; }
   else { document.getElementById(idlist[i]).style.display = "none"; }
   }
}
</script>



<!-- Below is the CSS for the example tab panel div tags. 
     You may, of course, change these according to your 
     design requirements.

     Refer to the Creating a Tab Panel article for notes 
     about this CSS.

<style type="text/css">
.tab { 
	font-family: verdana,sans-serif; 
	font-size: 14px;
	width: 100px;
	white-space: nowrap;
	text-align: center;
	border-style: solid;
	border-color: black;
	border-left-width: 1px;
	border-right-width: 1px;
	border-top-width: 1px;
	border-bottom-width: 0px;
	padding-top: 5px;
	padding-bottom: 5px;
	cursor: pointer;
	}
.tabhold { 
	background-color: white;
	color: black;
	}
.tabfocus { 
	background-color: black;
	color: white;
	}
.tabcontent { 
	font-family: sans-serif; 
	font-size: 14px;
	width: 400px;
	height: 275px;
	border-style: solid;
	border-color: black;
	border-width: 1px;
	padding-top: 15px;
	padding-left: 10px;
	padding-right: 10px;
	}
</style>



<!-- Below is the example tab panel. Notes are embedded in 
     the HTML, and the Creating a Tab Panel article also 
     contains information.

<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<div id="tab1focus" class="tab tabfocus" style="display:block;">
Introduction
</div>
<div id="tab1ready" class="tab tabhold" style="display:none;">
<!-- Between the parenthesis, provide a list of ids that are to 
     be visible when this tab is clicked. The ids are between 
     single quotes (apostrophes) and separated with a comma.
<span onclick="ManageTabPanelDisplay('tab1focus','tab2ready','tab3ready','content1')">Introduction</span>
</div>
</td><td width="20"> </td><td>
<div id="tab2focus" class="tab tabfocus" style="display:none;">
Anti-Hijack
</div>
<div id="tab2ready" class="tab tabhold" style="display:block;">
<!-- Between the parenthesis, provide a list of ids that are to 
     be visible when this tab is clicked. The ids are between 
     single quotes (apostrophes) and separated with a comma.
<span onclick="ManageTabPanelDisplay('tab1ready','tab2focus','tab3ready','content2')">Anti-Hijack</span>
</div>
</td><td width="20"> </td><td>
<div id="tab3focus" class="tab tabfocus" style="display:none;">
About
</div>
<div id="tab3ready" class="tab tabhold" style="display:block;">
<!-- Between the parenthesis, provide a list of ids that are to 
     be visible when this tab is clicked. The ids are between 
     single quotes (apostrophes) and separated with a comma.
<span onclick="ManageTabPanelDisplay('tab1ready','tab2ready','tab3focus','content3')">About</span>
</div>
</td><td width="180"> </td><td>
</tr>
<tr>
<td colspan="6">
<div id="content1" class="tabcontent" style="display:block;">
<p style="margin-top:0px">
Form hijacking can get accounts closed down.
</p>
<p>
Just last week, another client was informed by the hosting company that a 
script is being compromised and to fix it. 
If left unfixed, the hosting account would be shut down.
</p>
<p>
Such draconian attitudes are not nice. But what is a hosting 
company to do? A script is being hijacked to send thousands and 
thousands of messages from one of their accounts. Not only is 
it using server resources, but their IP addresses are in danger 
of being put on black lists.
</p>
<p>
Something must be done.
</p>
<p style="margin-bottom:0px">
Something has been done.
</p>
</div>
<div id="content2" class="tabcontent" style="display:none;">
<p style="margin-top:0px">
<a href="/master/formV4/" target="_blank" style="font-weight:bold;">Master Form V4</a> 
blocks form hijacking.
</p>
<p>
This software is one of the most sophisticated form handlers available 
on the Internet. Yet, it is easy to implement.
</p>
<p>
And it has anti-hijacking code built right in.
</p>
<p>
The software also helps spam-proof your web site so email 
harvesting robots find nothing when they crawl your site.
</p>
<p style="margin-bottom:0px">
With 
<a href="/master/formV4/" target="_blank" style="font-weight:bold;">Master Form V4</a>, 
you can put your full attention on running your business, and sleep well at night, 
without worrying about some cracker/spammer hijacking your forms and using your 
server to spam thousands and thousands of unwilling recipients.
</p>
</div>
<div id="content3" class="tabcontent" style="display:none;">
<div style="margin: 0px 20px 10px 0px; width: 80px; height: 105px; float: left;"><img src="/images/20000710w80105.jpg" alt="author" border="0" height="105" width="80"></div>
<p style="margin-top:0px">
The anti-hijacking code built into 
<a href="/master/formV4/" target="_blank" style="font-weight:bold;">Master Form V4</a> 
was developed while a hijacking was in full progress.
</p>
<p>
I noticed unusual activity on the server and determined a script 
was being used to send spam. Quickly, I replaced that script with one that would 
record everything sent to it but would not send email.
</p>
<p>
During the hijacking, which continued for hours, I developed and tweaked code to 
block that very thing.<!-- The code was developed and tested and tweaked and tested 
again, using the live hijacking to measure effectiveness.
</p>
<p>
Get some peace of mind. Get <a href="/master/formV4/" target="_blank" style="font-weight:bold;">Master Form V4</a>.
</p>
<p style="margin-bottom:0px">
Will Bontrager, Programmer<br><a href="/">Willmaster.com</a>
</p>
</div>

</td></tr>
</table>
-->
<?php
$a='';
if (isset($a)) {
    echo 'ok';
} else {
    echo 'non-ok';
}

if (!empty($a)) {
    echo 'ok';
} else {
    echo 'non-ok';
}
//$array1 = array("a" => "green", "red", "blue", "red");
//$array2 = array("b" => "green", "yellow", "red");
//$result = array_diff($array1, $array2);
//
//print_r($result);
//echo '=================================================';
//$array1 = array("a" => "green", "b" => "red", "c" => "blue", "d" => "a");
//$array2 = array("a" => "green", "b" => "yellow", "c" => "red", "d" => "abc");
//$result = array_diff($array1, $array2);
//foreach ($result as $key => $value) {
//    echo $key;
//    echo $value;
//}
//print_r($result);
//$a = array ( "id" => 6, "custName" => d, "referrer_name" => test, "referrer_fee" => 76, "builder_name" => test, "builder_package_amount" => 24, "builder_land_amount" => 12, "builder_construction_amount" => 67, "lender_name" => asd, "lender_loan_amount" => 24, "lender_lvr" => 100, "prm_financial_broker" => 25, "prm_legals" => 25, "prm_builders" => 25, "prm_financial_planner" => 25, "prm_total" => 100, "user_id" => 2, "status" => 1 );
//$b = array ( "id" => 6, "custName" => d2343, "referrer_name" => czxczxc, "referrer_fee" => 76, "builder_name" => test, "builder_package_amount" => 24, "builder_land_amount" => 12, "builder_construction_amount" => 67, "lender_name" => duy, "lender_loan_amount" => 35, "lender_lvr" => 95.83, "prm_financial_broker" => 24, "prm_legals" => 87, "prm_builders" => 25, "prm_financial_planner" => 25, "prm_total" => 100, "user_id" => 2, "status" => 1 );
//$c = array_diff($a, $b);
//foreach ($c as $key => $value) {
//    echo $key;
//    echo '</br>';
//    echo $value;
//}
//echo "</br>";
//echo "</br>";
//$d = array_diff_assoc($a, $b);
//print_r($d);
//foreach ($d as $key => $value) {
//    echo $key."=>".$value."</br>";
//    echo 'a array: '.$key.'=>'.$b[$key]."</br>";
//    exit;
//}
?>