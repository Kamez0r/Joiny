<?php require "../includes/init.php"; ?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>

    <script src="./assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Home | Joiny</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="./assets/theme_switcher.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./assets/dashboard.css" rel="stylesheet">
</head>
<body>


<?php require("../includes/templates/theme_switcher.php"); ?>


<!--Gray Top-bar-->
<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">Joiny Webview</a>

    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle search">
                <svg class="bi"><use xlink:href="#search"/></svg>
            </button>
        </li>
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <svg class="bi"><use xlink:href="#list"/></svg>
            </button>
        </li>
    </ul>

    <div id="navbarSearch" class="navbar-search w-100 collapse">
        <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
    </div>
</header>

<div class="container-fluid">
    <!--Main Body-->
    <div class="row">

        <?php require "../includes/templates/menu.php"; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">New Ingest Transaction</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                </div>
            </div>

            <form class="col-lg-6">
                <div class="mb-3">
                    <label for="transactionName" class="form-label">Transaction Name</label>
                    <div class="input-group">

                        <input type="transactionName" class="form-control" id="transactionName" aria-describedby="transactionNameHelp">
                        <button class="btn btn-outline-secondary" type="button" id="transactionNameAuto">Auto</button>
                    </div>
                    <div id="transactionNameHelp" class="form-text">This is used to easily identify your transaction later.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="customFile">Upload</label>
                    <input type="file" class="form-control" id="customFile" accept=".csv">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>


        </main>
    </div>
</div>


<script src="./assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.js" integrity="sha384-eI7PSr3L1XLISH8JdDII5YN/njoSsxfbrkCTnJrzXt+ENP5MOVBxD+l6sEG4zoLp" crossorigin="anonymous"></script><script src="assets/js/custom/home.js"></script></body>


</html>
