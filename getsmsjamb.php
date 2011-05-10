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
// check <JAMB number>
$txt = explode(" ", $msgtext);
$txt[0]; # Keyword
$txt[1]; # JAMB Number
$jnumber = strtoupper($txt[1]); # Make sure Number is Uppercased

$db_regno = $notify->notify_get_row_count("SELECT jambno FROM jamb WHERE jambno='$jnumber'");
    // If JAMB number matches then insert into DB
    if ($db_regno == 1){
    $wh = $notify->notify_query("INSERT INTO incoming_sms VALUES ('0', '$jnumber', '$msgtime', '$msgfrom')");
    $msgout = "";
    $dbres = $notify->notify_get_db_array("SELECT * FROM jamb WHERE jambno='$jnumber' LIMIT 1");

            foreach ( $dbres as $val ) {
                $jambno = $val['jambno'];
                $surname = $val['surname'];
                $firstname = $val['firstname'];
                $maths = $val['maths'];
                $english = $val['eng'];
                $physics = $val['phy'];
                $chemistry = $val['chem'];
                $total = $val['total'];
             }

         // Format Output
        $msgout .= "JAMB NO: $jambno $surname $firstname RESULT: Maths: $maths, Eng: $english, Phy: $physics, Chem: $chemistry SCORE: $total";
        echo $msgout;
     } else {

       $msgout .="$jnumber is not a Registered JAMB Number.";
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
