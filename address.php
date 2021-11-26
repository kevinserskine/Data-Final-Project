<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $addressID=(int)$_POST['id'];

    //Deletes corresponding address and address link from db
    mysqli_begin_transaction($conn);
    mysqli_query($conn, "DELETE FROM user_address WHERE address_id=$addressID");
    mysqli_query($conn, "DELETE FROM address WHERE address_id=$addressID");
    mysqli_commit($conn);
?>
