<?php
    include_once 'components/dbConnect.php';
    $conn = getConnection();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $ISBN=$_POST['ISBN'];
    $query="SELECT title, author_name, publisher_name, publication_date, price, img_link FROM book
    INNER JOIN publisher ON book.publisher_id=publisher.publisher_id
    INNER JOIN book_author ON book.book_id=book_author.book_id
    INNER JOIN author ON author.author_id=book_author.author_id
    WHERE ISBN13 = $ISBN";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    header('Content-Type: application/json');
    echo json_encode(array('ISBN' => $ISBN, 'Title' => $row['title'], 'Author' => $row['author_name'], 'Publisher' => $row['publisher_name'], 'Date' => $row['publication_date'], 'Price' => $row['price'], 'Img' => $row['img_link']));
?>
