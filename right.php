<html>
<head>
    <title> Grocery Store </title>
    <link rel="stylesheet" href="CSS/right.css" type="text/css">
    <script src="JS/jquery.min.js"></script>
    <script src="JS/display.js"></script>
    <base target="bottom-frame">
</head>

<body>
    <h1>Product Information</h1>   
    </div>
    <div id="product-details" style="display:none;">
</div>

<?php
session_start();

$servername = "74.48.21.122";
$username = "nana"; 
$password = "12345678";     
$dbname = "assignment1"; 

if(isset($_GET) && !empty($_GET)){
    // echo "Empty";
} else {
    header("Location: right.php");
}

if(isset($_SESSION['id']) && isset($_SESSION['quantity']) && !empty($_SESSION['id']) && !empty($_SESSION['quantity'])) {
    $key = array_search($_GET['id'], $_SESSION['id'], true); 
    if($key !== false) {
        $existingQuantity = $_SESSION['quantity'][$key];
    }
}


$conn = new mysqli($servername, $username, $password, $dbname);

if(isset($_GET['id']) && !empty($_GET['id'])){
    if($_GET['id'] === "NULL") {
        echo "<div class='centered-message'><h2>Welcome to our Grocery Store! Please select a product to view its details.</h2></div>";
    }else{
        $var = $_GET['id'];
        $product_array = "SELECT * FROM products WHERE product_id='$var'";

        $result = mysqli_query($conn,$product_array);


        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);  
            $existingQuantity = 0;
            if(isset($_SESSION['id'])) {
                $key = array_search($var, $_SESSION['id']);
                if($key !== false) {
                    $existingQuantity = $_SESSION['quantity'][$key];
                }
            }
            $maxQuantity = $row['in_stock'] - $existingQuantity;

            echo "<div id='pDisplay'>";
            echo "<form name='prodForm' action='session.php' method ='get'>";
            echo "<table id='prodInfo'>";
        
            echo "<tr><td><b>Product Image:</b></td><td style='text-align: center;'><img src='".$row['image_url']."' alt='Product Image' class='product-image'></td></tr>";
        
            echo "<tr><td><b>Product ID:</b></td><td>" . $row['product_id'] . "</td></tr>";
            echo "<tr><td><b>Product Name:</b></td><td>" . $row['product_name'] . "</td></tr>";
            echo "<tr><td><b>Price:</b></td><td>" . $row['unit_price'] . "</td></tr>";
            echo "<tr><td><b>Unit Quantity:</b></td><td>" . $row['unit_quantity'] . "</td></tr>";
            echo "<tr><td><b>In Stock:</b></td><td>" . $row['in_stock'] . "</td></tr>";
        
            echo"<tr><td>Order #<input type='number' id='quantity' name='quantity' value='1' min='1' max='$maxQuantity'></td><td><input type='submit' value='Add' id= 'add'></td></tr> ";

            echo "</table>";
            echo "<input type = 'hidden' id = 'prodId' name = 'prodId' value ='".$row['product_id']."'>";
            echo "<input type = 'hidden' id = 'unitQuant' name = 'unitQuant' value ='".$row['unit_quantity']."'>";
            echo "<input type = 'hidden' id = 'form_products' name = 'form_products' value ='".$row['product_name']."'>";
            echo "<input type ='hidden' id = 'form_prod_price' name = 'form_prod_price' value ='".$row['unit_price']."'>";
            echo "<br>";
            echo "</form></div>";
        }
        
        else if(!($result))
        {
            echo "<h1> No product to display. </h1> ";
        }
    }
}
mysqli_close($conn);

?>


</body>
</html>