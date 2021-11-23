<?php

$error = "";


// If this is a post request, handle the login (this page posts to itself)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
}


?>


<html>

<head>

    <link rel="stylesheet" href="styles/login.css">

</head>

<body>

<button class="tablink" onclick="openPage('Login', this, 'white')" id="defaultOpen">Login</button>
<button class="tablink" onclick="openPage('SignUp', this, 'white')">Sign Up</button>


<div id="Login" class="tabcontent">
    <h3 class= "head">Login to THE BOOKSHELF</h3>
    <form method= "POST">

        <label class= "label">Username</label>
        <input type="text" class="textbox" name="username" required>

        <label class= "label">Password</label>
        <input type="password" class="textbox" name="password" required>

        <button type="submit" id="logbtn" name="login">Login</button>

    </form>
</div>

<div id="SignUp" class="tabcontent">
    <h3 class="head">Sign Up to THE BOOKSHELF</h3>
    <form method= "POST">

        <label class= "label">Username</label>
        <input type="text" class="textbox" name="username" required>

        <label class= "label">Password</label>
        <input type="password" class="textbox" name="password" required>

        <button type="submit" id="signbtn" name="signup">Sign Up</button>

    </form>


</div>




</body>

<script>

    function openPage(pageName, elmnt, color){
        var i, tabcontent, tablinks;

        tabcontent = document.getElementsByClassName("tabcontent");

        for (i = 0; i < tabcontent.length; i++){
            tabcontent[i].style.display = "none";
        }

        tablinks = document.getElementsByClassName("tablink");
        for(i = 0; i< tablinks.length; i++){
            tablinks[i].style.backgroundColor = "";
        }

        document.getElementById(pageName).style.display = "block";

        elmnt.style.backgroundColor = color;

    }

    document.getElementById("defaultOpen").click();

</script>

</html>