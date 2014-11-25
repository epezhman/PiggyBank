<?php

try{
// Some basic access control checks
  ob_start();
    require("accesscontrol.php");
    if(ob_get_clean() == -1){
        header("Location: ../error.php?id=404");
        exit();
    }

    // Pear Mail Library
    require_once "Mail.php";

    $from = "noreply@piggybank.de";
    $to = $_POST["emailTo"];
    $subject = $_POST["emailSubject"];
    $body = $_POST["emailBody"];

$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);

$smtp = Mail::factory('smtp', array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => '465',
        'auth' => true,
        'username' => 'piggybankgmbh@gmail.com',
        'password' => 'optimus_159_prime'
    ));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
    echo($mail->getMessage());
} else {
    echo('SUCCESS');
}
}catch(Exception $e){
    header("Location: ../error.php?id=404");
    exit();
}

?>
