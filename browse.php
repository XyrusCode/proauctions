<?php include_once("header.php"); ?>
<?php require("utilities.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$keyword = '';

//get variables, assign defaults, populate $asql that is used to add the additional filters to the query
// Search bar func
$asql = 'WHERE 1=1';
if (!isset($_GET['keyword'])) {
} else {
  $keyword = $_GET['keyword'];
  if (trim($keyword) != '') {
    $asql .= " AND auction_name LIKE '%" . $keyword . "%'";
  }
}
//Category func
if (!isset($_GET['cat'])) {
} else {
  $category = $_GET['cat'];
  if ($category != 'all') {
    $asql .= " AND category_id='" . $category . "'";
  }
}
//Order by func
if (!isset($_GET['order_by'])) {
  $ordering = 'date';
} else {
  $ordering = $_GET['order_by'];
}
//Include expired func
if (!isset($_GET['expired'])) {
  $expired = 0;
  $asql .= ' AND end_date_time > UNIX_TIMESTAMP()';
} else {
  $expired = $_GET['expired'];
}
//Items per page  display limit
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
global $auctions;
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

//eprint($auctions);  //eprint defined in utilities, just a more human-readable print, debug purposes

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

if ($ordering == 'pricelow') { array_multisort(array_map(function ($element) {return $element['price'];}, $auctions), SORT_ASC, $auctions);
} elseif ($ordering == 'pricehigh') { array_multisort(array_map(function ($element) { return $element['price'];}, $auctions), SORT_DESC, $auctions);
} elseif ($ordering == 'date') { array_multisort(array_map(function ($element) {return $element['end_date_time'];}, $auctions), SORT_ASC, $auctions);
}

//eprint($auctions);  //eprint defined in utilities, just a more human-readable print, debug purposes


?>

<!-- CONTAINER STARTS -->
<div class="container-fluid">
  <h2 class="my-3" style="text-align:center; padding:10px; border:5px solid gray">Browse listings</h2></br>

  <!-- <nav aria-label="Search results pages" class="mt-5">
    <ul class="pagination justify-content-center"> -->

  <div id="searchSpecs">
    <form method="get" action="browse.php">
      <div class="row">
        <div class="col-md-4 pr-0">
          <div class="form-group">
            <label for="keyword" class="sr-only">Search keyword:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-transparent pr-0 text-muted">
                  <i class="fa fa-search"></i> <!-- This is the magnifying glass in the search bar.  -->
                </span>
              </div>
              <input name="keyword" type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything" value="<?php echo $keyword; ?>">
            </div>
          </div>
        </div>

        <!-- Categories Drop Menu Select: Start --> 
        <div class="col-md-3 pr-0">
          <div class="form-group">
            <label for="cat" class="sr-only">Search within:</label>
            <select name="cat" class="form-control" id="cat">
              <option selected value="all">All categories</option>
              <?php
              $sql = "SELECT * FROM categories WHERE main_cat=0";
              $rez = $conn->query($sql);
              while ($row = mysqli_fetch_assoc($rez)) {
                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <!-- Categories Drop Select: End --> 
        
        
        <!-- Sort by: Start --> 
        <div class="col-md-2 pr-0">
          <div class="form-inline">
            <label class="mx-2" for="order_by">Sort by:</label>
            <select name="order_by" class="form-control" id="order_by">
              <option value="pricelow" <?php if ($ordering == 'pricelow') {
                echo 'selected';
              } ?>>Price (low to high)</option>
              <option value="pricehigh" <?php if ($ordering == 'pricehigh') {
                echo 'selected';
              } ?>>Price (high to low)</option>
              <option value="date" <?php if ($ordering == 'date') {
                echo 'selected';
              } ?>>Soonest expiry</option>
            </select>
          </div>
        </div>
        <!-- Sort by: End --> 
        
        
        <!-- Include Expired Auctions in Search: Start --> 
        <div class="col-md-2 pr-0">
          <div class="form-inline">
            <label class="mx-2" for="order_by">Include expired</label>
            <input type="checkbox" class="form-control" name="expired" <?php if ($expired != '0') {
              echo 'checked';
            } ?>>
          </div>
        </div>
        <!-- Include Expired Auctions in Search: End --> 

        <!-- Search Button -->
        <div class="col-md-1 px-0">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
    </form>
  </div> 
</div>
<!-- end search specs bar -->


<div class="container-fluid mt-5">
<div style="max-width: 1200px; margin: 10px auto">

  <!-- If result set is empty, print an informative message. Otherwise display search result -->

  <ul class="list-group browse_display">

    <!-- Use a foreach loop to print a list item for each auction listing retrieved from the query -->

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

  <!-- Pagination for results listings -->
  <nav aria-label="Search results pages" class="mt-5">
    <ul class="pagination justify-content-center">

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
</div>



<?php include_once("footer.php") ?>