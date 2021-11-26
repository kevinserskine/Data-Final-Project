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
                <div class="col-2 px-0">
                    <button class="btn btn-primary w-100 collapsed shadow-none border-0" id="collapsingBtn" type="button" data-toggle="collapse" data-target="#sortOption">
                        Sort by
                    </button>
                    <div class="collapse" id="sortOption">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="optionRadios" id="titleASC" value="titleA">
                                <label class="form-check-label" for="titleASC">Title ascending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="optionRadios" id="titleDESC" value="titleD">
                                <label class="form-check-label" for="titleDESC">Title descending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="optionRadios" id="authorASC" value="authorA">
                                <label class="form-check-label" for="authorASC">Author ascending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="optionRadios" id="authorDESC" value="authorD">
                                <label class="form-check-label" for="authorDESC">Author descending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="optionRadios" id="publisherASC" value="publishA">
                                <label class="form-check-label" for="publisherASC">Publisher ascending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="optionRadios" id="publisherDESC" value="publishD">
                                <label class="form-check-label" for="publisherDESC">Publisher descending</label>
                            </div>
                            <input type="submit" value="Sort" name="Sort">
                        </form>
                    </div>
                </div>

                <div class="col-8">
                    <div class="row row-cols-2">
                        <?php
                            $books_per_page = 4;

                            //Getting page from URL and defaulting to 1 if not found
                            if(isset($_GET["page"])) {
                                $page = $_GET["page"];
                            } else {
                                $page = 1;
                            }

                            if(isset($_GET["searchBar"])){
                                $search = $_GET["searchBar"];
                            } else {
                                $search = "";
                            }

                            //Switch to determine sorting order, default to title ASC if nothing posted
                            //Sorting order persists through session to allow for searched sorting
                            if (isset($_POST["optionRadios"])) {
                                switch ($_POST["optionRadios"]) {
                                    case "titleA":
                                        $_SESSION['category'] = "title";
                                        $_SESSION['order'] = "ASC";
                                        break;
                                    case "titleD":
                                        $_SESSION['category'] = "title";
                                        $_SESSION['order'] = "DESC";
                                        break;
                                    case "authorA":
                                        $_SESSION['category'] = "author_name";
                                        $_SESSION['order'] = "ASC";
                                        break;
                                    case "authorD":
                                        $_SESSION['category'] = "author_name";
                                        $_SESSION['order'] = "DESC";
                                        break;
                                    case "publishA":
                                        $_SESSION['category'] = "publisher_name";
                                        $_SESSION['order'] = "ASC";
                                        break;
                                    case "publishD":
                                        $_SESSION['category'] = "publisher_name";
                                        $_SESSION['order'] = "DESC";
                                        break;
                                }
                            } else {
                                if (!isset($_SESSION['category'])){
                                    $_SESSION['category'] = "title";
                                    $_SESSION['order'] = "ASC";
                                }
                            }

                            $category=$_SESSION['category'];
                            $order=$_SESSION['order'];
                            $start_from = ($page-1) * $books_per_page;

                            $terms = explode(" ",$search);
                            $term = "'%".$terms[0]."%'";
                            $countQ= "SELECT COUNT(*) from book
                            INNER JOIN book_author ON book.book_id=book_author.book_id
                            INNER JOIN author ON author.author_id=book_author.author_id
                            INNER JOIN publisher ON book.publisher_id=publisher.publisher_id
                            WHERE title LIKE $term
                            OR author_name LIKE $term
                            OR publisher_name LIKE $term";
                            if (count($terms)>1){
                                $query = "SELECT * from book
                                INNER JOIN book_author ON book.book_id=book_author.book_id
                                INNER JOIN author ON author.author_id=book_author.author_id
                                INNER JOIN publisher ON book.publisher_id=publisher.publisher_id
                                WHERE title like $term
                                OR author_name LIKE $term
                                OR publisher_name LIKE $term
                                ";
                                for ($i=1;$i<count($terms);$i++){
                                    $term = "'%".$terms[$i]."%'";
                                    $query .=" OR title like ".$term." OR author_name LIKE ".$term." OR publisher_name LIKE ".$term;
                                    $countQ .=" OR title like ".$term." OR author_name LIKE ".$term." OR publisher_name LIKE ".$term;
                                }
                                $query .= " ORDER BY ".$category." ".$order." LIMIT ".$start_from.", ".$books_per_page;
                            } else {
                                $query = "SELECT ISBN13, img_link from book
                                INNER JOIN book_author ON book.book_id=book_author.book_id
                                INNER JOIN author ON author.author_id=book_author.author_id
                                INNER JOIN publisher ON book.publisher_id=publisher.publisher_id
                                WHERE title LIKE $term
                                OR author_name LIKE $term
                                OR publisher_name LIKE $term
                                ORDER BY $category $order 
                                LIMIT $start_from, $books_per_page";
                            }
                            $rs_result = mysqli_query($conn, $query);

                            //Output a card for each book found
                            while ($row = mysqli_fetch_assoc($rs_result)){
                                echo '<div class="col border bookCard">';
                                    echo '<button class="btn btn-outline-link h-100 w-100" type="button" data-toggle="modal" data-target="#bookModal" data-ISBN="'.$row["ISBN13"].'">';
                                        echo '<img src="'.$row["img_link"].'" class="w-25">';
                                    echo '</button>';
                                echo '</div>';
                            }

                            //Pads the rest of the mock 2x2 if not enough books are found
                            if (mysqli_num_rows($rs_result)<4){
                                for ($i=0;$i<$books_per_page-mysqli_num_rows($rs_result);$i++){
                                    echo '<div class="col border bookCard">Disabled</div>';
                                }
                            }
                        ?>

                        <div class="col-12 border border-dark justify-content-center align-items-center d-flex" id="botBar">
                            <?php
                                $result = mysqli_query($conn, $countQ);
                                $row = mysqli_fetch_array($result);
                                $total_records = $row[0];

                                $total_pages = ceil($total_records/$books_per_page);
                                $pagLink="";

                                if(isset($_GET["searchBar"])){
                                    if($page>=2) {
                                        echo "<a href='index.php?page=".($page-1)."&searchBar=".$_GET['searchBar']."'> Prev </a>";
                                    }

                                    for($i=1; $i<=$total_pages; $i++){
                                        if($i==$page){
                                            $pagLink .="<a class='active' href='index.php?page=".$i."&searchBar=".$_GET['searchBar']."'>".$i."</a>";
                                        } else {
                                            $pagLink .="<a href='index.php?page=".$i."&searchBar=".$_GET['searchBar']."'>".$i."</a>";
                                        }
                                    }
                                    echo $pagLink;

                                    if($page<$total_pages){
                                        echo "<a href='index.php?page=".($page+1)."&searchBar=".$_GET['searchBar']."'> Next </a>";
                                    }
                                } else {
                                    if($page>=2) {
                                        echo "<a href='index.php?page=".($page-1)."'> Prev </a>";
                                    }

                                    for($i=1; $i<=$total_pages; $i++){
                                        if($i==$page){
                                            $pagLink .="<a class='active' href='index.php?page=".$i."'>".$i."</a>";
                                        } else {
                                            $pagLink .="<a href='index.php?page=".$i."'>".$i."</a>";
                                        }
                                    }
                                    echo $pagLink;

                                    if($page<$total_pages){
                                        echo "<a href='index.php?page=".($page+1)."'> Next </a>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-2 px-0">
                    <div class="row-cols-1" style="overflow-y: scroll">
                        <div class="card">
                            <ul class="list-group list-group-flush" id="cartList">
                                <?php
                                    if(isset($_SESSION['cart'])){
                                        foreach ($_SESSION['cart'] as &$value){
                                            foreach ($value as &$value2){
                                                echo "<li class='list-group item'>";
                                                echo "Title: ".$value2['title']." - Quantity: ".$value2['quantity']. " - ";
                                                if (isset($value2['price'])){
                                                    echo "Price per book - ".$value2['price'];
                                                } else {
                                                    echo "Borrowing";
                                                }
                                                echo "</li>";
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="col position-absolute border border-dark" id="botBar">
                            <span id="totalCost">
                                <?php
                                if (isset($_SESSION['totalCost'])){
                                  echo "Total: ".$_SESSION['totalCost'];
                                } else {
                                  echo "Total: 0.00";
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="bookModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bookModalLabel">Book :)</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="card justify-content-center align-items-center d-flex">
                                <img src="https://covers.openlibrary.org/b/id/7555639-L.jpg" id="modalImg" class="card-img-top w-25">
                                <ul class="list-group list-group-flush w-100">
                                    <li class="list-group-item" id="authorLi">Author</li>
                                    <li class="list-group-item" id="publisherLi">Publisher</li>
                                    <li class="list-group-item" id="pubDateLi">Publication Date</li>
                                    <li class="list-group-item" id="ISBNLi">ISBN13</li>
                                </ul>
                                <div class="card-footer w-100" id="PriceFoot">
                                    Price
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="borrowBtn">Borrow</button>
                                <button type="button" class="btn btn-success" id="buyBtn">Buy</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" crossorigin="anonymous"></script>
        <script src="scripts/bootstrap.bundle.js"></script>
        <script>
            $('#bookModal').on('show.bs.modal', function(event){
                var button = $(event.relatedTarget);
                var ISBN = button.data('isbn');
                var modal = $(this)
                $.ajax({
                    type: "POST",
                    url: "bookModal.php",
                    data: {
                        ISBN: ISBN
                    },
                    success: function(data) {
                        modal.find('.modal-title').text(data["Title"]);
                        $("#authorLi").text("Author: "+data["Author"]);
                        $("#publisherLi").text("Publisher: "+data["Publisher"]);
                        $("#pubDateLi").text("Publication date: "+data["Date"]);
                        $("#ISBNLi").text("ISBN13: "+data["ISBN"]);
                        $("#PriceFoot").text("Price: "+data["Price"]);
                        $("#modalImg").attr("src",data["Img"]);
                    }
                });
            });
            $(document).ready(function(){
                $('#borrowBtn').click(function(){
                    var string = $(this).parent().siblings('.card').find("#ISBNLi")["0"].innerText;
                    var ISBN = string.slice(8);
                    $.ajax({
                        type: "POST",
                        url: "bookCart.php",
                        data: {
                            ISBN: ISBN,
                            mode: "borrow"
                        },
                        success: function(data) {
                            var child = "<li class='list-group-item'>Title: "+data["title"]+" - Quantity "+data["quantity"]+"</li>";
                            $("#cartList").append(child)
                        }
                    });
                });
                $('#buyBtn').click(function(){
                    var string = $(this).parent().siblings('.card').find("#ISBNLi")["0"].innerText;
                    var ISBN = string.slice(8);
                    $.ajax({
                        type: "POST",
                        url: "bookCart.php",
                        data: {
                            ISBN: ISBN,
                            mode: "buy"
                        },
                        success: function(data) {
                            $("#totalCost").text("Total: "+data['totalCost']);
                            var child = "<li class='list-group-item'>Title: "+data["title"]+" - Quantity "+data["quantity"]+" - Price per book - "+data["price"]+"</li>";
                            $("#cartList").append(child)
                        }
                    });
                });
            });
        </script>
    </body>
</html>
