<?php include_once("header.php"); ?>
<?php require("utilities.php"); ?>

    <!-- Need to add image on top -->
    <?php
    $sql="SELECT * FROM users WHERE userid = '" . $_SESSION['userid'] . "'";
    $rez = $conn->query($sql);
    $users = mysqli_fetch_assoc($rez);
    // print_r($row);echo'<Br>';
    $username=$users['username'];
    $fname=$users['firstname'];
    $lname=$users['lastname'];
    $address_1=$users['address_1'];
    $address_2=$users['address_2'];
    $city=$users['city'];
    $country=$users['country_id'];
    $post_code=$users['post_code'];
    $phone_number=$users['phone_number'];
    $email=$users['email_address'];
    $password=$users['password'];
    ?>


    <div class="container">
        <div style="max-width: 800px; margin: 10px auto">
        <h2 class="my-3" style="text-align:center; padding:10px; border:5px solid gray">My Profile</h2></br>
            <div class="card">
            <div class="card-body">

          <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label text-right">Username: </label>
            <div class="col-sm-10">
              <div name="user_name" class="form-control" id="user_name">
        
              <?php /*if (check_profile()){ //defined in utilities
                echo '<input type="text" name="" value="'.$username.'">';
              } else*/ echo ($username); 
              ?>
              
            </div>
            </div>
          </div>


          <div class="form-group row">
            <label for="fname" class="col-sm-2 col-form-label text-right">First name: </label>
            <div class="col-sm-10">
              <div name="fname" class="form-control" id="fname"><?php echo ($fname);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="lname" class="col-sm-2 col-form-label text-right">Last name: </label>
            <div class="col-sm-10">
              <div name="lname" class="form-control" id="lname"><?php echo ($lname);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="address_1" class="col-sm-2 col-form-label text-right">Address 1: </label>
            <div class="col-sm-10">
              <div name="address_1" class="form-control" id="address_1"><?php echo ($address_1);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="address_2" class="col-sm-2 col-form-label text-right">Address 2: </label>
            <div class="col-sm-10">
              <div name="address_2" class="form-control" id="address_2"><?php echo ($address_2);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="city" class="col-sm-2 col-form-label text-right">City: </label>
            <div class="col-sm-10">
              <div name="city" class="form-control" id="city"><?php echo ($city);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="country" class="col-sm-2 col-form-label text-right">Country: </label>
            <div class="col-sm-10">
              <div name="country" class="form-control" id="country"><?php echo ($country);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="post_code" class="col-sm-2 col-form-label text-right">Post code: </label>
            <div class="col-sm-10">
              <div name="post_code" class="form-control" id="post_code"><?php echo ($post_code);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="phone_number" class="col-sm-2 col-form-label text-right">Mobile number: </label>
            <div class="col-sm-10">
              <div name="phone_number" class="form-control" id="phone_number"><?php echo ($phone_number);?></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label text-right">Email: </label>
            <div class="col-sm-10">
              <div name="email" class="form-control" id="email"><?php echo ($email);?></div>
            </div>
          </div>

<?php /*echo ($password);*/?>
    
</div>
    </div> 
    </div> 
    </div> 
    </div> 
</div>

<!-- <?php /*if (check_profile()){ ?>
  <div style="position:fixed;bottom:50%;right:0;color:white">
    <a class="nav-link btn btn-primary logg" href="prof_update.php">Update Profile</a>
  </div> -->
<!-- <?php } else {?> -->
  <!-- <div style="position:fixed;bottom:50%;right:0;color:white"> -->
    <!-- <a class="nav-link btn btn-primary logg" href="?user_id=<?php /*echo $_GET['user_id'];*/ ?>&action=edit">Edit Profile</a> -->
    
<!-- <?php /* } */?>  -->



<?php include_once ("footer.php") ?>

