<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('db.php');
include ('utilities');
session_start();


$userid=$_SESSION['userid'];
$auction_id=$_POST['arguments'][0];
$functionname=$_POST['functionname'];

if (!isset($functionname) || !isset($auction_id)) {   
  return;
}

if ($functionname == "add_to_watchlist") {
    // Supposed to add auction item from the browse page to the watchlist and return success/failure

    $sql = "INSERT INTO user_watches (userid, auction_id) VALUES ('$userid', $auction_id)";
    $query = mysqli_query($conn, $sql); 
    $res = "success";
    }
else if ($_POST['functionname'] == "remove_from_watchlist") {
    // Supposed to remove auction item from the users watchlist and return success/failure

    $sql = "DELETE FROM 'user_watches' WHERE 'userid' = '$userid' AND 'auction_id' = '$auction_id'";
    $query = mysqli_query($conn, $sql); 
    $res = "success";
    }
  //userid
  //auction_id
  //create_date
  //user_watch_status
  // Extract arguments from the POST variables:
  
  

  // Note: Echoing from this PHP function will return the value as a string.
  // If multiple echo's in this file exist, they will concatenate together,
  // so be careful. You can also return JSON objects (in string form) using
  // echo json_encode($res).
  echo $res;

  ?>