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
            <form class="form-inline w-100">
                <div class="col">
                    <input class="form-control w-100 rounded-pill" type="search" placeholder="Search">
                </div>
                <div class="col-1">
                    <button class="btn btn-outline-light rounded-pill" type="submit">
                        <img src="images/bootstrap-icons-1.7.1/search.svg" width="32px">
                    </button>
                </div>
            </form>
        </div>
        <div class="col-1 h-100 justify-content-center align-items-center d-flex">
            <button type="button" class="btn btn-outline-light w-75">
                <img src="images/bootstrap-icons-1.7.1/person-circle.svg" class="w-100">
            </button>
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
                    <div class="card card-body sortOpt">
                        Title
                    </div>
                    <div class="card card-body sortOpt">
                        Author
                    </div>
                    <div class="card card-body sortOpt">
                        Publication year
                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="row row-cols-2">
                    <div class="col border bookCard">A</div>
                    <div class="col border bookCard">B</div>
                    <div class="col border bookCard">C</div>
                    <div class="col border bookCard">D</div>
                    <div class="col-12 border border-dark justify-content-center align-items-center d-flex" id="botBar">Pagination</div>
                </div>
            </div>

            <div class="col-2 px-0">
                <div class="row-cols-1" style="overflow-y: scroll">
                    <div class="col position-absolute border border-dark" id="botBar">Total</div>
                </div>

            </div>

        </div>
    </div>

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="js/bootstrap.bundle.js"></script>

  </body>
</html>
