<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (!isset($_SESSION)){
        session_start();
    }

    $ISBN = $_POST['ISBN'];
    $mode = $_POST['mode'];
    if($mode=='buy'){
        //If buying set the corresponding ['buy']'s price, title, and quantity
        $query = "SELECT price, title FROM BOOK where ISBN13=$ISBN";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $_SESSION['cart']['buy'][$ISBN]['price']=$row['price'];
        $_SESSION['cart']['buy'][$ISBN]['title']=$row['title'];
        if(!isset($_SESSION['cart']['buy'][$ISBN]['quantity'])){
            $_SESSION['cart']['buy'][$ISBN]['quantity']=1;
        } else {
            $_SESSION['cart']['buy'][$ISBN]['quantity']+=1;
        }
        if(!isset($_SESSION['totalCost'])){
            $_SESSION['totalCost']=$row['price'];
        } else {
            $_SESSION['totalCost']+=$row['price'];
        }
        header('Content-Type: application/json');
        echo json_encode(array('title' => $row['title'], 'quantity' => $_SESSION['cart']['buy'][$ISBN]['quantity'],'price'=>$row['price'],'totalCost' => $_SESSION['totalCost']));
    } elseif ($mode=='borrow'){
        //If borrwing set the corresponding ['borrow']'s price, title, and quantity
        $query = "SELECT title FROM BOOK where ISBN13=$ISBN";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $_SESSION['cart']['borrow'][$ISBN]['title']=$row['title'];
        if(!isset($_SESSION['cart']['borrow'][$ISBN]['quantity'])){
            $_SESSION['cart']['borrow'][$ISBN]['quantity']=1;
        } else {
            $_SESSION['cart']['borrow'][$ISBN]['quantity']+=1;
        }
        header('Content-Type: application/json');
        echo json_encode(array('title' => $row['title'], 'quantity' => $_SESSION['cart']['borrow'][$ISBN]['quantity']));
    }
?>
