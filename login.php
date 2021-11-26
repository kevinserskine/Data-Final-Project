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
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $mode = $_POST["mode"];

    // Sanitize our inputs and hash the password (nice try bobby tables)
    $email = $conn->real_escape_string($email);
    $passwordHash = password_hash($conn->real_escape_string($password), PASSWORD_DEFAULT);

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
            $sql = "SELECT password, email ,user_id FROM user WHERE email = '$email';";
            $result = mysqli_query($conn, $sql);
            while ($row = $result->fetch_assoc()) {
                // Verify the password against the hash
                if (password_verify($password, $row['password'])) {
                    // Set the session info and redirect the user
                    $_SESSION['sessionID'] = $row['user_id'];
                    // Redirect to index
                    header("Location:index.php");
                } else {
                    $error = "Wrong password!";
                }
            }
        } else {
            $error = "User not found! Make sure you've entered the correct email address or sign up for a new account";
        }

    } else if ($mode == "register") {
        if ($userExists) {
            $error = "User with that email already exists!";
        } else {
            // Jank fix todo make auto increment
            $query = "SELECT MAX(user_id)+1 FROM user";
            $result = mysqli_query($conn, $query);
            $userID = (int)mysqli_fetch_array($result)[0];
            // Insert the new user info into the database
            $sql = "INSERT INTO user VALUES ('$userID','$passwordHash','$name','$email');";
            mysqli_query($conn, $sql);

            // Log the user in as the newly created user and redirect to index
            $_SESSION['sessionID'] = $conn->insert_id;
            //header("Location:index.php");
        }
    }
}


?>


<html lang="en">

<head>

    <link rel="stylesheet" href="styles/login.css">
    <title>Placeholder change later</title>

    <script type="text/javascript" src="scripts/loginTabs.js" async></script>

</head>

<body>
<div id="loginBox">
    <div id="splitHeader">
        <button type="button" class="headerButton" id="loginButton" onclick="loginMode()">Log In</button>
        <button type="button" class="headerButton" id="signupButton" onclick="signupMode()">Sign Up</button>
    </div>
    <div id="formBody">
        <h1 class="formHeader" id="loginTitle">Login to Bookshelf</h1>
        <!-- Form posts to itself -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <label for="name" class="signup">Name</label>
            <input type="text" id="name" class="signup" name="name">

            <label for="email" class="login">Email</label>
            <input type="email" id="email" class="login" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="text" value="login" id="mode" name="mode" hidden>

            <input type="submit" id="submit">
            <p class="error"><?php echo $error; ?></p>
        </form>
    </div>
</div>

</html>
