<?php
include_once("header.php");
/* (Uncomment this block to redirect people without selling privileges away from this page)
  // If user is not logged in or not a seller, they should not be able to
  // use this page.
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }
*/
?>

<div class="container">

  <!-- Create auction form -->
  <div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3" style="text-align:center; padding:10px; border:5px solid gray">Create Auction</h2></br>
    <div class="card">
      <div class="card-body">
        <!-- Note: This form does not do any dynamic / client-side / 
      JavaScript-based validation of data. It only performs checking after 
      the form has been submitted, and only allows users to try once. You 
      can make this fancier using JavaScript to alert users of invalid data
      before they try to send it, but that kind of functionality should be
      extremely low-priority / only done after all database functions are
      complete. -->

      
<!-- FORM CREATE AUCTION -->
      <form id="create_auction" method="post" action="/ajax/create_auction_result.php" enctype="multipart/form-data">
          <!-- Auction Name -->
          <div class="form-group row">
            <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of Auction</label>
            <div class="col-sm-10">
              <input name="auction_name" type="text" class="form-control" id="auctionTitle" placeholder="e.g. Black Nike T-shirt" required="">
              <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>

          <!-- Details -->
          <div class="form-group row">
            <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
            <div class="col-sm-10">
              <textarea name="details" class="form-control" id="auctionDetails" rows="8" maxlength="1000" placeholder="Provide bidders with more information about  item."></textarea>
            </div>
          </div>

          <!-- Category Selection -->
          <div class="form-group row">
            <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
            <div class="col-sm-10">
              <select name="category_id" class="form-control" id="auctionCategory" required="">
              <option disabled selected value> -- select an option -- </option>
                <?php
                $sql = "SELECT * FROM categories WHERE main_cat=0";
                $rez = $conn->query($sql);
                while ($row = mysqli_fetch_assoc($rez)) {
                  echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                }
                ?>
              </select>
              <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
            </div>
          </div>

          <!-- Quantity Item -->
          <div class="form-group row">
            <label for="quantity" class="col-sm-2 col-form-label text-right">Quantity</label>
            <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-prepend">
                </div>
              <input name="quantity" type="number" class="form-control" id="auctionQuantity" value="1">
              </div>
          </div>
          </div>

          <!-- UPLOAD IMAGE STARTS-->
          <div class="form-group row">
            <label for="formFile" class="col-sm-2 col-form-label text-right">Upload Image</label>
            <div class="col-sm-10">
              <input name="imgupload" class="form-control" type="file" id="formFile" onchange="preview()">
              <button class="btn btn-primary mt-3 clear_me">Clear Image</button>

              <img id="frame" src="" class="img-fluid" />
            </div>
          </div>
          <!-- UPLOAD IMAGE ENDS -->

          <!-- Starting Price Starts-->
          <div class="form-group row">
            <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
            <div class="col-sm-10">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
                </div>
                <input name="start_price" type="number" class="form-control" id="auctionStartPrice" required="">
              </div>
              <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>
          <!-- Starting Price Ends -->

          <!-- Reserve Price Price Starts -->
          <div class="form-group row">
            <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
            <div class="col-sm-10">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
                </div>
                <input name="reserve_price" type="number" class="form-control" id="auctionReservePrice">
              </div>
              <!-- Auctions that end below this price will not go through. This value is not displayed in the auction listing. -->
            </div>
          </div>
          <!-- Reserve Price Ends-->

          <!-- DATE/TIME BOX STARTS-->
          <div class="form-group row">
            <label for="auctionStartDate" class="col-sm-2 col-form-label text-right">Auction duration</label>
            <div class="col-sm-10">

              <input name="auction_duration" type="text" name="auction_duration" class="form-control" style="width: 100%" />
              <small id="startDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>
          <!-- DATE/TIME BOX ENDS-->

          <button type="submit" class="btn btn-primary form-control">Create Auction</button>
          <div class="results"></div>
        </form>
      </div>
    </div>
  </div>

</div>


<?php include_once("footer.php") ?>