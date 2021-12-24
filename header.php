<?php
include 'db.php';
session_start();
$pages = array('index', 'about', 'profile', 'browse', 'mybids', 'watchlist', 'recommendations', 'mylistings', 'create_auction', 'contact', 'listing', 'register');
foreach ($pages as $page) {
  $active[$page] = '';
  if (strpos($_SERVER['REQUEST_URI'], $page . '.php')) {
    $active[$page] = 'active_link';
    $title = ucwords(str_replace('_', ' ', $page));
  }
}

?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="css/custom.css">
  <title><?php echo $title; ?> - ProAuctions</title>

</head>


<body class="d-flex flex-column min-vh-100">

  <!-- Navbars -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
    <a class="navbar-brand" href="index.php">
      <img src="images/logo.png" alt="logo">
    </a>
    </div>

    <ul class="navbar-nav ml-auto top_bar">

      <?php
      // Displays logged in links 
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { ?>

        <!-- Access depending on Seller/Buyer -->
        <?php
        if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
          echo ('
	<li class="nav-item mx-1">
      <a class="nav-link ' . $active['mybids'] . '" href="mybids.php">My Bids</a>
    </li>
  <li class="nav-item mx-1">
      <a class="nav-link ' . $active['watchlist'] . '" href="watchlist.php">Watchlist</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link ' . $active['recommendations'] . '" href="recommendations.php">Recommendations</a>
    </li>');
        }
        if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
          echo ('
          <li class="nav-item mx-1">
      <a class="nav-link ' . $active['mybids'] . '" href="mybids.php">My Bids</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link ' . $active['mylistings'] . '" href="mylistings.php">My Listings</a>
    </li>
  <li class="nav-item mx-1">
      <a class="nav-link ' . $active['watchlist'] . '" href="watchlist.php">Watchlist</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link ' . $active['create_auction'] . '" href="create_auction.php">+ Create auction</a>
    </li>');
        }
        ?>
        <li class="nav-item"><a class="nav-link btn btn-primary prof '.$active['profile'].'" href="profile.php">Profile</a></li>
        <li class="nav-item"><a class="nav-link btn btn-primary logg" href="logout.php">Logout</a></li>

      <?php } else { //Displays logged out links  
      ?>
        <li class="nav-item "><button type="button" class="nav-link btn btn-primary logg" data-toggle="modal" data-target="#loginModal">Login</button></li>
      <?php } ?>
    </ul>

  </nav>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark themenu">
    <ul class="navbar-nav align-middle">

    <li class="nav-item mx-1">
        <a class="nav-link <?php echo $active['index']; ?>" href="index.php">Home</a>
      </li>

    <li class="nav-item mx-1">
        <a class="nav-link <?php echo $active['browse']; ?>" href="browse.php">Browse</a>
      </li>

      <li class="nav-item mx-1">
        <a class="nav-link <?php echo $active['about']; ?>" href="about.php">About us</a>
      </li>
    
      <li class="nav-item mx-1">
        <a class="nav-link <?php echo $active['contact']; ?>" href="contact.php">Contact us</a>
      </li>
    </ul>
  </nav>

  <!-- LOGIN MODAL -->
  <div class="modal fade" id="loginModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Login</h4>

         
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form id="loginform" method="POST" action="login_result.php">
            <div class="results"></div>
            <div class="form-group">
              <label for="username">Username</label>
              <input name="username" type="text" class="form-control" id="username" placeholder="Username">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input name="password" type="password" class="form-control" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary form-control">Sign in</button>
          </form>
          <div class="text-center">or <a href="register.php">create an account</a></div>
        </div>
      </div>
    </div>
  </div> <!-- END LOGIN MODAL -->