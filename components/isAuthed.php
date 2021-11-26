<?php
// Checks if user's session ID has been set by login.  If it has not, redirects them to index
if (!(isset($_SESSION['sessionID']))){
    header("Location:index.php");
    exit;
}