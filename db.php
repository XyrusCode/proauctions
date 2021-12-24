 <?php
    $servername = "localhost";
    $username = "xyruscod_padmin";
    $password = "";
    $dbname = "xyruscod_pa";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>