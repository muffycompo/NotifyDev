<?php
// Reference NotifyRSClass
require_once('NotifyRSClass.php');

// Disable Error Reporting
//error_reporting(0);

// Grab Kannel parameters and store in $_GET super globals
$msgfrom = $_GET['msgfrom'];
$msgtext = $_GET['msgtext'];
$msgtime = $_GET['msgtime'];

// Create a new fetchSMS object
$notify = new fetchSMS();

//Format Grabbed Text Content
// verify <NAFDAC number>
$txt = explode(" ", $msgtext);
$txt[0]; # Keyword
$txt[1]; # NAFDAC Number

$db_regno = $notify->notify_get_row_count("SELECT reg_no FROM nafdac WHERE reg_no='$txt[1]'");
    // If NAFDAC number matches then insert into DB
    if ($db_regno == 1){
    $wh = $notify->notify_query("INSERT INTO incoming_sms VALUES ('0', '$txt[1]', '$msgtime', '$msgfrom')");
    $msgout = "";
    $dbres = $notify->notify_get_db_array("SELECT * FROM nafdac WHERE reg_no='$txt[1]' LIMIT 1");

            foreach ( $dbres as $val ) {
                $reg_no = $val['reg_no'];
                $company_name = $val['company_name'];
                $product_name = $val['product_name'];
                $product_desc = $val['product_desc'];
             }

         // Format Output
        $msgout .= "NAFDAC NO: $reg_no, CO. NAME: $company_name, PROD. NAME:  $product_name";
        echo $msgout;
     } else {

       $msgout .="$txt[1] is not a Registered/Valid NAFDAC Number.";
       echo $msgout;
       //Stop Script
       exit;
     }

//#######################

// Send the SMS through IP SMS (UTIWARE API)
//$notify->send_sms_to_ipsms($msgfrom, rawurlencode($response));

// Update processed SMS message
//$notify->notify_query("UPDATE sms2 SET processed = 1 WHERE number='$number' AND insertdate='$insertmsgtime' LIMIT 1");

?>
