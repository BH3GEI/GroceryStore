<html>
<head>
    <title> Grocery Store</title>
    <link rel="stylesheet" href="CSS/cart.css" type="text/css">
    <script src="JS/jquery.min.js"></script>
    <script src="JS/display.js"></script>
</head>

<body>
    <div id="title">
        <form action="clear.php" method="post">
            <input type="submit" id="clear" value="Clear"/>
        </form>
        <form action="checkout.php" method="post" id="checkout-form">
            <input type="submit" id="checkout" class="checkout-button" value="Checkout" onclick="return display_cart();"/>
        </form>
        <h3>Cart List</h3>
    </div>
    <hr/>
    <div>
        <table id="list">
            <tr>
                <th>Product ID</th> <th>Product Name</th> <th>Unit Quantity</th> <th>Unit Price ($)</th> <th>Units</th> <th>Value ($)</th> <th>Adjust Quantity</th> <th>Remove</th>
            </tr>
            <?php
                $servername = "74.48.21.122";
                $username = "nana"; 
                $password = "12345678";     
                $dbname = "assignment1"; 
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }

            session_start();
            if(isset($_SESSION['products'])) {
                $cartPrice = 0;
                for ($i=0; $i<sizeof($_SESSION['products']); $i++) {
                    $productId = $_SESSION['id'][$i];
                    $quantityId = 'quantity_' . $productId;

                    $sql = "SELECT in_stock FROM products WHERE product_id = $productId";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $instock = $row['in_stock'];
                    } else {
                        $instock = 0; 
                    }
                    // echo "<tr id='row_{$productId}' data-instock='{$instock}' >";
                    echo "<tr id='row_{$productId}' data-instock='{$instock}'>
                        <td align='center'>{$productId}</td>
                        <td align='center'>{$_SESSION['products'][$i]}</td> 
                        <td align='center'>{$_SESSION['quant'][$i]}</td>
                        <td align='center'>{$_SESSION['price'][$i]}</td>
                        <td align='center'><span id='{$quantityId}'>{$_SESSION['quantity'][$i]}</span></td>
                        <td align='center'><span id='value_{$productId}'>" . number_format($_SESSION['price'][$i] * $_SESSION['quantity'][$i], 2) . "</span></td>
                        
                        <td align='center'>
                            <button class='adjust-qty' onclick='adjustQuantity(\"{$productId}\", 1)'>+</button>
                            <button class='adjust-qty' onclick='adjustQuantity(\"{$productId}\", -1)'>-</button>
                        </td>
                        <td align='center'>
                        <button class='remove-item' onclick='removeItem(\"{$productId}\")'>Remove</button>
                        </td>
                    </tr>";
                    $cartPrice += $_SESSION['price'][$i] * $_SESSION['quantity'][$i];
                }
                echo "</table><br><table id='total'>";
                echo "<tr>
                    <td>Total price for " . sizeof($_SESSION['products']) . " product(s):</td>
                    <td align='center'><span id='total_price'>" . number_format($cartPrice, 2) . "</span></td>
                </tr>";   
            } else {
                echo "<h1>Shopping cart is empty. Please pick a product.</h1>";
            }
            ?>
    </div>
    <script>
        $(document).ready(function() {
            var isCartEmpty = <?php echo empty($_SESSION['products']) ? 'true' : 'false'; ?>;
            if(isCartEmpty) {
                $('#checkout').addClass('disabled-button').prop('disabled', true);
            }
        });
    function adjustQuantity(productId, change) {
        var currentQuantity = parseInt($('#quantity_' + productId).text(), 10);
        var instock = parseInt($('#row_' + productId).data('instock'), 10); 
        var newQuantity = currentQuantity + change;
        if(newQuantity > 0) {  
            $.ajax({
                url: 'adjust_quantity.php',
                type: 'POST',
                data: {
                    'product_id': productId,
                    'new_quantity': newQuantity
                },
                dataType: 'json', 
                success: function(response) {
                    var valueId = 'value_' + productId;
                    $('#quantity_' + productId).text(response.new_quantity);
                    $('#' + valueId).text(response.new_value);
                    $('#total_price').text(response.cart_total); 
                },
                error: function() {
                    alert('Could not update quantity. Please try again.');
                }
            });
        if (newQuantity > instock) {
            alert('Cannot add more items than are in stock.');
            return;
          }   
        }
    }
    function removeItem(productId) {
        if(confirm('Are you sure you want to remove this item from your cart?')) {
            $.ajax({
                url: 'remove_item.php',
                type: 'POST',
                data: {
                    'product_id': productId
                },
                success: function(response) {
                    if(response.success) {
                        $('#row_' + productId).remove(); 
                        $('#total_price').text(response.new_total); 
                    } else {
                        alert('Could not remove item. Please try again.');
                    }
                },
                error: function() {
                    alert('Error while requesting to remove item.');
                }
            });
        }
    }
    </script>
</body>
</html>