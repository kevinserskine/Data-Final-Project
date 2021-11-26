<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    session_start();
    include 'components/isAuthed.php';
    $userID = $_SESSION['sessionID'];

?>

<!doctype html>
<html lang="en">
    <head>
        <?php include_once 'components/imports.php'?>
        <title>The BookShelf</title>
    </head>
    <body>
        <nav class="navbar navbar-light p-0 steelBlue" id="navbar">

            <div class="col-2 h-100 justify-content-center align-items-center d-flex">
                <img src="images/BookShelf-Logo-alphabg.png" class="logo">
            </div>

            <div class="col h-100 d-flex justify-content-center align-items-center">
                <form class="form-inline w-100" method="GET" action="index.php">
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
                <a class="btn btn-outline-light w-75" href="account.php">
                    <img src="images/bootstrap-icons-1.7.1/person-circle.svg" class="w-100">
                </a>
            </div>
            <div class="col-1 h-100 justify-content-center align-items-center d-flex">
                <button type="button" class="btn btn-outline-light w-75">
                    <img src="images/bootstrap-icons-1.7.1/cart-check.svg" class="w-100">
                </button>
            </div>
        </nav>
        <?php
            //If address information is posted, insert into the database - better to just use an ajax call in hindsight
            if(isset($_POST['cityAdd'])) {
                $streetNumber = $_POST['streetNumberAdd'];
                $streetName = $_POST['streetNameAdd'];
                $city = $_POST['cityAdd'];
                $postal = $_POST['postalAdd'];
                if($_POST['unitNumberAdd']==''){
                    $values = $streetNumber.", '".$streetName."', '".$city."', '".$postal."', NULL";
                } else {
                    $unit = $_POST['unitNumberAdd'];
                    $values = $streetNumber.", '".$streetName."', '".$city."', '".$postal."', ".$unit;
                }
                $query = "INSERT INTO address (street_number, street_name, city, postal_code, unit_number) VALUES ($values)";
                mysqli_query($conn, $query);
                $addressID=mysqli_insert_id($conn);
                $query = "INSERT INTO user_address VALUES ($userID, $addressID)";
                mysqli_query($conn, $query);
            }
        ?>
        <div class="container-fluid" id="mainContainer">
            <div class="row">
                <div class="col">
                    <?php
                        //Pulls up user info
                        $query = "SELECT name, email FROM user WHERE user_id=$userID";
                        $result = mysqli_query($conn, $query);
                        $row=mysqli_fetch_assoc($result);
                        $name = $row['name'];
                        $email = $row['email'];

                        //Pulls up how many addresses the user has
                        $query = "SELECT COUNT(address_id) FROM user_address WHERE user_id=$userID GROUP BY user_id";
                        $result = mysqli_query($conn, $query);
                        $count = mysqli_fetch_array($result);
                        if (mysqli_num_rows($result)==0) {
                            $count[0]=0;
                        }

                        $query = "SELECT user_address.address_id, name, email, street_name, city, street_number, postal_code, unit_number FROM user
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
                                <li class="list-group-item">Name: <?php echo $name;?></li>
                                <li class="list-group-item">Email: <?php echo $email;?></li>
                                <?php
                                    $addressCounter=1;
                                /**
                                 * @param $addressCounter
                                 * @param array $row
                                 * @return array
                                 */
                                //Auto genned function that removed some common code, creates a new li with address info
                                function extracted($addressCounter, array $row)
                                {
                                    echo "<li class='list-group-item'>Address " . $addressCounter . ": ";
                                    $addressString = $row["street_number"] . " " . $row["street_name"];
                                    if (isset($row["unit_number"])) {
                                        $addressString .= " - Unit " . $row["unit_number"];
                                    }
                                    $addressString .= " " . $row["city"] . ", " . $row["postal_code"];
                                    echo $addressString . "
                                    <button type='button' class='close' name='delBtn' id=".$row["address_id"].">
                                        <span>&times;</span>
                                    </button>
                                    </li>";
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
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#addAddress">Add address</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal fade" id="addAddress" tabindex="-1">
                        <!--Modal used for adding new addresses-->
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAddressLabel">Add an address</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="addAddressForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="form-group">
                                            <label for="streetNumberAdd">Street Number</label>
                                            <input type="number" class="form-control" id="streetNumberAdd" name="streetNumberAdd" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="unitNumberAdd">Unit number (Optional)</label>
                                            <input type="number" class="form-control" id="unitNumberAdd" name="unitNumberAdd">
                                        </div>
                                        <div class="form-group">
                                            <label for="streetNameAdd">Street Name</label>
                                            <input type="text" class="form-control" id="streetNameAdd" name="streetNameAdd" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="cityAdd">City</label>
                                            <input type="text" class="form-control" id="cityAdd" name="cityAdd" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="postalAdd">Postal Code</label>
                                            <input type="text" class="form-control" id="postalAdd" name="postalAdd" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" form="addAddressForm">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Your orders</h5>
                            <ul class="list-group list-group-flush">
                                <?php
                                //Pulls up all user's order, can definitely be improved to display details better
                                $query = "SELECT order_list.price, book.title, address.street_name, address.street_number FROM user_order
                                    INNER JOIN order_list ON order_list.order_id = user_order.order_id
                                    INNER JOIN address ON address.address_id = user_order.dest_address_id
                                    INNER JOIN book ON book.book_id = order_list.book_id
                                    WHERE user_id=$userID";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)!=0){
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo '<li class="list-group-item">Title: '.$row["title"].' - Status: Preparing - Price: '.$row["price"].' - Address: '.$row["street_number"].' '.$row["street_name"].'</li>';
                                    }
                                } else {
                                    echo '<li class="list-group-item">No orders found</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" crossorigin="anonymous"></script>
        <script src="scripts/bootstrap.bundle.js"></script>
        <script>
            $(document).ready(function() {
                //Used for deleting and hiding addresses
                $('[name="delBtn"]').click(function() {
                    $.ajax({
                        type: "POST",
                        url: "address.php",
                        data: {
                            id: this.id
                        }
                    });
                    $(this).parent().hide();
                });
            });
        </script>
    </body>
</html>
