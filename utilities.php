 <?php
  
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

  if ($interval->days == 0 && $interval->h == 0) {
    // Less than one hour remaining: print mins + seconds:
    $time_remaining = $interval->format('%im %Ss');
  }
  else if ($interval->days == 0) {
    // Less than one day remaining: print hrs + mins:
    $time_remaining = $interval->format('%hh %im');
  }
  else {
    // At least one day remaining: print days + hrs:
    $time_remaining = $interval->format('%ad %hh');
  }

return $time_remaining;

}



// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id,$title,$image, $desc, $price, $num_bids, $end_time)
{
  global $conn;
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }


// IMAGE DISPLAY FOR EACH AUCTION --------------------------------------------------------------------------------
$query = $conn->query("SELECT * FROM images WHERE auction_id='" . $item_id . "' LIMIT 1");
if ($query->num_rows > 0) {
  $image = mysqli_fetch_assoc($query);
  $img = 'images/auctions/' . $image['title'];
} else {
  $img = 'images/default.jpg';
}



 // Calculate time to auction end
 $now = new DateTime();
 $end_time=new DateTime(date("Y-m-dTH:i:s",$end_time));
 if ($now > $end_time) {
   $time_remaining = 'This auction has ended';
 }
 else {
   // Get interval:
   global $time_to_end;
   $time_to_end = date_diff($now, $end_time);
   $time_remaining = display_time_remaining($time_to_end) . ' remaining';
 }
//  Displays info about Auction in browsing mode
  echo('
  <li class="list-group-item d-flex justify-content-between" onclick="location.href=\'listing.php?item_id=' . $item_id . '\'">
  <div class="p-2 mr-5"><h5><img style="max-height:100px" src="' . $img . '"></div>
  <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
  <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>Bids# ' . $num_bids .'<br/>' . $time_remaining . '</div>
  </li>
  </a>');

}



// WATCHLIST SESSION CHECK--------------------------------------------------------------------------------
// TODO: If the user has a session, use it to make a query to the database
//       to determine if the user is already watching this item.
//       For now, this is hardcoded.
if (isset($_SESSION['userid'])) {
  $has_session = true;
} else {
  $has_session = false;
}
$watching = false;




// If I am logged in AND I created the auction AND the edit button is clicked, then return true 
// (used in listing to give sellers option to edit their listings)
function check_editor() { 
  $ismyauction=true;
  if (isset($_SESSION['account_type']) && $ismyauction && (isset($_GET['action']) && $_GET['action']=='edit')) {
  return true;
  }
  return false;
  }

//If I am logged in AND the edit button is clicked, then return true   
function check_profile() {
  $ismyprofile=true;
  if (isset($_SESSION['account_type']) && $_GET['action']=='edit') {
return true;
  }
return false;
}

//Debug helper
function eprint($array) {
  echo '<pre>';print_r($array);echo'</pre>';
}


//Most Popular Auctions and Recommended SQL display (grabs the auctions from database)
function grab_auctions($type) {
  global $conn;
  $auctions = array();
$query = "SELECT * FROM auctions LIMIT 5";
if ($type=='recommended') {$query = "SELECT * FROM auctions LIMIT 5";}

$result = $conn->query($query);
if ($result) {
  if ($result->num_rows > 0) {
  } else {
    echo "No record found";
  }
} else {
  echo "Error in " . $query . "<br>" . $conn->error;
}
//print_r($query);


if ($result->num_rows) {
  while ($row = mysqli_fetch_assoc($result)) {
    $auctions[] = $row;
  }
}

//DISPLAY POPULAR AUCTIONS (number of bids/currentprice/image)
if (count($auctions)) {
  foreach ($auctions as $key => $auction) {
    $query = $conn->query("SELECT * FROM images WHERE auction_id='" . $auction['auction_id'] . "' LIMIT 1");
    if ($query->num_rows) {
      $image = mysqli_fetch_assoc($query);
    } else {
      $image = 0;
    }
    $auctions[$key]['image'] = $image;
    $num_bids = $conn->query("SELECT COUNT(userid) AS xcount FROM bids WHERE auction_id='" . $auction['auction_id'] . "'")->fetch_object()->xcount;
    $auctions[$key]['num_bids'] = $num_bids;

    // Calculate the display price
    if ($num_bids) {
      $price = $conn->query("SELECT bid_amount FROM bids WHERE auction_id='" . $auction['auction_id'] . "' ORDER BY bid_amount DESC LIMIT 1")->fetch_object()->bid_amount;
    } else {
      $price = $auction['start_price'];
    }
    $auctions[$key]['price'] = $price;
  }
}

if ($type=='recommended') {
array_multisort(array_map(function ($element) { return $element['num_bids'];}, $auctions), SORT_DESC, $auctions);
} 
else { //if type=='most popular'
array_multisort(array_map(function ($element) { return $element['num_bids'];}, $auctions), SORT_DESC, $auctions);
}
return $auctions;

}



?>

