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

    // Sanitize our inputs and hash the password (nice try bobby tables)
    $email = $conn->real_escape_string($email);
    $password = password_hash($conn->real_escape_string($password), PASSWORD_DEFAULT);

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
        if ($userExists) {
            $error = "User with that email already exists!";
        } else {
            // Insert the new user info into the database
            $sql = "INSERT INTO user (email, password) VALUES ('$email','$password');";
            mysqli_query($conn, $sql);

            // Log the user in as the newly created user and redirect to index
            $_SESSION['sessionID'] = $conn->insert_id;
            header("Location:index.php");
        }
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
    <p class="error"><?php echo $error; ?></p>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <label class="label">Email
            <input type="email" class="textbox" name="email" required>
        </label>

        <label class="label">Password
            <input type="password" class="textbox" name="password" required>
        </label>

        <!--suppress HtmlFormInputWithoutLabel -->
        <input type="text" value="login" id="mode" name="mode" hidden>
        <button type="submit" id="loginButton" name="login">Login</button>

    </form>
</div>

<div id="SignUp" class="tabcontent">
    <h3 class="head">Sign Up to THE BOOKSHELF</h3>
    <form method="POST">

        <label class="label">Email
            <input type="email" class="textbox" name="email" required>
        </label>

        <label class="label">Password
            <input type="password" class="textbox" name="password" required>
        </label>

        <button type="submit" id="signbtn" name="signup">Sign Up</button>

    </form>


</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>


</body>

<script>

    function openPage(pageName, element, color) {
        let i, tabContent, tabLinks;

        // Isn't jquery fun?
        $(".tabcontent").hide();
        $(".tablink").css("background-color", "");

        $("#" + pageName).show();

        element.style.backgroundColor = color;

    }

    document.getElementById("defaultOpen").click();

</script>

</html>
