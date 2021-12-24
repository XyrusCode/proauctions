<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db.php';
session_start();

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// TODO: Notify user of success/failure and redirect/give navigation options.

$userid=$_SESSION['userid'];
$auction_id=$_POST['auction_id'];
$bid_amount=$_POST['bid_amount'];

$query=$conn->query("SELECT * FROM bids WHERE auction_id='".$auction_id."' ORDER BY bid_amount DESC LIMIT 1");
$row=mysqli_fetch_assoc($query);
$current_price=$row['bid_amount'];



if ($bid_amount >$current_price){
$sql = "INSERT INTO `bids` VALUES ('','".$userid."', '".$auction_id."', UNIX_TIMESTAMP(), '".$bid_amount."' )" ;

$history_row='
<tr><td>'.$_SESSION['username'].'</td>
<td>'.date("d-m-Y h:i:s", time()).'</td>
<td>£'.number_format($bid_amount,'2') .'</td>
</tr>
';


$query = mysqli_query($conn, $sql); 
	echo '<span style="color:green">Success! Your bid has been recorded!</span>|'.$bid_amount.'|'.$history_row;

}

else {
	echo '<span style="color:red">There was an error. Your bid was not recorded.</span>';
}	



?>