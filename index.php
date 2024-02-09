<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales and Inventory System</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .open-btn {
            font-size: 30px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<h2>Sales and Inventory System</h2>

<div id="sidebar" class="sidebar">
    <a href="?page=add_product">Add Product</a>
    <a href="?page=v_product">View Products</a>
    <a href="?page=m_sales">Make Sale</a>
    <a href="?page=v_sales">View Sales</a>
</div>

<button class="open-btn" onclick="toggleNav()">S</button>

<div id="main">
<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    $filePath = $page . ".php";
    if (!empty($page) && file_exists($filePath)) {
        include $filePath;
    } else {
        echo "Page not found";
    }
?>

</div>

<script>
    function toggleNav() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("main");
        
        if (sidebar.style.width === "250px") {
            sidebar.style.width = "0";
            mainContent.style.marginLeft = "0";
        } else {
            sidebar.style.width = "250px";
            mainContent.style.marginLeft = "250px";
        }
    }

    function loadContent(url) {
        $.get(url, function(data) {
            $('#main').html(data);
            toggleNav(); // Close the sidebar after loading content
        }).fail(function() {
            console.error('Error loading content.');
        });
    }
</script>
</body>
</html>
