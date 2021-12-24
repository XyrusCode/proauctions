<?php 
include_once("header.php");
require("utilities.php");
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.


  // TODO: Check user's credentials (cookie/session).

  // TODO: Perform a query to pull up their auctions.

  // TODO: Loop through results and print them out as list items.


$keyword = '';

//get variables, assign defaults, populate $asql that is used to add the additional filters to the query

$asql = 'WHERE 1=1';
if (!isset($_GET['keyword'])) {
} else {
  $keyword = $_GET['keyword'];
  if (trim($keyword) != '') {
    $asql .= " AND auction_name LIKE '%" . $keyword . "%'";
  }
}

if (!isset($_GET['cat'])) {
} else {
  $category = $_GET['cat'];
  if ($category != 'all') {
    $asql .= " AND category_id='" . $category . "'";
  }
}

if (!isset($_GET['order_by'])) {
  $ordering = 'date';
} else {
  $ordering = $_GET['order_by'];
}

if (!isset($_GET['expired'])) {
  $expired = 0;
  $asql .= ' AND end_date_time > UNIX_TIMESTAMP()';
} else {
  $expired = $_GET['expired'];
}

$asql.=" AND userid = ".$_SESSION['userid']."";

if (!isset($_GET['page'])) {
  $curr_page = 1;
  $orig_aasql=$asql;
  $asql.=' LIMIT 0,10';
} else {
  $curr_page = $_GET['page'];
  $orig_aasql=$asql;
  if ($curr_page!=1) {$tlimit=($curr_page-1)*10;} else {$tlimit=$curr_page;}
  $asql.=' LIMIT '.$tlimit.',10';
  
}

//get all info from the auctions table

$auctions = array();
$sql = "SELECT * FROM auctions " . $asql . "";
//echo $sql;

$r1 = $conn->query($sql);
$num_results = $conn->query("SELECT COUNT(*) as xcount FROM auctions ".$orig_aasql)->fetch_object()->xcount; // TODO: Calculate me for real
$results_per_page = 10;
$max_page = ceil($num_results / $results_per_page);


if ($r1->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($r1)) {
    $auctions[] = $row;
  }
}


//get all info for auctions that is not taken from the initial query (number of bids/currentprice/image)

if (count($auctions) > 0) {
  foreach ($auctions as $key => $auction) {
    $query = $conn->query("SELECT * FROM images WHERE auction_id='" . $auction['auction_id'] . "' LIMIT 1");
    if ($query->num_rows > 0) {
      $image = mysqli_fetch_assoc($query);
    } else {
      $image = 0;
    }
    $auctions[$key]['image'] = $image;
    $num_bids = $conn->query("SELECT COUNT(userid) AS xcount FROM bids WHERE auction_id='" . $auction['auction_id'] . "'")->fetch_object()->xcount;
    $auctions[$key]['num_bids'] = $num_bids;

    if ($num_bids > 0) {
      $price = $conn->query("SELECT bid_amount FROM bids WHERE auction_id='" . $auction['auction_id'] . "' ORDER BY bid_amount DESC LIMIT 1")->fetch_object()->bid_amount;
    } else {
      $price = $auction['start_price'];
    }
    $auctions[$key]['price'] = $price;
  }
}

//ordering the results 

//print_r($auctions)

if ($ordering == 'pricelow') { array_multisort(array_map(function ($element) {return $element['price'];}, $auctions), SORT_ASC, $auctions);
}elseif ($ordering == 'pricehigh') { array_multisort(array_map(function ($element) { return $element['price'];}, $auctions), SORT_DESC, $auctions);
} elseif ($ordering == 'date') { array_multisort(array_map(function ($element) {return $element['end_date_time']; }, $auctions), SORT_ASC, $auctions);
}

?>


<div class="container-fluid">
<h2 class="my-3" style="text-align:center; padding:10px;border:5px solid gray">My listings</h2></br>





<div class="container-fluid mt-5">

  <!-- If result set is empty, print an informative message. Otherwise... -->

  <ul class="list-group browse_display">

    <!-- Use a foreach loop to print a list item for each auction listing
     retrieved from the query -->

    <?php
    if (count($auctions) > 0) {
      foreach ($auctions as $auction) {
        print_listing_li($auction['auction_id'],$auction['auction_name'], $auction['image'], $auction['details'], $auction['price'], $auction['num_bids'], $auction['end_date_time']);
      }
    } else {
      echo 'No results. Please search again.';
    }

    ?>

  </ul>

      <?php

      // Copy any currently-set GET variables to the URL.
      $querystring = "";
      foreach ($_GET as $key => $value) {
        if ($key != "page") {
          $querystring .= "$key=$value&amp;";
        }
      }

      $high_page_boost = max(3 - $curr_page, 0);
      $low_page_boost = max(2 - ($max_page - $curr_page), 0);
      $low_page = max(1, $curr_page - 2 - $low_page_boost);
      $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

      if ($curr_page != 1) {
        echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
      }

      for ($i = $low_page; $i <= $high_page; $i++) {
        if ($i == $curr_page) {
          // Highlight the link
          echo ('
    <li class="page-item active">');
        } else {
          // Non-highlighted link
          echo ('
    <li class="page-item">');
        }

        // Do this in any case
        echo ('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
      }

      if ($curr_page != $max_page) {
        echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
      }
      ?>

    </ul>
  </nav>


</div>

  <?php include_once("footer.php") ?>