<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db.php';
session_start();

$errors=array();

//define variables for create_auction
$category_id=trim($_POST['category_id']); 
$auction_name=$conn->real_escape_string(trim($_POST['auction_name']));
$details=$conn->real_escape_string(trim($_POST['details']));
$start_price=$_POST['start_price'];
$reserve_price=$_POST['reserve_price'];
$quantity=$_POST['quantity'];
$auction_duration=$_POST['auction_duration'];

$dur=explode(' - ',$auction_duration); // split date start/end into 2
$d['start_date_time']=strtotime(trim($dur[0]));
$d['end_date_time']=strtotime(trim($dur[1]));

//sql code to insert an auction form into database

$sql = "INSERT INTO `auctions` (`auction_id`, `userid`, `category_id`, `item_id`, `auction_name`, `details`, `start_date_time`, `end_date_time`, `start_price`, `reserve_price`, `currency_id`, `auction_status`, `shipping_method_id`, `quantity`) 
VALUES ('', '".$_SESSION['userid']."', '".$category_id."', NULL, '".$auction_name."', '".$details."', '".$d['start_date_time']."', '".$d['end_date_time']."', '".$start_price."', '".$reserve_price."', '1', '1', '1', '".$quantity."')" ;


$conn->query($sql);
$auction_id=$conn->insert_id;


//SAVE IMAGES TO FOLDER--------------------------------------------------
if ($_FILES["imgupload"]["name"]!=''){
$target_dir = "../images/auctions/";
$parts=pathinfo(basename($_FILES["imgupload"]["name"]));

$final_title=strtolower($parts['filename']).'-'.$auction_id.'.'.strtolower($parts['extension']);
$target_file = $target_dir . $final_title;
$uploadOk = 1;
$imageFileType = strtolower($parts['extension']);


// ALLOW CERTAIN FILE FORMATS
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to larger 0 by an error
if ($uploadOk == 0) {
  $errors[]= "Sorry, your file was not uploaded.";

// if everything is ok, try to upload file
} else {
if (move_uploaded_file($_FILES["imgupload"]["tmp_name"], $target_file)) {
  echo "The file ". htmlspecialchars( basename( $_FILES["imgupload"]["name"])). " has been uploaded.";
  $conn->query("INSERT INTO `images` VALUES ('','".$auction_id."','','".$final_title."')");
} else {
    $errors[]="Sorry, there was an error uploading your image.";
   }
}
}
//END IMAGE-----------------------------------------------------------

if (count($errors)>0) {
    foreach ($errors as $err) {
        echo '<br>'.$err;
    }
}
else {
echo '<br>Auction id is '.$auction_id;
}

