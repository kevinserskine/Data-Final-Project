<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    session_start();

    $userID=$_SESSION['sessionID'];
    $addressID=(int)$_POST['id'];

    mysqli_begin_transaction($conn);
    mysqli_query($conn, "DELETE FROM user_address WHERE address_id=$addressID");
    mysqli_query($conn, "DELETE FROM address WHERE address_id=$addressID");
    mysqli_commit($conn);
?>
