<html>
    <head>
        <title> Grocery Store</title>
        <link rel="stylesheet" href="CSS/cart.css" type="text/css">
        <script src="JS/jquery.min.js"></script>
        <script src="JS/display.js"></script>
        <base target="bottom-frame">
    </head>
    <body onload="return clear()">
        <div id="title">
            <?php
                session_start();
                session_destroy();
            ?>
        </div>
        <div id="clear-message">
            <?php
                echo "<h3>Product list is empty. Please add product to Checkout.<h3>";
                echo '<a href="cart.php">Return to cart</a>';
            ?>
        </div>
    </body>
</html>
