<?php include_once("header.php") ?>
<?php require("utilities.php") ?>

<div class="container">
  <div class="text-center mt-5">
    <img src="images/success.jpg" alt="success"/>
  <div class="mt-5">Your auction has been added. Please click <a href="listing.php?item_id=<?php echo $_GET['itemid']; ?>"> here </a>.</div>
</div>
</div>


<?php include_once("footer.php") ?>