<?php
// Reference NotifyRSClass
require_once('NotifyRSClass.php');

// Disable Error Reporting
//error_reporting(0);

// Grab Kannel parameters and store in $_GET super globals
$msgfrom = $_GET['msgfrom'];
$msgtext = $_GET['msgtext'];

// Create a new fetchSMS object
$notify = new fetchSMS();

//Format Grabbed Text Content
// chequeno fname lname acctno dateofissue amount pin
$txt = explode(" ", $msgtext);
$txt[0]; # Keyword
$txt[1]; # Cheque Number
$txt[2]; # First Name
$txt[3]; # Last name
$txt[4]; # Account Number
$txt[5]; # Date of Issue
$txt[6]; # Amount
$txt[7]; # PIN

//########################
// Emulate Web service call from Bank
// AcctNo 	PhoneNo 	FirstName 	LastName 
// Get record count from DB
//$db_ws = $notify->notify_get_row_count("SELECT AcctNo, PhoneNo FROM webservice WHERE AcctNo='$txt[4]' AND PhoneNo='$msgfrom' LIMIT 1");
$response = "";
// Encrypt PIN and comapre in DB
   $enctxt = md5($txt[7]);
   $db_pin = $notify->notify_get_row_count("SELECT Pin FROM storepin WHERE pin='$enctxt' AND AcctNo='$txt[4]'");
    // If PIN matches correctly then insert into DB
    if ($db_pin == 1){
        // Insert SMS details into DB
        $wh = $notify->notify_query("INSERT INTO chequedetails VALUES ('$txt[1]', '$txt[2]', '$txt[3]', '$txt[4]', '$txt[5]', '$txt[6]', md5('$txt[7]'), '$msgfrom', '0', '0', '0', '0', '0', now())");
        // Set Success Message
        if($wh){
        $response .= "Successfully registered cheque $txt[1]";
        echo $response;
        } else {
            //exit
            exit;
        }
    }


//#######################

// Send the SMS through IP SMS (UTIWARE API)
//$notify->send_sms_to_ipsms($msgfrom, rawurlencode($response));

// Update processed SMS message
//$notify->notify_query("UPDATE sms2 SET processed = 1 WHERE number='$number' AND insertdate='$insertmsgtime' LIMIT 1");

?>
