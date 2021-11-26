<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    session_start();
    include 'components/isAuthed.php';
    $userID = $_SESSION['sessionID'];
    foreach ($_SESSION['cart']['buy'] as $key => $value){
        $result=mysqli_query($conn, "SELECT book_id FROM book where ISBN13=$key");
        $row=mysqli_fetch_assoc($result);
        $book_id=$row['book_id'];
        $price=$value['price'];
        $result=mysqli_query($conn, "INSERT INTO user_order (order_date, user_id, dest_address_id) VALUES (NOW(), 1, 1)");
        echo print_r($result);
        $order_id=mysqli_insert_id($conn);
        $result=mysqli_query($conn, "INSERT INTO order_list (order_id, book_id, price) VALUES ($order_id, $book_id, $price)");
    }
    unset($_SESSION['cart']['buy']);
    foreach ($_SESSION['cart']['borrow'] as $key => $value){
        $result=mysqli_query($conn, "SELECT book_id FROM book where ISBN13=$key");
        $row=mysqli_fetch_assoc($result);
        $book_id=$row['book_id'];
        mysqli_query($conn, "INSERT INTO user_order (order_date, user_id, dest_address_id) VALUES (NOW(), 1, 1)");
        $order_id=mysqli_insert_id($conn);
        mysqli_query($conn, "INSERT INTO order_list (order_id, book_id, price) VALUES ($order_id, $book_id, 0.0)");
    }
    unset($_SESSION['cart']['borrow']);
    unset($_SESSION['totalCost']);
?>
