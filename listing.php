<?php include_once("header.php") ?>
<?php require("utilities.php") ?>

<?php
if (isset($_POST['action']) && $_POST['action']=='save'){
$auction_name = $conn->real_escape_string(trim($_POST['auction_name']));
$description = $conn->real_escape_string(trim($_POST['details']));
$auction_id = $conn->real_escape_string(trim($_POST['auction_id']));
$conn->query("UPDATE auctions SET auction_name = '$auction_name', details = '$description' WHERE auction_id = $auction_id");
header('Location: listing.php?item_id='.$_GET['item_id']);
exit();
}



// BIDS HISTORY DISPLAY FUNC STARTS--------------------------------------------------------------------------------
//Get info about an auction from database to display on page (Setting variables)
$item_id = $_GET['item_id']; // Get info from the URL:
$query = $conn->query("SELECT * FROM auctions WHERE auction_id='" . $item_id . "' LIMIT 1");
$auction = mysqli_fetch_assoc($query);
$title = $auction['auction_name'];
$description = $auction['details'];
$now=time();
$end_time=$auction['end_date_time'];
$time_to_end = date_diff(new DateTime(), new DateTime(date("Y-m-dTH:i:s",$end_time)));
$time_remaining=display_time_remaining($time_to_end);

// Get all bids about the auction
$query = $conn->query("SELECT * FROM bids WHERE auction_id='" . $item_id . "' ORDER BY bid_amount DESC");

if ($query->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($query)) {
    $bids[] = $row;
  }
  $current_price = $bids[0]['bid_amount'];
  $num_bids = count($bids);
} else {
  $bids = array();
  $current_price = $auction['start_price'];
  $num_bids = 0;
}
//bids[0] is the largest bid(the last bid)

// BIDS HISTORY DISPLAY FUNC ENDS--------------------------------------------------------------------------------



  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }

// IMAGE DISPLAY FOR EACH AUCTION --------------------------------------------------------------------------------

$item_id = $_GET['item_id'];
$query = $conn->query("SELECT * FROM images WHERE auction_id='" . $item_id . "' LIMIT 1");
if ($query->num_rows > 0) {
  $image = mysqli_fetch_assoc($query);
  $img = 'images/auctions/' . $image['title'];
} else {
  $img = 'images/default.jpg';
}


// TODO: Note: Auctions that have ended may pull a different set of data,
//       like whether the auction ended in a sale or was cancelled due
//       to lack of high-enough bids. Or maybe not.


if (check_editor()) { //defined in utilities
echo '<form method="post">';
}
?>

<!--  CONTAINER----------------------------------------------------------------------------------------------- -->
<div class="parent-container d-flex" style="max-width: 1100px; margin: 10px auto">

  <!-- LEFT-->
  <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
      <!-- Left col -->
      <h2 class="my-3 text-center " type="text" style="font-weight: bold">
        <?php
        if (check_editor()) { //defined in utilities
          echo '<input type="text" name="auction_name" value="' . $title . '">';
        } else echo ($title); ?>
      </h2>
      <div class="border border-dark">
      <a data-toggle="modal" data-target="#listing_image" target="blank" href="<?php echo $img; ?>"><img src="<?php echo $img; ?>" style="max-width:100%; margin:auto"/></a>
     
<div id="listing_image" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width:80%!important">
    <div class="modal-content">
        <div class="modal-body">
        <img src="<?php echo $img; ?>" style="width:100%"/>
        </div>
    </div>
  </div>
</div>
    
    
    
    </div>
    </div>
        </div>
  </div>
 

    <!-- RIGHT -->   
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8" style="padding-top:270px;margin:auto; text-align: center">
    <!-- Edit Listing if Seller and Created Auction -->
<div style="position:absolute;right:20px;top:70px">
    <?php if (check_editor()) { ?>
<input type="submit" class="btn btn-secondary btn-sm logg" value="Update Listing"/>
<?php } else { ?>
  <a class="nav-link btn btn-secondary btn-sm logg" href="?item_id=<?php echo $_GET['item_id']; ?>&action=edit">Edit Listing</a>
<?php }?> 
</div>


    <!-- WATCHLIST Buttons: Starts -->
      <?php
      $has_session = true;
      $userid=$_SESSION['userid'];
      $sql = "SELECT * FROM user_watches WHERE userid = $userid AND auction_id = $auction_id";
      $query = mysqli_query($conn, $sql); 
      $num_rows = mysqli_num_rows($query);

        if ($num_rows == 1){
        $watching = true;
        } else {
        $watching = false;
        }
        echo ($query) ;

      /* uses Javascript but could have used PHP */
      if ($now < $end_time && $has_session) {
      ?>
        <div id="watch_nowatch" <?php if (!$has_session || $watching) echo ('style="display: none"'); ?>>
          <button type="button" class="btn btn-outline-secondary btn-sm"onclick="add_To_Watchlist()"><i class="fa fa-bookmark-o"></i> Add to watchlist</button>
        </div>
        <div id="watch_watching" <?php if (!$has_session || !$watching) echo ('style="display: none"'); ?>>
          <button type="button" class="btn btn-success btn-sm" disabled><i class="fa fa-bookmark-o"></i> Watching</button>
          <button type="button" class="btn btn-danger btn-sm" onclick="remove_From_Watchlist()">Remove watch</button>
        </div>
      <?php } /* Print nothing otherwise */ ?>
      <!-- WATCHLIST Buttons: Ends -->


      <!-- Display Auction Timing Info: Starts -->

        <?php 
        if (time() > $auction['end_date_time']) { 
          ?>
             <p style="font-weight: bold;padding-top:15px;margin-top:-70px">
          <span style="font-size: 130%;">This auction ended: </span>
          <br/><br/>Date: <?php  echo (date('j M, Y',$end_time))?>
          <br/>Time: <?php  echo  date('H:i A',$end_time); ?>
          </p>
          <!-- TODO: Print the result of the auction here? -->
        <?php } else { ?>
          <p style="font-weight: bold;padding-top:15px;">
          Auction ends: <?php echo (date('j M, Y - H:i',$end_time)); ?> <?php echo '</br></br>';?>
          Time remaining: <?php echo $time_remaining;
          ?>
      </p></br>
      <p class="lead" style="font-weight: bold;">Current bid: £<span class="current_bid1"><?php echo (number_format($current_price, 2)) ?></span></p>
      <!-- Display Auction Timing Info: Ends -->
      <?php 
        } ?>

      <!-- BIDDING FORM -->
      <?php //don't show the bidding form unless the user is logged in
          if ($has_session &&  (time() < $auction['end_date_time'])) { ?>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">£</span>
            </div>
            <input name="bid_amount" type="number" class="form-control" id="bid" step=".01">
            <input name="auction_id" type="hidden" class="form-control" value="<?php echo $item_id; ?>">
          </div>
          <button class="btn btn-primary form-control place_the_bid">Place bid</button>
        <div class="bid_result"></div>
    <?php 
        } ?>
    </div>
  </div>
</div>
</div>

<div class="container-fluid" style="max-width: 1100px; margin: 10px auto">
<div class="row">

      <?php
        if (check_editor()) { //defined in utilities
          echo '<textarea name="details" style="width:100%" rows="10">' . $description . '</textarea>';
        } else
          echo ($description);

        ?>

</div>
  </div>



<!-- CONTAINER ENDS --------------------------------------------------------------------------------------->

<?php 
if (check_editor()) { //defined in utilities
  echo '<input type="hidden" name="action" value="save">
  </form>';
  }
?>

<!-- Bids History Table: Starts -->
<?php if (!empty($bids)) { ?>
  <div class="container mt-5 mb-5">
    <p style="text-align:center; font-weight:bold">Bidding History</p>
    <table class="bid_table table" border=1>
      <tr class="bid_history_header">
        <td>Username</td>
        <td>Timestamp</td>
        <td>Bid</td>
      </tr>

      <?php
      foreach ($bids as $bid) {
        $user = mysqli_fetch_assoc($conn->query("SELECT * FROM users WHERE userid=" . $bid['userid'] . ""));
        echo '<tr><td>' . $user['username'] . '</td><td>' . date("d-m-Y h:i:s", $bid['bid_timestamp']) . '</td><td>£' . $bid['bid_amount'] . '</td></tr>';
      }
      ?>
    </table>
  </div>
<?php }  ?>
<!-- Bids History Table: Ends -->




<!-- JavaScript functions: ADD-TO-WATCHLIST and REMOVE-FROM-WATCHLIST: Start------------------------------------------->
<script>
  function addToWatchlist(button) {
  function add_To_Watchlist(button) {
    console.log("These print statements are helpful for debugging btw");

    // This performs an asynchronous call to a PHP function using POST method.
    // Sends item ID as an argument to that function.
    $.ajax({
      type: "POST",
      url: 'add_to_watchlist.php', 
      data: {
        functionname: 'add_to_watchlist',
        arguments: [<?php echo ($auction_id); ?>]
      },

      success: function(obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();

        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        } else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

      error: function(obj, textstatus) {
        console.log("Error");
      }
    }); // End of AJAX call

  } // ADD-TO-WATCHLIST function: Ends --------------------------------------------------


  function remove_From_Watchlist(button) {
    // This performs an asynchronous call to a PHP function using POST method.
    // Sends item ID as an argument to that function.
    $.ajax('watchlist_funcs.php', {
      type: "POST",
      data: {
        functionname: 'remove_from_watchlist',
        arguments: [<?php echo ($auction_id); ?>]
      },

      success: function(obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();

        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        } else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

      error: function(obj, textstatus) {
        console.log("Error");
      }
    }); // End of AJAX call
  }
</script>

<?php include_once("footer.php") ?>