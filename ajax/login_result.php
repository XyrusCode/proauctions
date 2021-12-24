<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

include '../db.php';
session_start();

//trim password from user

$email = trim($_REQUEST['username']);
$password=trim($_REQUEST['password']);

//check database for a match

$sql="SELECT * FROM users WHERE username='".trim($_REQUEST['username'])."'" ;
$rez=$conn->query($sql);
$row = mysqli_fetch_assoc($rez);
// if email not in database, give an error
if($row){
    $pass=$row['password'];
    // if pass not at email, give an error
    if(!password_verify($password,$pass)){
        echo '<i style="color:red;font-size:20px;font-family:calibri;">Username or Password wrong</i>';
        }
        //else log in
        else {
        $rez=$conn->query($sql);
        while($row = mysqli_fetch_assoc($rez)) {
        $account_type='buyer';
        if ($row['is_seller']=='1') {$account_type='seller';}
        
        $_SESSION['userid']=$row['userid'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['account_type'] = $account_type;
        $_SESSION['logged_in']= true; 
        }	
        echo 'ok';	
        }
    } 
else{
    echo '<i style="color:red;font-size:20px;font-family:calibri;">Username or Password wrong</i>';
};