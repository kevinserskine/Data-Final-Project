<?php

//THis PHP file validates user information for login and inserts user information into database table for sign up

session_start();

$username = "";
$password = "";

$database = mysqli_connect('localhost', 'root', '', 'webfinal');



if (isset($_POST['login'])){

    $username = mysqli_real_escape_string($database, $_POST['username']);
    $password = mysqli_real_escape_string($database, $_POST['password']);

    $query = "SELECT * FROM users WHERE Username= '$username' AND Password= '$password'";
    $results = mysqli_query($database, $query);

    if (mysqli_num_rows($results) == 1 ){

        header( 'Location: SeatBooking.html' ) ;

    }else{
;
        header( 'Location: Fail.html' ) ;
    }
    
}

if (isset($_POST['signup'])) {

    $username = mysqli_real_escape_string($database, $_POST['username']);
    $password = mysqli_real_escape_string($database, $_POST['password']);

    $query = "INSERT INTO users (Username, Password) VALUES('$username', '$password')";

    mysqli_query($database, $query);

    if ($_POST['theatres'] !== 0){

        header( 'Location: SeatBooking.html' ) ;
    }

   

}

?>