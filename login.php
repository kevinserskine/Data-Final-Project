<?php

$error = "";


// If this is a post request, handle the login (this page posts to itself)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include and call function to connect to db
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    session_start();

    // Get post request variables
    $email = $_POST["email"];
    $password = $_POST["password"];
    $mode = $_POST["mode"];

    // Sanitize our inputs (nice try bobby tables)
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    // Make a sql query to see if the user exists (This is useful for both login and signup)
    $sql = "SELECT email FROM user WHERE email = '$email';";
    if (mysqli_query($conn, $sql)->num_rows > 0) {
        $userExists = true;
    } else {
        $userExists = false;
    }

    // Do things based on if user logging in or signing up
    if ($mode == "login") {
        if ($userExists) {
            // Todo hash password
            $sql = $sql = "SELECT email ,user_id FROM user WHERE email = '$email' AND password = '$password';";
            $result = mysqli_query($conn, $sql);
            // If nothing was returned, wrong password
            if ($result->num_rows == 0) {
                $error = "Wrong password!";
            } else {
                // Set the session info and redirect the user
                while ($row = $result->fetch_assoc()) {
                    $_SESSION['sessionID'] = $row['user_id'];
                    // Redirect to index
                    header("Location:index.php");

                }
            }
        } else {
            $error = "User not found! Make sure you've entered the correct email address or sign up for a new account";
        }

    } else if ($mode == "register") {
        $error = "I haven't wrote that code yet sorry";
        // todo remember to hash the password (if login inexplicably doesn't work check consistent hashing)
    }
}


?>


<html lang="en">

<head>

    <link rel="stylesheet" href="styles/login.css">
    <title>Placeholder change later</title>

</head>

<body>

<button class="tablink" onclick="openPage('Login', this, 'white')" id="defaultOpen">Login</button>
<button class="tablink" onclick="openPage('SignUp', this, 'white')">Sign Up</button>


<div id="Login" class="tabcontent">
    <h3 class="head">Login to THE BOOKSHELF</h3>
    <form method="POST">

        <label class="label">Username
            <input type="text" class="textbox" name="username" required>
        </label>

        <label class="label">Password
            <input type="password" class="textbox" name="password" required>
        </label>

        <button type="submit" id="logbtn" name="login">Login</button>

    </form>
</div>

<div id="SignUp" class="tabcontent">
    <h3 class="head">Sign Up to THE BOOKSHELF</h3>
    <form method="POST">

        <label class="label">Username
            <input type="text" class="textbox" name="username" required>
        </label>

        <label class="label">Password
            <input type="password" class="textbox" name="password" required>
        </label>

        <button type="submit" id="signbtn" name="signup">Sign Up</button>

    </form>


</div>


</body>

<script>

    function openPage(pageName, elmnt, color) {
        var i, tabcontent, tablinks;

        tabcontent = document.getElementsByClassName("tabcontent");

        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }

        document.getElementById(pageName).style.display = "block";

        elmnt.style.backgroundColor = color;

    }

    document.getElementById("defaultOpen").click();

</script>

</html>
