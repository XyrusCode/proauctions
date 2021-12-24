<?php include_once("header.php"); ?>
<?php require("utilities.php"); ?>

<?php

//get all info from the auctions table
$popular_auctions=grab_auctions('popular');
$recommended_auctions=grab_auctions('recommended');
?>



<!----------------------------------------------------------- PARENT CONTAINER ----------------------------------------------------------->
<div class="container-fluid">

  <div style="margin: 10px auto">
  <h2 class="my-3" style="text-align:center; padding:10px; border:5px solid gray">Home Page</h2>

  <!---------------------------------------------------------------- LEFT-CONTAINER: CURRENT MOST POPULAR AUCTIONS START -------------------------------------------------------------->
  <?php if ($has_session){?>
  <div class="row">
  <div class="col-md-6">
      <h2 class="my-3" style="text-align:center">Most Popular Auctions</h2>
      <ul class="list-group browse_display">
        <?php
        if (count($popular_auctions) > 0) {
          foreach ($popular_auctions as $auction) {
            print_listing_li($auction['auction_id'], $auction['auction_name'], $auction['image'], $auction['details'], $auction['price'], $auction['num_bids'], $auction['end_date_time']);
          }
        } else {
          echo 'We do not have any auctions at the moment. Please come back later!';
        }
        ?>
      </ul>
  </div>

  <!---------------------------------------------------------------- RIGHT-CONTAINER: RECOMMENDED FOR YOU START -------------------------------------------------------------->
  
  
  <div class="col-md-6">
    <h2 class="my-3" style="text-align:center">Recommended For You</h2>
    <ul class="list-group browse_display">
      <?php
      if (count($recommended_auctions) > 0) {
        foreach ($recommended_auctions as $auction) {
          print_listing_li($auction['auction_id'], $auction['auction_name'], $auction['image'], $auction['details'], $auction['price'], $auction['num_bids'], $auction['end_date_time']);
        }
      } else {
        echo 'We do not have any recommended auctions at the moment. Please come back later!';
      }
      ?>
    </ul>
  </div>
   <?php } else { ?>

    <div class="row">
  <div class="col-md-12">
      <h2 class="my-3" style="text-align:center">Most Popular Auctions</h2>
      <h5 class="my-3" style="text-align:center">(Please log in to see your personal recommendations)</h5>
      <ul class="list-group browse_display">
        <?php
        if (count($popular_auctions) > 0) {
          foreach ($popular_auctions as $auction) {
            print_listing_li($auction['auction_id'], $auction['auction_name'], $auction['image'], $auction['details'], $auction['price'], $auction['num_bids'], $auction['end_date_time']);
          }
        } else {
          echo 'We do not have any popular auctions at the moment. Please come back later!';
        }
        ?>
      </ul>
  </div>
    <?php } ?>

<!---------------------------------------------------------------- RECOMMENDED FOR YOU END -------------------------------------------------------------->

</div>
</div>
</div>

<?php include_once("footer.php"); ?>