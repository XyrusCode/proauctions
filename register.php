<?php include_once("header.php") ?>

<div class="container">
<h2 class="my-3" style="text-align:center; padding:10px; border:5px solid gray">Register</h2></br>

  <!-- Create auction form -->
  <form id="register_form" method="POST" action="process_registration.php">
  <div class="results"></div>

    <!-- SELECT ACCOUNT TYPE - BUYER/SELLER -->
    <div class="form-group row">
      <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
      <div class="col-sm-10">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
          <label class="form-check-label" for="accountBuyer">Buyer</label>
        </div>

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
          <label class="form-check-label" for="accountSeller">Seller</label>
        </div>
        <small class="form-text-inline text-muted"><span class="text-danger"></span></small>
      </div>
    </div>
    <!-- USERNAME -->
    <div class="form-group row">
      <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>
    <!-- NAME - FIRST AND LAST INLINE -->
    <div class="form-group row">
      <label for="first_name" class="col-sm-2 col-form-label text-right">Your name</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="first_name" name="firstname" placeholder="First name">
        <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="last_name" name="lastname" placeholder="Last name">
        <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>
    <!-- EMAIL -->
    <div class="form-group row">
      <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="email" name="email_address" placeholder="Email">
        <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>
   
    <div class="form-group row seller_fields">
            <label for="address_1" class="col-sm-2 col-form-label text-right">Address 1: </label>
            <div class="col-sm-10">
              <input name="address_1" class="form-control" id="address_1">
              <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>

          <div class="form-group row seller_fields">
            <label for="address_2" class="col-sm-2 col-form-label text-right">Address 2: </label>
            <div class="col-sm-10">
              <input name="address_2" class="form-control" id="address_2">
              <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>

          <div class="form-group row seller_fields">
            <label for="city" class="col-sm-2 col-form-label text-right">City: </label>
            <div class="col-sm-10">
              <input name="city" class="form-control" id="city">
              <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>
    
    <!-- COUNTRY -->
    <div class="form-group row">
            <label for="countries" class="col-sm-2 col-form-label text-right">Country</label>
            <div class="col-sm-10">
              <select name="country_id" class="form-control" id="countries" required="">
              <option disabled selected value> -- select an option -- </option>
                <?php
                $sql = "SELECT * FROM countries";
                $rez = $conn->query($sql);
                while ($row = mysqli_fetch_assoc($rez)) {
                  echo '<option value="' . $row['country_id'] . '"';
                    if ($row['country_id']=='GB'){echo' selected';}
                 echo '>' . $row['country_name'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>

          
          <div class="form-group row seller_fields">
            <label for="post_code" class="col-sm-2 col-form-label text-right">Post code: </label>
            <div class="col-sm-10">
              <input name="post_code" class="form-control" id="post_code">
              <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="phone_number" class="col-sm-2 col-form-label text-right">Mobile number: </label>
            <div class="col-sm-10">
              <input name="phone_number" class="form-control" id="phone_number">
              <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>


    <!-- PASSWORD -->
    <div class="form-group row">
      <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control pass" id="password" name="password" placeholder="Password">
        <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>
    <!-- REPEAT PASSWORD -->
    <div class="form-group row">
      <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="passwordConfirmation" name="password2" placeholder="Repeat password">
        <small class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>
    <!-- REGISTER BUTTON -->
    <div class="form-group row">
      <button type="submit" class="btn btn-primary form-control">Register</button>
    </div>
  </form>
  <!-- LINK TO LOG IN IF ALREADY REGISTERED-->
  <div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a></div>

</div>

<?php
include_once("footer.php") ?>