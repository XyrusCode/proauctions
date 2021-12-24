<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db.php';
session_start();

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.
//print_r($_REQUEST);


//insert profile registration into database
$username=$conn->real_escape_string(trim($_POST['username']));
$password=$conn->real_escape_string(trim($_POST['password']));
$firstname=$conn->real_escape_string(trim($_POST['firstname']));
$lastname=$conn->real_escape_string(trim($_POST['lastname']));
$address_1=$conn->real_escape_string(trim($_POST['address_1']));
$address_2=$conn->real_escape_string(trim($_POST['address_2']));
$country_id=$conn->real_escape_string(trim($_POST['country_id']));
$city=$conn->real_escape_string(trim($_POST['city']));
$post_code=$conn->real_escape_string(trim($_POST['post_code']));
$email_address=$conn->real_escape_string(trim($_POST['email_address']));
$phone_number=$conn->real_escape_string(trim($_POST['phone_number']));



$sql="INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `address_1`, `address_2`, `city`, `country_id`, `post_code`, `currency_id`, `phone_number`, `company`, 
`email_address`, `is_buyer`, `is_seller`) 
VALUES ('', '".$username."', '".$password."', '".$firstname."', '".$lastname."', '".$address_1."', '".$address_2."', '".$city."', '".$country_id."', '".$post_code."', '', '".$phone_number."', '', '".$email_address."', '0', '0');";

//echo $sql;

$conn->query($sql); 
$userid=$conn->insert_id;

//update database if account registered as seller or buyer 

if ($_REQUEST['accountType']=='seller') {$sql="UPDATE users SET is_seller=1 WHERE userid='".$userid."'"; }
else $sql="UPDATE users SET is_buyer=1 WHERE userid='".$userid."'";

$conn->query($sql);

$_SESSION['userid']=$userid;
$_SESSION['username'] = trim($_REQUEST['username']);
$_SESSION['account_type'] = $_REQUEST['accountType'];
$_SESSION['logged_in']= true; 

//setting up mail at registration (incomplete)

//echo 'account added';
//echo 'Password don\'t match';
/* -- mail functionction - does not work on WAMP, only external server
$to = $_REQUEST['email_address'];
$subject = "Welcome to ProAuctions!";
$txt = "Welcome! Welcome! Welcome!";
$headers = "From: none@proauctions.co.uk" . "\r\n";

mail($to,$subject,$txt,$headers);
*/

echo 'ok';
exit();

?>