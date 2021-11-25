<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="styles/bootstrap.css">
    <link rel="stylesheet" href="styles/index.css">
    <title>The BookShelf</title>
</head>
<body>

<nav class="navbar navbar-light p-0 steelBlue" id="navbar">

    <div class="col-2 h-100 justify-content-center align-items-center d-flex">
        <img src="images/BookShelf-Logo-alphabg.png" class="logo">
    </div>

    <div class="col h-100 d-flex justify-content-center align-items-center">
        <form class="form-inline w-100" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="col">
                <input class="form-control w-100 rounded-pill" id="searchBar" name="searchBar" type="search" placeholder="Search">
            </div>
            <div class="col-1">
                <button class="btn btn-outline-light rounded-pill" type="submit">
                    <img src="images/bootstrap-icons-1.7.1/search.svg" width="32px">
                </button>
            </div>
        </form>
    </div>

    <div class="col-1 h-100 justify-content-center align-items-center d-flex">
        <a class="btn btn-outline-light w-75"
            <?php
            if (isset($_SESSION['sessionID'])) {
                echo 'href="account.php"';
            } else {
                //Temporarily set sessionID to 1 while login is broken
                $_SESSION['sessionID']=1;
                //echo 'href="login.php"';
            }
            ?>
        >
            <img src="images/bootstrap-icons-1.7.1/person-circle.svg" class="w-100">

        </a>
    </div>
    <div class="col-1 h-100 justify-content-center align-items-center d-flex">
        <button type="button" class="btn btn-outline-light w-75">
            <img src="images/bootstrap-icons-1.7.1/cart-check.svg" class="w-100">
        </button>
    </div>
</nav>

<div class="container-fluid" id="mainContainer">
    <div class="row">
        <div class="col">
            <?php
                $userID=$_SESSION['sessionID'];
                $query = "SELECT COUNT(address_id) FROM user_address WHERE user_id=$userID GROUP BY user_id";
                $result = mysqli_query($conn, $query);
                $count = mysqli_fetch_array($result);

                $query = "SELECT name, email, street_name, city, street_number, postal_code, unit_number FROM user
                INNER JOIN user_address ON user_address.user_id = user.user_id
                INNER JOIN address ON address.address_id = user_address.address_id
                WHERE user.user_id = $userID";

                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
            ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User info</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Name: <?php echo $row["name"];?></li>
                        <li class="list-group-item">Email: <?php echo $row["email"];?></li>
                        <?php
                            $addressCounter=1;
                        /**
                         * @param $addressCounter
                         * @param array $row
                         * @return array
                         */
                        function extracted($addressCounter, array $row)
                        {
                            echo "<li class='list-group-item'>Address " . $addressCounter . ": ";
                            $addressString = $row["street_number"] . " " . $row["street_name"];
                            if (isset($row["unit_number"])) {
                                $addressString .= " - Unit " . $row["unit_number"];
                            }
                            $addressString .= " " . $row["city"] . ", " . $row["postal_code"];
                            echo $addressString . "</li>";
                            return array($addressString, $row);
                        }
                        if ($count[0]==0) {
                                echo "<li class='list-group-item'>Please add an address to your account.</li>";
                            } else {
                            list($addressString, $row) = extracted($addressCounter, $row);
                            if ($count[0]>1){
                                    while ($row = mysqli_fetch_assoc($result)){
                                        $addressCounter+=1;
                                        list($addressString, $row) = extracted($addressCounter, $row);
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            Order info
        </div>
    </div>

</div>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="js/bootstrap.bundle.js"></script>
</body>
</html>
